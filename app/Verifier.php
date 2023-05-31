<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\CryptoPayLite\PluginHero\Hook;
use \BeycanPress\CryptoPayLite\PluginHero\Helpers;
use \BeycanPress\CryptoPayLite\Interfaces\TransactionInterface;

class Verifier
{
    use Helpers;

    protected $model;

    /**
     * @param object $model
     */
    public function __construct(object $model)
    {
        $this->model = $model;
    }

    /**
     * @param object $transaction
     * @return bool|null
     */
    public function verifyTransaction(object $transaction) : ?bool
    {
        $order = json_decode($transaction->order);
        $amount = $order->paymentPrice;
        $currency = $order->paymentCurrency;

        $confirmationCount = 10;
        $receiver = Settings::get('evmBasedWalletAddress');
        $provider = Services::getProviderByTx($transaction);
        $transaction = $provider->Transaction($transaction->hash);

        if (is_null($transaction->getStatus())) {
            return null;
        }

        if (method_exists($transaction, 'getConfirmations') && $confirmationCount > 0) {
            if ($transaction->getConfirmations() < $confirmationCount) {
                return null;
            }
        }
        
        $tokenAddress = isset($currency->address) ? $currency->address : null;
        return $transaction->verifyTransferWithData((object) [
            'amount' => $amount,
            'receiver' => $receiver,
            'tokenAddress' => $tokenAddress
        ]);
    }
    
    /**
     * @param int $userId
     * @param string $code
     * @return void
     */
    public function verifyPendingTransactions($userId = 0, string $code = 'all') : void
    {
        if ($userId == 0) {
            $params = [
                'status' => 'pending'
            ];
        } else {
            $params = [
                'status' => 'pending',
                'userId' => $userId
            ];
        }

        if ($code != 'all') {
            $params['code'] = $code;
        }

        $transactions = $this->model->findBy($params);
        if (empty($transactions)) return;

        $uniqueTransactions = [];
        foreach($transactions as $transaction) {
            $order = json_decode($transaction->order);
            if (isset($order->id)) {
                $uniqueTransactions[$order->id] = $transaction;
            }
        }

        $transactions = array_values($uniqueTransactions);

        foreach ($transactions as $transaction) {
            
            $order = json_decode($transaction->order);
            $network = json_decode($transaction->network);

            try {

                if ((time() - strtotime($transaction->createdAt)) < 30) {
                    continue;
                }
                $result = $this->verifyTransaction($transaction);

                if (is_null($result)) continue;

                if ($result) {
                    $this->model->update([
                        'status' => 'verified',
                        'updatedAt' => date('Y-m-d H:i:s', $this->getUTCTime()->getTimestamp())
                    ], ['hash' => $transaction->hash]);
                    
                } else {
                    $this->model->update([
                        'status' => 'failed',
                        'updatedAt' => date('Y-m-d H:i:s', $this->getUTCTime()->getTimestamp())
                    ], ['hash' => $transaction->hash]);
                }

                Hook::callAction(
                    'payment_finished_' . $this->model->addon, (object) [
                        'userId' => $this->userId,
                        'order' => $order,
                        'network' => $network,
                        'hash' => $transaction->hash,
                        'model' => $this->model,
                        'status' => $result,
                    ]
                );

            } catch (\Exception $e) {
                $this->model->update([
                    'status' => 'failed',
                    'updatedAt' => date('Y-m-d H:i:s', $this->getUTCTime()->getTimestamp())
                ], ['hash' => $transaction->hash]);
                continue;
            }
        }
    }
}
