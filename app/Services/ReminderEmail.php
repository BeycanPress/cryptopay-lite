<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
// Types
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;

class ReminderEmail
{
    /**
     * @return void
     */
    public function __construct()
    {
        Hook::addAction('payment_finished', [$this, 'sendReminderEmail'], 10, 2);
    }

    /**
     * @param PaymentDataType $paymentData
     * @return void
     */
    public function sendReminderEmail(PaymentDataType $paymentData): void
    {
        try {
            $model = Helpers::getModelByAddon($paymentData->getAddon());
            $transaction = $model->getTransactionByHash($paymentData->getHash());

            if (!$transaction->getReminderEmail()) {
                return;
            }

            Helpers::debug('Sending  reminder email...', 'INFO', $paymentData->forDebug());

            $transactionHtmlDetails = Helpers::getPaymentHtmlDetails($transaction);

            $urls = Hook::callFilter('payment_redirect_urls_' . $paymentData->getAddon(), $paymentData);

            $mailContent = Helpers::view('reminder-email', compact('transactionHtmlDetails', 'paymentData', 'urls'));
            $mailContent = Hook::callFilter(
                'reminder_email_content_' . $paymentData->getAddon(),
                $mailContent,
                $paymentData
            );

            $res = wp_mail(
                $transaction->getReminderEmail(),
                esc_html__('CryptoPay Payment Reminder', 'cryptopay'),
                $mailContent,
                [
                    'Content-Type: text/html; charset=UTF-8'
                ]
            );

            Helpers::debug($res ? 'Reminder email sent' : 'Reminder email could not be sent');
        } catch (\Throwable $th) {
            Helpers::debug($th->getMessage(), 'ERROR', $th);
        }
    }
}
