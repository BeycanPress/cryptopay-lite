<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
// Types
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;

class Verifier
{
    /**
     * @param TransactionType $transaction
     * @return bool|null
     */
    public static function verifyTransaction(TransactionType $transaction): ?bool
    {
        Helpers::debug('Verifying transaction...', 'INFO', $transaction->forDebug());

        $code = $transaction->getCode();
        $order = $transaction->getOrder();
        $amount = $order->getPaymentAmount();
        $currency = $order->getPaymentCurrency();

        $provider = Helpers::getProvider($transaction);
        $receiver = $transaction->getAddresses()->getReceiver();
        $transaction = $provider->Transaction($transaction->getHash());

        if (is_null($transaction->validate())) {
            return null;
        }

        $confirmationCount = Helpers::getBlockConfirmationCount($code);
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
}
