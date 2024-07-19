<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use phpseclib\Math\BigInteger;
use Web3\Contract as Web3Contract;
use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\TransactionData;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\ContractInterface;

class Contract implements ContractInterface
{
    /**
     * @var string
     */
    private string $address;

    /**
     * @var array<string,mixed>
     */
    private array $cachedMethods = [];

    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var Web3Contract
     */
    public Web3Contract $web3Contract;

    /**
     * @var array<object>
     */
    public array $abi;

    /**
     * @param string $address
     * @param Provider|null $provider
     * @param array<object>|null $abi
     */
    public function __construct(string $address, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        $this->abi = $abi ?? [];
        $this->address = $address;
        $this->provider = $provider ?? Provider::instance();
        $this->web3Contract = (new Web3Contract($this->provider->web3->getProvider(), $this->abi))->at($address);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    public function callMethod(string $method, mixed ...$args): mixed
    {
        $result = null;
        $args[] = function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        };

        $this->web3Contract->call($method, ...$args);

        $result = is_array($result) ? array_values($result)[0] : $result;

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        }

        return $result;
    }

    /**
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    public function callMethodWithCache(string $method, mixed ...$args): mixed
    {
        if (isset($this->cachedMethods[$method])) {
            return $this->cachedMethods[$method];
        }

        return $this->cachedMethods[$method] = $this->callMethod($method, ...$args);
    }

    /**
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    public function getMethodData(string $method, mixed ...$args): mixed
    {
        // @phpstan-ignore-next-line
        return '0x' . $this->web3Contract->getData($method, ...$args);
    }

    /**
     * @param string $method
     * @param string $from
     * @param mixed ...$args
     * @return string
     */
    public function getMethodEstimateGas(string $method, string $from, mixed ...$args): string
    {
        $result = null;
        $args[] = ['from' => $from];
        $args[] = function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        };

        $this->web3Contract->estimateGas($method, ...$args);

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        } else {
            return '0x' . dechex(50000);
        }
    }

    /**
     * @param string $method
     * @param string $from
     * @param mixed ...$args
     * @return TransactionData
     */
    public function createTransactionData(string $method, string $from, mixed ...$args): TransactionData
    {
        $data = $this->getMethodData($method, ...$args);
        $gasPrice = $this->provider->web3->getGasPrice();
        $nonce = $this->provider->web3->getNonce($from);
        $gas = $this->getMethodEstimateGas($method, $from, ...$args);

        return new TransactionData([
            'value' => '0x0',
            'gas' => $gas,
            'from' => $from,
            'data' => $data,
            'nonce' => $nonce,
            'gasPrice' => $gasPrice,
            'to' => $this->getAddress(),
            'chainId' => $this->provider->network->getId()
        ]);
    }
}
