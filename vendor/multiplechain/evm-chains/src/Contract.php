<?php

namespace MultipleChain\EvmChains;

use Web3\Contract as Web3Contract;
use Web3\Validators\AddressValidator;
use phpseclib\Math\BigInteger as BigNumber;

final class Contract
{
    /**
     * Provider
     * @var Provider
     */
    private $provider;

    /**
     * Current token contract address
     * @var string
     */
    private $address;

    /**
     * web3 contract
     * @var Web3Contract
     */
    public $contract;

    /**
     * Default gas
     * @var int
     */
    private $defaultGas = 50000;

    /**
     * @param string $address
     * @param array $abi
     * @param Provider|null $provider
     */
    public function __construct(string $address, array $abi, Provider $provider)
    {
        if (AddressValidator::validate($address) === false) {
            throw new \Exception('Invalid contract address!', 23000);
        }

        $this->address = $address;
        $this->provider = $provider;
        $this->contract = (new Web3Contract($this->provider->web3->provider, json_encode($abi)))->at($address);
    }

    /**
     * @param string $method
     * @param array $params
     * @return string|null
     * @throws Exception
     */
    public function getEstimateGas(string $method, ...$params) : ?string
    {
        $gas = null;
        call_user_func_array([$this->contract, 'estimateGas'], [$method, ...$params, function($err, $res) use (&$gas) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $gas = $res;
            }
        }]);

        if ($gas instanceof BigNumber) {
            return Utils::hex($gas->toString());
        } else {
            return Utils::hex($this->defaultGas);
        }

        return $gas;
    }

    /**
     * @param string $method
     * @param array $params
     * @return string|null
     * @throws Exception
     */
    public function getData(string $method, ...$params) : ?string
    {
        return '0x' . $this->contract->getData($method, ...$params);
    }

    /**
     * Returns the current token contract address
     * @return string
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call(string $method, array $params)
    {
        $result = null;
        call_user_func_array([$this->contract, 'call'], [$method, ...$params, function($err, $res) use (&$result) {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        }]);

        return $result;
    }
}