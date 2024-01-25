<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

use BeycanPress\Http\Request;
use BeycanPress\Http\Response;
use BeycanPress\CryptoPayLite\Services;
use BeycanPress\CryptoPayLite\Verifier;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
use BeycanPress\CryptoPayLite\PluginHero\Api as AbstractApi;

class Api extends AbstractApi
{
    /**
     * @var int
     */
    private int $userId;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var string|null
     */
    private ?string $addon;

    /**
     * @var AbstractTransaction
     */
    private AbstractTransaction $model;

    /**
     * @var string|null
     */
    private ?string $hash;

    /**
     * @var object
     */

    private object $order;

    /**
     * @var object
     */
    private object $network;

    /**
     * @var object
     */
    private object $data;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->userId = get_current_user_id();
        $this->addon = $this->request->getParam('cp_lite_addon');
        if ($this->addon) {
            $dynamicData = $this->request->getParam('dynamicData');
            if (!$dynamicData) {
                $dynamicData = (object) [];
            }
            $this->model = Services::getModelByAddon($this->addon);
            $this->hash = $this->request->getParam('hash');
            $this->order = $this->request->getParam('order');
            $this->network = $this->request->getParam('network');
            $this->data = (object) [
                'userId' => $this->userId,
                'order' => $this->order,
                'hash' => $this->hash,
                'network' => $this->network,
                'model' => $this->model,
                'status' => 'pending',
                'params' => $this->request->getParam('params'),
                'dynamicData' => $dynamicData,
            ];
        }

        $this->addRoutes([
            'cryptopay-lite' => [
                'init' => [
                    'callback' => 'init',
                    'methods' => ['GET']
                ],
                'create-transaction' => [
                    'callback' => 'createTransaction',
                    'methods' => ['POST']
                ],
                'payment-finished' => [
                    'callback' => 'paymentFinished',
                    'methods' => ['POST']
                ],
                'currency-converter' => [
                    'callback' => 'currencyConverter',
                    'methods' => ['GET']
                ],
                'old-transactions' => [
                    'callback' => 'oldTransactions',
                    'methods' => ['GET']
                ]
            ]
        ]);
    }

    /**
     * @return void
     */
    public function init(): void
    {
        // data customizer
        $this->data = Hook::callFilter('init_' . $this->addon, $this->data);
        // Check order or update
        $this->data->order = $this->order = Hook::callFilter('check_order_' . $this->addon, $this->order);

        $paymentAmount = Services::calculatePaymentAmount(
            $this->order->currency,
            $this->order->paymentCurrency,
            floatval($this->order->amount)
        );

        if (is_null($paymentAmount)) {
            Response::error(esc_html__('There was a problem converting currency!', 'cryptopay_lite'), 'INIT101');
        }

        if (!$receiver = Settings::get('evmBasedWalletAddress')) {
            Response::error(esc_html__('There was a problem getting wallet address!', 'cryptopay_lite'), 'INIT102');
        }

        $receiver = Hook::callFilter('receiver', $receiver, $this->data);
        $receiver = Hook::callFilter('receiver_' . $this->addon, $receiver, $this->data);
        $receiver = Hook::callFilter('receiver_' . $this->data->network->code, $receiver, $this->data);

        Response::success(null, [
            'receiver' => $receiver,
            'paymentAmount' => $paymentAmount,
            'blockConfirmationCount' => 10,
        ]);
    }

    /**
     * @return void
     */
    public function createTransaction(): void
    {
        if ($this->model) {
            if (!$this->hash) {
                Response::badRequest(esc_html__('Please enter a valid data.', 'cryptopay_lite'), 'CT101', [
                    'redirect' => 'reload'
                ]);
            }

            // Check order or update
            $this->data->order = $this->order = Hook::callFilter('check_order_' . $this->addon, $this->order);
            // data customizer
            $this->data = Hook::callFilter('before_payment_started_' . $this->addon, $this->data);

            $date = date('Y-m-d H:i:s', $this->getUTCTime()->getTimestamp());
            if ($this->model->findOneBy(['hash' => $this->hash])) {
                Response::error(esc_html__('Transaction already exists!', 'cryptopay_lite'), 'CT102');
            }

            $this->model->insert([
                'hash' => $this->hash,
                'order' => json_encode($this->order),
                'orderId' => $this->order->id ?? null,
                'userId' => $this->userId,
                'network' => json_encode($this->network),
                'code' => $this->network->code,
                'testnet' => boolval(Settings::get('testnet')),
                'status' => Hook::callFilter('transaction_status_' . $this->addon, 'pending'),
                'updatedAt' => $date,
                'createdAt' => $date,
            ]);

            Hook::callAction('payment_started_' . $this->addon, $this->data);

            Response::success(esc_html__('Success'), isset($this->data->dynamicData) ? $this->data->dynamicData : null);
        }

        Response::error(esc_html__('Model not found!', 'cryptopay_lite'), 'MOD103', [
            'redirect' => 'reload'
        ]);
    }

    /**
     * @return void
     */
    public function paymentFinished(): void
    {
        if ($this->model) {
            if (!$this->hash) {
                Response::badRequest(esc_html__('Please enter a valid data.', 'cryptopay_lite'), 'GNR101');
            }

            // Check order or update
            $this->data->order = $this->order = Hook::callFilter('check_order_' . $this->addon, $this->order);
            // data customizer
            $this->data = Hook::callFilter('before_payment_finished_' . $this->addon, $this->data);

            if (!$transaction = $this->model->findOneBy(['hash' => $this->hash])) {
                Response::error(esc_html__('Transaction record not found!', 'cryptopay_lite'), 'PAYF101');
            }

            $failedMessage = esc_html__('Payment not verified via Blockchain', 'cryptopay_lite');

            try {
                $this->data->status = (new Verifier($this->model))->verifyTransaction($transaction);
            } catch (\Exception $e) {
                $failedMessage = sprintf(
                    esc_html__('Payment not verified via Blockchain - Because reason: %s', 'cryptopay_lite'),
                    $e->getMessage()
                );
                $this->data->status = false;
            }

            Hook::callAction('payment_finished_' . $this->addon, $this->data);

            $urls = Hook::callFilter('payment_redirect_urls_' . $this->addon, $this->data);

            if (is_object($urls)) {
                Response::badRequest(esc_html__('Redirect links cannot finded!', 'cryptopay_lite'), 'GNR102');
            }

            if ($this->data->status) {
                $this->model->updateStatusToVerifiedByHash($transaction->hash);

                Response::success(Hook::callFilter(
                    'payment_success_message_' . $this->addon,
                    esc_html__('Payment completed successfully', 'cryptopay_lite')
                ), [
                    'redirect' => $urls['success']
                ]);
            } else {
                $this->model->updateStatusToFailedByHash($transaction->hash);

                Response::error(Hook::callFilter(
                    'payment_failed_message_' . $this->addon,
                    $failedMessage
                ), 'PAYF102', [
                    'redirect' => $urls['failed']
                ]);
            }
        }

        Response::error(esc_html__('Model not found!', 'cryptopay_lite'), 'MOD103', [
            'redirect' => 'reload'
        ]);
    }

    /**
     * @return void
     */
    public function currencyConverter(): void
    {
        $paymentAmount = Services::calculatePaymentAmount(
            $this->order->currency,
            $this->order->paymentCurrency,
            floatval($this->order->amount),
            $this->network
        );

        if (is_null($paymentAmount)) {
            Response::error(esc_html__('There was a problem converting currency!', 'cryptopay_lite'), 'GNR101');
        }

        Response::success(null, $paymentAmount);
    }
}
