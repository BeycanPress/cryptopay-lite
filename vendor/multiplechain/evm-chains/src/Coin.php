<?php

namespace MultipleChain\EvmChains;

use Web3p\EthereumTx\Transaction;
use phpseclib\Math\BigInteger as BigNumber;

final class Coin
{
    /**
     * Provider
     * @var Provider
     */
    private $provider;

    /**
     * @param Provider|null $provider
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
    * Generates a coin transfer data
    *
    * @param string $from
    * @param string $to
    * @param float $amount
    * @return array
    * @throws Exception
    */
    public function transferData(string $from, string $to, float $amount) : array
    {
        if ($this->getBalance($from) < $amount) {
            throw new \Exception('Insufficient balance!', 10000);
        }

        return [
            'to' => $to,
            'data' => '',
            'from' => $from,
            'gas' => Utils::hex(21000),
            'chainId' => $this->provider->getChainId(),
            'nonce' => $this->provider->getNonce($from),
            'gasPrice' => $this->provider->getGasPrice(),
            'value' => Utils::toHex($amount, $this->getDecimals()),
        ];
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return Transaction
     */
    public function transfer(string $from, string $to, float $amount) : Transaction
    {
        Utils::validate($from, $to, $amount);

        return new Transaction($this->transferData($from, $to, $amount));
    }

    /**
     * Returns the coin decimals
     * @return int
     */
    public function getDecimals() : int
    {
        return $this->provider->getCurrency()->decimals;
    }

    /**
     * Returns the coin symbol
     * @return string
     */
    public function getSymbol() : string
    {
        return $this->provider->getCurrency()->symbol;
    }

    /**
     * Returns the balance of the current token in the address given wallet address
     * @param string $address
     * @return float
     * @throws Exception
     */
    public function getBalance(string $address) : float
    {
        $result = null;
        $this->provider->methods->getBalance($address, function($err, $res) use (&$result) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigNumber) {
            return Utils::toDec($result->toString(), $this->getDecimals());
        } else {
            throw new \Exception("There was a problem retrieving the balance!", 11000);
        }
    }
}