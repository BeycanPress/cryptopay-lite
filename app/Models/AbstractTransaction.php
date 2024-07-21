<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Models;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Model\AbstractModel;
// Types
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionsType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;

/**
 * General transaction table model
 */
abstract class AbstractTransaction extends AbstractModel
{
    /**
     * @var string
     * Because abstract class don't have property type, that's from php side giving error
     */
    public string $version = '1.3.1';

    /**
     * @var string
     */
    public string $addon;

    /**
     * @param string $tableName
     */
    public function __construct(string $tableName)
    {
        $this->tableName = 'cpl_' . $tableName;

        parent::__construct([
            'hash' => [
                'type' => 'string',
                'length' => 250,
                'index' => [
                    'type' => 'unique'
                ]
            ],
            'order' => [
                'type' => 'json'
            ],
            'orderId' => [
                'type' => 'integer',
                'nullable' => true,
            ],
            'userId' => [
                'type' => 'integer'
            ],
            'network' => [
                'type' => 'json'
            ],
            'params' => [
                'type' => 'json',
            ],
            'code' => [
                'type' => 'string',
                'length' => 50,
            ],
            'testnet' => [
                'type' => 'boolean',
            ],
            'reminderEmail' => [
                'type' => 'string',
                'length' => 250,
                'nullable' => true,
            ],
            'addresses' => [
                'type' => 'json',
            ],
            'status' => [
                'type' => 'string',
                'length' => 20,
            ],
            'updatedAt' => [
                'type' => 'timestamp'
            ],
            'createdAt' => [
                'type' => 'timestamp',
                'default' => 'current_timestamp',
            ],
        ]);

        $this->createTable();
    }

    /**
     * @see AbstractModel
     * @param array<mixed> $predicates
     * @param array<string> $orderBy
     * @param int $limit
     * @param int $offset
     * @return TransactionsType
     */
    public function findBy(
        array $predicates = [],
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): TransactionsType {
        $transactions = new TransactionsType();
        $rawTransactions = parent::findBy($predicates, $orderBy, $limit, $offset);
        foreach ($rawTransactions as $transaction) {
            $transactions->addTransaction(TransactionType::fromObject($transaction));
        }

        return $transactions;
    }

    /**
     * @see AbstractModel
     * @param array<mixed> $predicates
     * @param array<string> $orderBy
     * @return TransactionType|null
     */
    public function findOneBy(array $predicates = [], array $orderBy = []): ?TransactionType
    {
        if ($transaction = parent::findOneBy($predicates, $orderBy)) {
            return TransactionType::fromObject($transaction);
        }

        return null;
    }

    /**
     * @see AbstractModel
     * @param array<string> $orderBy
     * @return TransactionsType
     */
    public function findAll(array $orderBy = []): TransactionsType
    {
        return $this->findBy([], $orderBy);
    }

    /**
     * @param PaymentDataType $data
     * @param TransactionType $tx
     * @return bool
     */
    public function updateWithPaymentData(PaymentDataType $data, TransactionType $tx): bool
    {
        $tx = Hook::callFilter('transaction_update_tx', $tx, $data);
        $data = Hook::callFilter('transaction_update_data', $data, $tx);

        $params = $tx->getParams()->merge($data->getParams());
        $status = $data->getStatus() ? Status::VERIFIED : Status::FAILED;

        $provider = Helpers::getProvider($tx);
        $pTx = $provider->transaction($tx->getHash());
        $tx->getAddresses()->setSender($pTx->getSigner());

        return (bool) $this->update([
            'hash' => $data->getHash(),
            'params' => $params->toJson(),
            'userId' => $data->getUserId(),
            'status' => $status->getValue(),
            'updatedAt' => current_time('mysql'),
            'order' => $data->getOrder()->toJson(),
            'orderId' => $data->getOrder()->getId(),
            'code' => $data->getNetwork()->getCode(),
            'network' => $data->getNetwork()->toJson(),
            'addresses' => $tx->getAddresses()->toJson(),
        ], [
            'hash' => $data->getHash()
        ]);
    }

    /**
     * @param string $hash
     * @param Status $status
     * @return bool
     */
    public function updateStatusByHash(string $hash, Status $status): bool
    {
        return (bool) $this->update([
            'status' => $status->getValue(),
            'updatedAt' => current_time('mysql')
        ], [
            'hash' => $hash
        ]);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function updateStatusToVerifiedByHash(string $hash): bool
    {
        return $this->updateStatusByHash($hash, Status::VERIFIED);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function updateStatusToFailedByHash(string $hash): bool
    {
        return $this->updateStatusByHash($hash, Status::FAILED);
    }

    /**
     * @param string $text
     * @param array<mixed> $params
     * @return array<array<object>,integer>
     */
    public function search(string $text, array $params = []): array
    {
        $predicates = '';
        $pleaceholders = ['%s'];
        $parameters = ['%' . $this->db->esc_like($text) . '%'];
        if (!empty($params)) {
            $i = 1;
            foreach ($params as $key => $value) {
                $pleaceholder = '%' . $i . '$s';
                $predicates .= " AND {$key} = '$pleaceholder'";
                $pleaceholders[] = $pleaceholder;
                $parameters[] = $value;
                $i++;
            }
        }

        $rawTransactions = $this->getResults(str_ireplace(
            $pleaceholders,
            $parameters,
            "SELECT * FROM {$this->tableName} 
            WHERE (hash LIKE '%s' 
            OR orderId LIKE '%s'
            OR userId LIKE '%s' 
            OR network LIKE '%s'
            OR addresses LIKE '%s')
            " . $predicates . "
            ORDER BY id DESC"
        ));

        $transactions = new TransactionsType();
        foreach ($rawTransactions as $transaction) {
            $transactions->addTransaction(TransactionType::fromObject($transaction));
        }

        return [
            'transactions' => $transactions,
            'count' => (int) $this->getVar(str_ireplace(
                $pleaceholders,
                $parameters,
                "SELECT COUNT(id) FROM {$this->tableName} 
                WHERE (hash LIKE '%s' 
                OR orderId LIKE '%s'
                OR userId LIKE '%s' 
                OR network LIKE '%s'
                OR addresses LIKE '%s')
                " . $predicates . "
                ORDER BY id DESC"
            )) ?? 0
        ];
    }

    /**
     * @param string $hash
     * @return TransactionType|null
     */
    public function getTransactionByHash(string $hash): ?TransactionType
    {
        return $this->findOneBy([
            'hash' => $hash
        ]);
    }

    /**
     * @param int $orderId
     * @return TransactionType|null
     */
    public function getTransactionByOrderId(int $orderId): ?TransactionType
    {
        return $this->findOneBy([
            'orderId' => $orderId
        ], ['id', 'DESC']);
    }

    /**
     * @param array<mixed> $params
     * @return TransactionsType
     */
    public function getRemindedPendingTransactions(array $params): TransactionsType
    {
        return $this->findBy(array_merge($params, [
            'status' => Status::PENDING->getValue(),
            ['reminderEmail', 'IS NOT', 'NULL']
        ]));
    }

    /**
     * @return array<mixed>
     */
    public function getCodes(): array
    {
        return $this->getCol("SELECT DISTINCT(code) FROM {$this->tableName} WHERE code != ''");
    }

    /**
     * @return string
     */
    public function getAddon(): string
    {
        return $this->addon;
    }
}
