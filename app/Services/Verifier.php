<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
// Types
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;
use BeycanPress\CryptoPayLite\Types\Enums\PaymentDataProcess as Process;

class Verifier
{
    /**
     * @return void
     */
    public function __construct()
    {
        // Cron & Transaction Page
        add_action('cryptopay_cron_job', [$this, 'verifyAllPendingTransactions']);
        Hook::addAction('transaction_page', [$this, 'verifyAllPendingTransactions'], 10, 2);

        // WooCommerce
        add_action('woocommerce_view_order', array($this, 'wcVerifyPendingTransactions'), 4);
        add_action('woocommerce_thankyou_cryptopay', array($this, 'wcVerifyPendingTransactions'), 1);
        add_action('woocommerce_before_account_orders', array($this, 'wcVerifyPendingTransactions'), 4);
    }

    /**
     * @return void
     */
    public function verifyAllPendingTransactions(): void
    {
        try {
            $models = Helpers::getModels();

            Helpers::debug('Starting verify pending transactions on Verifier', 'INFO', [
                'models' => array_keys($models)
            ]);

            foreach ($models as $model) {
                self::verifyPendingTransactions($model);
            }
        } catch (\Exception $e) {
            Helpers::debug($e->getMessage(), 'ERROR', $e);
        }
    }

    /**
     * @return void
     */
    public function wcVerifyPendingTransactions(): void
    {
        if (Helpers::getSetting('backendConfirmation') && !is_checkout()) {
            Helpers::debug('Starting verify pending transactions on Verifier for WC', 'INFO');
            self::verifyPendingTransactions(Helpers::getModelByAddon('woocommerce'), get_current_user_id());
        }
    }

    /**
     * @param TransactionType $transaction
     * @return bool|null
     */
    public static function verifyTransaction(TransactionType $transaction): ?bool
    {
        Helpers::debug('Verifying transaction...', 'INFO', $transaction->forDebug());

        $order = $transaction->getOrder();
        $amount = $order->getPaymentAmount();
        $currency = $order->getPaymentCurrency();

        $network = $transaction->getNetwork();
        $provider = Helpers::getProvider($transaction);
        $receiver = $transaction->getAddresses()->getReceiver();
        $transaction = $provider->Transaction($transaction->getHash());

        if (is_null($transaction->validate())) {
            return null;
        }

        $confirmationCount = Helpers::getBlockConfirmationCount($network->getCode());
        if (method_exists($transaction, 'getConfirmations') && $confirmationCount > 0) {
            if ($transaction->getConfirmations() < $confirmationCount) {
                return null;
            }
        }

        $tokenAddress = $currency->getAddress();
        return $transaction->verifyTransferWithData((object) [
            'amount' => $amount,
            'receiver' => $receiver,
            'tokenAddress' => $tokenAddress
        ]);
    }

    /**
     * @param AbstractTransaction $model
     * @param int $userId
     * @param string $code
     * @return void
     */
    public static function verifyPendingTransactions(
        AbstractTransaction $model,
        int $userId = 0,
        string $code = 'all'
    ): void {
        if ($userId == 0) {
            $params = [
                'status' => Status::PENDING->getValue()
            ];
        } else {
            $params = [
                'status' => Status::PENDING->getValue(),
                'userId' => $userId
            ];
        }

        if ($code != 'all') {
            $params['code'] = $code;
        }

        $transactions = $model->findBy($params)->all();

        // no pending transactions
        if (empty($transactions)) {
            return;
        }

        Helpers::debug('Verifying pending transactions...', 'INFO', [
            'params' => $params,
            'model' => $model?->tableName,
            'count' => count($transactions)
        ]);

        $uniqueTransactions = [];
        /** @var TransactionType $transaction */
        foreach ($transactions as $transaction) {
            $uniqueTransactions[$transaction->getOrderId()] = $transaction;
        }

        /** @var TransactionType $transaction */
        foreach ($transactions as $transaction) {
            try {
                if ((time() - $transaction->getCreatedAt()->getTimestamp()) < 30) {
                    continue;
                }

                $paymentData = new PaymentDataType($model->getAddon());
                $paymentData->setHash($transaction->getHash());
                $paymentData->setOrder($transaction->getOrder());
                $paymentData->setParams($transaction->getParams());
                $paymentData->setUserId($transaction->getUserId());
                $paymentData->setNetwork($transaction->getNetwork());

                // set process and status
                $paymentData->setProcess(Process::PAYMENT_FINISHED);
                $paymentData->setStatus(self::verifyTransaction($transaction));

                // if status is null (because transaction not validated yet), continue
                if (is_null($paymentData->getStatus())) {
                    continue;
                }

                Helpers::debug('Verifier payment finished filters before', 'INFO', $paymentData->forDebug());

                // data customizer
                $paymentData = Hook::callFilter('edit_payment_data', $paymentData);
                /** @var PaymentDataType */
                $paymentData = Hook::callFilter('before_payment_finished_' . $model->getAddon(), $paymentData);

                Helpers::debug('Verifier payment finished filters after', 'INFO', $paymentData->forDebug());

                // update transaction
                $paymentData->getModel()->updateWithPaymentData($paymentData, $transaction);

                Hook::callAction('payment_finished', $paymentData);
                Hook::callAction('payment_finished_' . $model->getAddon(), $paymentData);
            } catch (\Exception $e) {
                Helpers::debug($e->getMessage(), 'ERROR', $e);
                $model->updateStatusToFailedByHash($transaction->getHash());
                continue;
            }
        }
    }
}
