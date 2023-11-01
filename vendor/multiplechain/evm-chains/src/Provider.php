<?php

namespace MultipleChain\EvmChains;

use Web3\Web3;
use Web3\Eth;
use Exception;
use Web3\Providers\HttpProvider;
use MultipleChain\EvmBasedChains;
use Web3\RequestManagers\HttpRequestManager;
use phpseclib\Math\BigInteger as BigNumber;
use Web3p\EthereumTx\Transaction as PendingTransaction;

final class Provider
{

    /**
     * Current time
     * @var int
     */
    private $time;

    /**
     * The connected blockchain network
     * @var object
     */
    private $network;

    /**
     * Current blockchain gas price
     * @var int
     */
    private $defaultGasPrice = 10000000000;

    /**
     * Current blockchain transfer nonce
     * @var int
     */
    private $defaultNonce = 1;
    
    /**
     * Web3 instance
     * @var Web3
     */
    public $web3;

    /**
     * Eth instance / RPC Api methods
     * @var Eth
     */
    public $methods;

    /**
     * @var PendingTransaction|null
     */
    private $pendingTransaction = null;

    /**
     * Exception codes
     * @var array
     */
    public static $codes = [
        10000 => 'Insufficient balance!',
        11000 => 'There was a problem retrieving the balance',
        12000 => 'There was a problem retrieving the decimals value',
        13000 => 'There was a problem retrieving the transaction id',
        14000 => 'There was a problem retrieving the chain id',
        14001 => 'There was a problem retrieving the total supply',
        15000 => 'Before you can use the signing process, you must create a pending transaction.',
        16000 => 'Transaction time out!',
        18000 => 'It should contain native currency, symbol and decimals values',
        20000 => 'The amount cannot be zero or less than zero!',
        21000 => 'Invalid sender address!',
        22000 => 'Invalid receiver address!',
        23000 => 'Invalid token address!',
        24000 => 'Invalid transaction id!',
        25000 => 'Invalid transaction data!',
        26000 => 'Transaction failed!'
    ];

    /**
     * @param object|array $options
     */
    public function __construct($options)
    {
        $options = is_array($options) ? (object) $options : $options;

        // Set params
        $network = isset($options->network) ? $options->network : 'mainnet';
        $timeOut = isset($options->timeOut) ? $options->timeOut : 60;
        $testnet = isset($options->testnet) ? $options->testnet : false;
        $networks = $testnet ? EvmBasedChains::$testnets : EvmBasedChains::$mainnets;

        if (is_object($network)) {
            $this->network = $network;
        } elseif (is_array($network)) {
            $this->network = (object) $network;
        } else if (isset($networks[$network])) {
            $this->network = (object) $networks[$network];
        } else {
            throw new Exception('Network not found!');
        }
        
        if (!is_object($this->network->nativeCurrency)) {
            $this->network->nativeCurrency = (object) $this->network->nativeCurrency;
        }

        $this->web3 = new Web3(new HttpProvider(new HttpRequestManager($this->network->rpcUrl, $timeOut)));
        $this->methods = $this->web3->eth;
    
        $this->time = time();
    }

    /**
     * @return object
     */
    public function getNetwork() : object
    {
        return $this->network;
    }

    /**
     * Start transfer process
     * @param string $from
     * @param string $to
     * @param float $amount
     * @param string|null $tokenAddress
     * @return Provider
     * @throws Exception
     */
    public function transfer(string $from, string $to, float $amount, ?string $tokenAddress = null) : Provider
    {
        if (is_null($tokenAddress)) {
            return $this->coinTransfer($from, $to, $amount);
        } else {
            return $this->tokenTransfer($from, $to, $amount, $tokenAddress);
        }
    }

    /**
     * Start token transfer process
     * @param string $from
     * @param string $to
     * @param float $amount
     * @param string $tokenAddress
     * @return Provider
     * @throws Exception
     */
    public function tokenTransfer(string $from, string $to, float $amount, string $tokenAddress) : Provider
    {
        $this->validate($from, $to, $amount, $tokenAddress);

        $this->pendingTransaction = (new Token($tokenAddress, [], $this))->transfer($from, $to, $amount);

        return $this;
    }

    /**
     * Start coin transfer process
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return Provider
     * @throws Exception
     */
    public function coinTransfer(string $from, string $to, float $amount) : Provider
    {
        $this->validate($from, $to, $amount);

        $this->pendingTransaction = (new Coin($this))->transfer($from, $to, $amount);

        return $this;
    }

    /**
     * @param string $privateKey
     * @param PendingTransaction|null $pendingTransaction
     * @return string
     * @throws Exception
     */
    public function sign(string $privateKey, ?PendingTransaction $pendingTransaction = null) : string
    {
        if ($pendingTransaction instanceof PendingTransaction) {
            $this->pendingTransaction = $pendingTransaction;
        }

        if ($this->pendingTransaction instanceof PendingTransaction) {
            return $this->pendingTransaction->sign($privateKey);
        } else {
            throw new \Exception("Before you can use the signing process, you must create a pending transaction.", 15000);
        }
    }

    /**
     * @param string $privateKey
     * @param PendingTransaction|null $pendingTransaction
     * @return Transaction
     * @throws Exception
     */
    public function signAndSend(string $privateKey, ?PendingTransaction $pendingTransaction = null) : Transaction
    {
        return $this->sendSignedTransaction($this->sign($privateKey, $pendingTransaction));
    }

    /**
     * Runs the signed transaction
     * @param string $signedTransaction
     * @return Transaction
     * @throws Exception
     */
    public function sendSignedTransaction(string $signedTransaction) : Transaction
    {
        try {
            $transactionId = null;
            $this->methods->sendRawTransaction('0x'. $signedTransaction, function($err, $tx) use (&$transactionId) {
                if ($err) {
                    throw new \Exception($err->getMessage(), $err->getCode());
                } else {
                    $transactionId = $tx;
                }
            });
        } catch (\Exception $e) {
            if ((time() - $this->time) >= 15) {
                throw new \Exception("Transaction time out!", 16000);
            } else {
                if ($e->getCode() == -32000 && $e->getMessage() != 'invalid sender') {
                    return $this->sendSignedTransaction($signedTransaction);
                }
            }
        }
        
        if (is_string($transactionId)) {
            return $this->createTransactionInstance($transactionId);
        } else {
            throw new \Exception("There was a problem retrieving the transaction id!", 13000);
        }
    }

    
    /**
     * @param string $transactionId
     * @return Transaction
     * @throws Exception
     */
    public function createTransactionInstance(string $transactionId) : Transaction
    {
        try {
            return new Transaction($transactionId, $this);
        } catch (\Exception $e) {
            if ((time() - $this->time) >= 15) {
                throw new \Exception("Transaction failed.", 26000);
            } else {
                if ($e->getCode() == 0 || $e->getCode() == 25000) {
                    return $this->createTransactionInstance($transactionId);
                }
            }
        }
    }

    /**
     * Gets the chain id of the blockchain network given the RPC url address
     * @return int
     * @throws Exception
     */
    public function getChainId() : int
    {
        $chainId = null;
        $this->web3->net->version(function($err, $res) use (&$chainId) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $chainId = $res;
            }
        });

        
        if (is_string($chainId)) {
            return intval($chainId);
        } else {
            throw new \Exception("There was a problem retrieving the chain id!", 14000);
        }
    }

    /**
     * get block number
     * @return int
     * @throws Exception
     */
    public function getBlockNumber() : int
    {
        $number = null;
        $this->methods->blockNumber(function($err, $res) use (&$number) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $number = $res;
            }
        });

        if (is_object($number) && $number instanceof BigNumber) {
            return intval($number->toString());
        } else {
            throw new \Exception("There was a problem retrieving the chain id!", 14000);
        }
    }

    /**
     * It receives the gas fee required for the transactions
     * @return string
     * @throws Exception
     */
    public function getGasPrice() : string
    {
        $result = null;
        $this->methods->gasPrice(function($err, $res) use (&$result) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigNumber) {
            return Utils::hex($result->toString());
        } else {
            return Utils::hex($this->defaultGasPrice);
        }
    }

    /**
     * Get transfer nonce
     * @param string $from
     * @return string
     * @throws Exception
     */
    public function getNonce(string $from) : string
    {
        $result = null;
        $this->methods->getTransactionCount($from, 'pending', function($err, $res) use (&$result) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });
        
        if ($result instanceof BigNumber) {
            return Utils::hex($result->toString());
        } else {
            return Utils::hex($this->defaultNonce);
        }
    }

    /**
     * @return object
     */
    public function getCurrency() : object
    {
        return $this->network->nativeCurrency;
    }

    /**
     * @param string $hash
     * @return Coin
     */
    public function Coin() : Coin
    {
        return new Coin($this);
    }

    /**
     * @param string $address
     * @return Token
     */
    public function Token(string $address, array $abi = []) : Token
    {
        return new Token($address, $abi, $this);
    }

    /**
     * @param string $hash
     * @return Transaction
     */
    public function Transaction(string $hash) : Transaction
    {
        return new Transaction($hash, $this);
    }
    
    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        return $this->methods->$name(...$args);
    }
}