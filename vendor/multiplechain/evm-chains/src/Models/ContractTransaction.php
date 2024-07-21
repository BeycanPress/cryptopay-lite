<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Models;

use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\AbiDecoder;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Models\ContractTransactionInterface;

class ContractTransaction extends Transaction implements ContractTransactionInterface
{
    /**
     * @var array<object>
     */
    public array $abi;

    /**
     * @param string $id
     * @param Provider|null $provider
     * @param array<object>|null $abi
     */
    public function __construct(string $id, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        $this->abi = $abi ?? [];
        parent::__construct($id, $provider);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        $data = $this->getData();
        return $data?->response?->to ?? '';
    }

    /**
     * @param object|null $response
     * @return object|null
     */
    public function decodeData(?object $response = null): ?object
    {
        if (is_null($response)) {
            /**
             * @var null|object{'response': object} $data
             */
            $data = $this->getData();
            if (is_null($data)) {
                return null;
            }
            $response = $data->response;
        }

        $decoder = new AbiDecoder($this->abi);
        /**
         * @var object{'input': string} $response
         */
        return $decoder->decodeInput($response->input);
    }
}
