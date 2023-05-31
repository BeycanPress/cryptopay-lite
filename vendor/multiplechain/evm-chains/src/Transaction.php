<?php

namespace MultipleChain\EvmChains;

use Exception;
use Web3\Validators\AddressValidator;
use Web3\Validators\BlockHashValidator;
use Web3\Validators\TransactionValidator;

final class Transaction
{
    /**
     * Provider
     * @var Provider
     */
    private $provider;
    
    /**
     * Transaction hash
     * @var string
     */
    private $hash;

    /**
     * Transaction data
     * @var object
     */
    private $data;

    /**
     * @param string $txHash
     * @param Provider|null $provider
     * @throws Exception
     */
    public function __construct(string $txHash, Provider $provider)
    {
        if (BlockHashValidator::validate($txHash) === false) {
            throw new \Exception('Invalid transaction id!', 24000);
        }

        $this->hash = $txHash;
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * @return object|null
     */
    public function getData() : ?object
    {
        if ($this->data) return $this->data;
        $this->provider->methods->getTransactionByHash($this->hash, function($err, $tx){
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                if (TransactionValidator::validate((array)$tx) === false) {
                    throw new \Exception('Invalid transaction data!', 25000);
                } else {
                    $this->data = $tx;
                }
            }
        });

        $this->provider->methods->getTransactionReceipt($this->hash, function($err, $tx){
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $this->data->status = isset($tx->status) ? $tx->status : null;
                $this->data->gasUsed = isset($tx->gasUsed) ? $tx->gasUsed : null;
            }
        });
        
        return $this->data;
    }

    /**
     * @return object|null
     */
    public function decodeInput() : ?object
    {
        $this->getData();
        $input = $this->data->input;
        $pattern = '/.+?(?=000000000000000000000000)/';
        preg_match($pattern, $input, $matches, PREG_OFFSET_CAPTURE, 0);
        $method = $matches[0][0];

        if ($input != '0x') {
            $input = str_replace($method, '', $input);
            $receiver = '0x' . substr(substr($input, 0, 64), 24);
            $amount = '0x' . ltrim(substr($input, 64), 0);
            return (object) compact('receiver', 'amount');
        } else {
            return null;
        }
    }

    /** 
     * @return int
     */
    public function getConfirmations() : int
    {
        try {
            $this->getData();
            $currentBlock = $this->provider->getBlockNumber();
            if ($this->data->blockNumber === null) return 0;
            
            if (is_string($this->data->blockNumber)) {
                $this->data->blockNumber = Utils::toDec($this->data->blockNumber, 0);
            }

            $confirmations = $currentBlock - $this->data->blockNumber;
            return $confirmations < 0 ? 0 : $confirmations;
        } catch (Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return ?bool
     */
    public function getStatus() : ?bool
    {
        $this->getData();
        $result = null;

        if ($this->data == null) {
            $result = false;
        } else {
            if ($this->data->blockNumber !== null) {
                if ($this->data->status == '0x0') {
                    $result = false;
                } else {
                    $result = true;
                }
            }
        }
        
        return $result;
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        $result =  $this->getStatus();

        if (is_bool($result)) {
            return $result;
        } else {
            return $this->validate();
        }
    }

    /**
     * @param string address 
     * @return bool
     */
    public function verifyTokenTransfer(string $address) : bool
    {
        if (AddressValidator::validate($address = strtolower($address)) === false) {
            throw new Exception('Invalid token address!');
        }

        if ($this->validate()) {
            if ($this->data->input == '0x') {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function verifyCoinTransfer() : bool
    {
        if ($this->validate()) {
            if ($this->data->value == '0x0') {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string receiver 
     * @param int amount 
     * @param string address 
     * @return bool
     */
    public function verifyTokenTransferWithData(string $receiver, float $amount, string $address) : bool
    {
        if (AddressValidator::validate($receiver = strtolower($receiver)) === false) {
            throw new Exception('Invalid receiver address!');
        }

        if ($this->verifyTokenTransfer($address)) {
            $decodedInput = $this->decodeInput();
            $token = new Token($address, [], $this->provider);

            $data = (object) [
                "receiver" => strtolower($decodedInput->receiver),
                "amount" => Utils::toDec($decodedInput->amount, ($token->getDecimals()))
            ];

            if ($data->receiver == $receiver && $data->amount == $amount) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string receiver 
     * @param int amount 
     * @return bool
     */
    public function verifyCoinTransferWithData(string $receiver, float $amount) : bool 
    {
        if (AddressValidator::validate($receiver = strtolower($receiver)) === false) {
            throw new Exception('Invalid receiver address!');
        }

        if ($this->verifyCoinTransfer()) {

            $coin = new Coin($this->provider);

            $data = (object) [
                "receiver" => strtolower($this->data->to),
                "amount" => Utils::toDec($this->data->value, ($coin->getDecimals()))
            ];

            if ($data->receiver == $receiver && $data->amount == $amount) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string|null tokenAddress
     * @return bool
     */
    public function verifyTransfer(?string $tokenAddress = null) : bool
    {
        if (!$tokenAddress) {
            return $this->verifyCoinTransfer();
        } else {
            return $this->verifyTokenTransfer($tokenAddress);
        }
    }

    /**
     * @param object $config
     * @return bool
     */
    public function verifyTransferWithData(object $config) : bool
    {
        if (isset($config->tokenAddress) && !is_null($config->tokenAddress)) {
            return $this->verifyTokenTransferWithData($config->receiver, $config->amount, $config->tokenAddress);
        } else {
            return $this->verifyCoinTransferWithData($config->receiver, $config->amount);
        }
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return rtrim($this->provider->getNetwork()->explorerUrl, '/') . '/tx/' . $this->hash;
    }
}