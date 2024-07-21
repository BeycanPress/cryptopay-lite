<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains;

use Web3\Utils;
use Web3\Contracts\Ethabi as EthAbi;
use Web3\Contracts\Types\Address;
use Web3\Contracts\Types\Boolean;
use Web3\Contracts\Types\Bytes;
use Web3\Contracts\Types\DynamicBytes;
use Web3\Contracts\Types\Integer;
use Web3\Contracts\Types\Str;
use Web3\Contracts\Types\Uinteger;
use phpseclib\Math\BigInteger as BN;

class AbiDecoder
{
    /**
     * @var array<object>
     */
    private array $abi = [];

    /**
     * @var EthAbi
     */
    private EthAbi $ethAbi;

    /**
     * @var array<object>
     */
    private array $methodIds = [];

    /**
     * @param array<object> $abi
     */
    public function __construct(array $abi)
    {
        $this->abi = $abi;

        $this->ethAbi = new EthAbi([
            'address' => new Address(),
            'bool' => new Boolean(),
            'bytes' => new Bytes(),
            'dynamicBytes' => new DynamicBytes(),
            'int' => new Integer(),
            'string' => new Str(),
            'uint' => new Uinteger(),
        ]);

        $this->parseMethodIds();
    }

    /**
     * @param object $input
     * @return string
     */
    private function typeToString(object $input): string
    {
        if ("tuple" === $input->type) {
            return "(" . implode(',', array_map([$this, 'typeToString'], $input->components)) . ")";
        }

        return $input->type;
    }

    /**
     * @param array<string> $inputs
     * @return string
     */
    private function parseInputs(array $inputs): string
    {
        return "(" . implode(',', array_map([$this, 'typeToString'], $inputs)) . ")";
    }

    /**
     * @return void
     */
    private function parseMethodIds(): void
    {
        foreach ($this->abi as $obj) {
            if (isset($obj->name)) {
                $signature = Utils::sha3($obj->name . $this->parseInputs($obj->inputs));

                if ("event" === $obj->type) {
                    $this->methodIds[substr($signature, 2)] = $obj;
                } else {
                    $this->methodIds[substr($signature, 2, 8)] = $obj;
                }
            }
        }
    }

    /**
     * @param string $input
     * @return object
     */
    public function decodeInput(string $input): object
    {
        $abiItem = $this->methodIds[substr($input, 2, 8)];
        $types = array_column($abiItem->inputs, 'type');

        if ($abiItem) {
            /** @var array<array<mixed>|string> $decoded */
            $decoded = $this->ethAbi->decodeParameters($types, substr($input, 10));

            $retData = (object) [
                "name" => $abiItem->name,
                "args" => [],
            ];

            for ($i = 0; $i < count($decoded); $i++) {
                $param = $decoded[$i];
                $parsedParam = $param;
                $isArray = is_array($param);
                $isInt = false !== strpos($abiItem->inputs[$i]->type, "int");
                $isUint = false !== strpos($abiItem->inputs[$i]->type, "uint");
                $isAddress = false !== strpos($abiItem->inputs[$i]->type, "address");

                if ($isUint || $isInt) {
                    if ($isArray) {
                        $parsedParam = array_map(function ($val) {
                            return '0x' . (new BN($val))->toHex();
                        }, $param);
                    } else {
                        $parsedParam = '0x' . (new BN($param))->toHex();
                    }
                }

                if ($isAddress) {
                    if ($isArray) {
                        $parsedParam = array_map('strtolower', $param);
                    } else {
                        $parsedParam = strtolower($param);
                    }
                }

                $retData->args[] = (object) [
                    "name" => $abiItem->inputs[$i]->name,
                    "value" => $parsedParam,
                    "type" => $abiItem->inputs[$i]->type,
                ];
            }
        }

        return $retData;
    }

    /**
     * @param array<mixed> $logs
     * @return array<mixed>
     */
    public function decodeLogs(array $logs): array
    {
        $logs = array_filter($logs, function ($log) {
            return count($log->topics) > 0;
        });

        return array_map(function ($log) {

            $method = $this->methodIds[substr($log->topics[0], 2)];

            if ($method) {
                $logData = $log->data;
                $decodedParams = [];
                $dataIndex = 0;
                $topicsIndex = 1;
                $dataTypes = [];

                array_map(function ($input) use (&$dataTypes): void {
                    if (!$input->indexed) {
                        $dataTypes[] = $input->type;
                    }
                }, $method->inputs);

                $decoded = $this->ethAbi->decodeParameters($dataTypes, substr($logData, 2));

                array_map(function ($input) use ($log, &$decodedParams, &$topicsIndex, &$dataIndex, $decoded): void {

                    $decodedP = (object) [
                        "name" => $input->name,
                        "type" => $input->type,
                    ];

                    if ($input->indexed) {
                        $decodedP->value = $log->topics[$topicsIndex];
                        $topicsIndex++;
                    } else {
                        $decodedP->value = $decoded[$dataIndex];
                        $dataIndex++;
                    }

                    if ("address" === $input->type) {
                        $decodedP->value = strtolower($decodedP->value);
                        if (strlen($decodedP->value) > 42) {
                            $toRemove = strlen($decodedP->value) - 42;
                            $decodedP->value = "0x" . substr($decodedP->value, -40);
                        }
                    }

                    if (
                        "uint256" === $input->type ||
                        "uint8" === $input->type ||
                        "int" === $input->type
                    ) {
                        if (is_string($decodedP->value) && str_starts_with($decodedP->value, '0x')) {
                            $decodedP->value = (new BN(substr($decodedP->value, 2), 16))->toString(10);
                        } else {
                            $decodedP->value = (new BN($decodedP->value))->toString(10);
                        }
                    }

                    $decodedParams[] = $decodedP;
                }, $method->inputs);


                return (object) [
                    "name" => $method->name,
                    "events" => $decodedParams,
                    "address" => $log->address,
                ];
            }
        }, $logs);
    }
}
