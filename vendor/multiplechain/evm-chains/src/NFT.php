<?php

namespace MultipleChain\EvmChains;

use Web3\Validators\AddressValidator;
use phpseclib\Math\BigInteger as BigNumber;

final class NFT
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
     * Default gas
     * @var int
     */
    private $defaultGas = 50000;

    /**
     * Current token contract
     * @var Web3Contract
     */
    public $contract;

    /**
     * @param string $contractAddress
     * @param array $abi
     * @param Provider|null $provider
     */
    public function __construct(string $contractAddress, Provider $provider, array $abi = [])
    {
        if (AddressValidator::validate($contractAddress) === false) {
            throw new \Exception('Invalid NFT address!', 23000);
        }
        
        $this->provider = $provider;
        $this->address = $contractAddress;
        $abi = empty($abi) ? file_get_contents(dirname(__DIR__) . '/resources/erc721.json') : $abi;
        $this->contract = (new Contract($contractAddress, json_decode($abi), $this->provider));

        try {
            $this->contract->balanceOf($this->address);
        } catch (\Throwable $th) {
            $abi = file_get_contents(dirname(__DIR__) . '/resources/erc1155.json');
            $this->contract = (new Contract($contractAddress, json_decode($abi), $this->provider));
        }
    }

    /**
     * Returns the nft's decimals
     * @return int
     * @throws Exception
     */
    public function getDecimals() : int
    {
        return 0;
    }

    /**
     * Returns the balance of the current nft in the address given wallet address
     *
     * @param string $address
     * @param string|null $id
     * @return float
     * @throws Exception
     */
    public function getBalance(string $address, ?string $id = null) : float
    {
        if ($id) {
            $result = $this->contract->balanceOf($address,  $id);
        } else {
            $result = $this->contract->balanceOf($address);
        }

        if (is_array($result) && $result[0] instanceof BigNumber) {
            return Utils::toDec($result[0]->toString(), $this->getDecimals());
        } else {
            throw new \Exception("There was a problem retrieving the balance!", 11000);
        }
    }

    /**
     * get collection name
     *
     * @return string|null
     * @throws Exception
     */
    public function getName() : ?string
    {
        return isset($this->contract->name()[0]) ? $this->contract->name()[0] : null;
    }

    /**
     * get collection symbol
     *
     * @return string|null
     * @throws Exception
     */
    public function getSymbol() : ?string
    {
        return isset($this->contract->symbol()[0]) ? $this->contract->symbol()[0] : null;
    }

    /**
     * get collection total supply
     *
     * @return string
     * @throws Exception
     */
    public function getTotalSupply() : string
    {
        $totalSupply = $this->contract->totalSupply();
        
        if (is_array($totalSupply) && end($totalSupply) instanceof BigNumber) {
            $totalSupply = Utils::toDec(end($totalSupply)->toString(), 
            $this->getDecimals());
            return number_format($totalSupply, $this->getDecimals(), ',', '.');
        } else {
            throw new \Exception("There was a problem retrieving the total suppy!", 14001);
        }
    }

    /**
     * Returns the current nft collection contract address
     * @return string
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        return $this->contract->$name(...$args);
    }
}