<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Data;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Types\AbstractType;
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Data\DynamicDataType;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
use BeycanPress\CryptoPayLite\Types\Transaction\ParamsType;
use BeycanPress\CryptoPayLite\Types\Enums\PaymentDataProcess as Process;

/**
 * Payment data type
 * @since 2.1.0
 */
class PaymentDataType extends AbstractType
{
    /**
     * @var string
     */
    private string $addon;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string|null
     */
    private ?string $hash = null;

    /**
     * @var bool|null
     */
    private ?bool $status = null;

    /**
     * @var Process
     */
    private Process $process = Process::INIT;

    /**
     * @var OrderType
     */
    private OrderType $order;

    /**
     * @var ParamsType
     */
    private ParamsType $params;

    /**
     * @var NetworkType
     */
    private NetworkType $network;

    /**
     * @var AbstractTransaction|null
     */
    private ?AbstractTransaction $model;

    /**
     * @var DynamicDataType|null
     */
    private ?DynamicDataType $dynamicData = null;

    /**
     * @param string $addon
     */
    public function __construct(string $addon)
    {
        $this->addon = $addon;
        $this->model = Helpers::getModelByAddon($this->addon);
    }

    /**
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param ?string $hash
     * @return self
     */
    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @param bool|null $status
     * @return self
     */
    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param Process $process
     * @return self
     */
    public function setProcess(Process $process): self
    {
        $this->process = $process;

        return $this;
    }

    /**
     * @param OrderType $order
     * @return self
     */
    public function setOrder(OrderType $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param ParamsType $params
     * @return self
     */
    public function setParams(ParamsType $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param NetworkType $network
     * @return self
     */
    public function setNetwork(NetworkType $network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @param DynamicDataType $dynamicData
     * @return self
     */
    public function setDynamicData(DynamicDataType $dynamicData): self
    {
        $this->dynamicData = $dynamicData;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddon(): string
    {
        return $this->addon;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @return Process
     */
    public function getProcess(): Process
    {
        return $this->process;
    }

    /**
     * @return OrderType
     */
    public function getOrder(): OrderType
    {
        return $this->order;
    }

    /**
     * @return ParamsType
     */
    public function getParams(): ParamsType
    {
        return $this->params;
    }

    /**
     * @return NetworkType
     */
    public function getNetwork(): NetworkType
    {
        return $this->network;
    }

    /**
     * @return AbstractTransaction|null
     */
    public function getModel(): ?AbstractTransaction
    {
        return $this->model;
    }

    /**
     * @return DynamicDataType
     */
    public function getDynamicData(): DynamicDataType
    {
        return $this->dynamicData;
    }

    /**
     * @return array<string,mixed>
     */
    public function forDebug(): array
    {
        return array_filter([
            'hash' => $this->getHash(),
            'addon' => $this->getAddon(),
            'userId' => $this->getUserId(),
            'status' => $this->getStatus(),
            'order' => $this->order->toArray(),
            'params' => $this->params->toArray(),
            'network' => $this->network->forDebug(),
            'model' => $this->getModel()?->tableName,
            'process' => $this->getProcess()->getValue(),
            'dynamicData' => $this->dynamicData?->toArray()
        ]);
    }
}
