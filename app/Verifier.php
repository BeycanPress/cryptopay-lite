<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

use BeycanPress\CryptoPayLite\PluginHero\Helpers;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;

class Verifier
{
    use Helpers;

    protected AbstractTransaction $model;

    /**
     * @param AbstractTransaction $model
     */
    public function __construct(AbstractTransaction $model)
    {
        $this->model = $model;
    }

    /**
     * @param object $transaction
     * @return bool|null
     */
    public function verifyTransaction(object $transaction): ?bool
    {
        $order = json_decode($transaction->order);
        $amount = $order->paymentAmount;
        $currency = $order->paymentCurrency;

        $confirmationCount = 10;
        $receiver = Settings::get('evmBasedWalletAddress');
        $provider = Services::getProviderByTx($transaction);
        $transaction = $provider->Transaction($transaction->hash);

        if (is_null($transaction->validate())) {
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
}
