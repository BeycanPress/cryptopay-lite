<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\Http\Request;
use \BeycanPress\Http\Response;
use \BeycanPress\CryptoPayLite\Services;
use \BeycanPress\CryptoPayLite\Verifier;
use \BeycanPress\CryptoPayLite\PluginHero\Hook;
use \BeycanPress\CryptoPayLite\PluginHero\Api as AbstractApi;

class Api extends AbstractApi
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $addon;

    /**
     * @var AbstractTransaction
     */
    private $model;

    /**
     * @var Verifier
     */
    private $verifier;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var object
     */

    private $order;

    /**
     * @var object
     */
    private $network;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $fiatCurrency;

    /**
     * @var object
     */
    private $cryptoCurrency;

    /**
     * @var object
     */
    private $data;

    /**
     * @var array
     */
    private $errorMap = [
        'GNR101' => 'Please enter a valid data!',
        'INIT101' => 'There was a problem converting currency!',
        'INIT102' => 'There was a problem getting wallet address!',
        'CT102' => 'Transaction already exists!',
        'PAYF101' => 'Transaction record not found!',
        'PAYF102' => 'Payment not verified via Blockchain',
        'MOD103' => 'Model not found!',
        'ORDER_NOT_FOUND' => 'The relevant order was not found!',
    ];

    public function __construct()
    {
        $this->request = new Request();
        $this->userId = get_current_user_id();
        $this->addon = $this->request->getParam('cp_lite_addon');
        if ($this->addon) {
            $this->model = Services::getModelByAddon($this->addon);
            $this->hash = $this->request->getParam('hash');
            $this->order = $this->request->getParam('order');
            $this->network = $this->request->getParam('network');
            $this->amount = $this->request->getParam('amount');
            $this->fiatCurrency = $this->request->getParam('fiatCurrency');
            $this->cryptoCurrency = $this->request->getParam('cryptoCurrency');
            $this->data = (object) [
                'userId' => $this->userId,
                'order' => $this->order,
                'hash' => $this->hash,
                'network' => $this->network,
                'model' => $this->model,
                'status' => 'pending',
                'params' => $this->request->getParam('params')
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
                ],
                'verify-pending-transactions' => [
                    'callback' => 'verifyPendingTransactions',
                    'methods' => ['GET']
                ]
            ]
        ]);
    }

    /**
     * @return void
     */
    public function init() : void
    {   
        Hook::callAction('init_' . $this->addon, $this->data);
        Hook::callAction('check_order_' . $this->addon, $this->order);

        $paymentPrice = Services::calculatePaymentPrice(
            $this->fiatCurrency, $this->cryptoCurrency, $this->amount, $this->network
        );

        if (is_null($paymentPrice)) {
            Response::error(esc_html__('There was a problem converting currency!', 'cryptopay_lite'), 'INIT101');
        }

        if (!$receiver = Settings::get('evmBasedWalletAddress')) {
            Response::error(esc_html__('There was a problem getting wallet address!', 'cryptopay_lite'), 'INIT102');
        }

        $receiver = Hook::callFilter('receiver_' . $this->addon, $receiver, $this->data);

        Response::success(null, [
            'receiver' => $receiver,
            'paymentPrice' => $paymentPrice,
            'blockConfirmationCount' => 10,
        ]);
    }

    /**
     * @return void
     */
    public function createTransaction() : void
    {
        if ($this->model) {
            Hook::callAction('check_order_' . $this->addon, $this->order);
            Hook::callAction('before_payment_started_' . $this->addon, $this->data);

            if (!$this->hash) {
                Response::badRequest(esc_html__('Please enter a valid data.', 'cryptopay_lite'), 'CT101');
            }
            
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

            Response::success();
        }

        Response::error(esc_html__('Model not found!', 'cryptopay_lite'), 'MOD103');
    }

    /**
     * @return void
     */
    public function paymentFinished() : void
    {   
        if ($this->model) {
            Hook::callAction('check_order_' . $this->addon, $this->order);
            Hook::callAction('before_payment_finished_' . $this->addon, $this->data);
            
            if (!$this->hash) {
                Response::badRequest(esc_html__('Please enter a valid data.', 'cryptopay_lite'), 'GNR101');
            }

            if (!$transaction = $this->model->findOneBy(['hash' => $this->hash])) {
                Response::error(esc_html__('Transaction record not found!', 'cryptopay_lite'), 'PAYF101');
            }

            try {
                $this->data->status = (new Verifier($this->model))->verifyTransaction($transaction);
            } catch (\Exception $e) {
                $this->data->status = false;
            }

            Hook::callAction('payment_finished_' . $this->addon, $this->data);

            $urls = Hook::callFilter('payment_redirect_urls_' . $this->addon, $this->data);

            if (!$urls['success'] || !$urls['failed']) {
                Response::badRequest(esc_html__('Redirect links cannot finded!', 'cryptopay_lite'), 'GNR102');
            }

            if ($this->data->status) {
                Response::success(Hook::callFilter(
                    'payment_success_message_' . $this->addon, 
                    esc_html__('Payment completed successfully', 'cryptopay_lite')
                ), [
                    'redirect' => $urls['success']
                ]);
            } else {
                Response::error(Hook::callFilter(
                    'payment_failed_message_' . $this->addon, 
                    esc_html__('Payment not verified via Blockchain', 'cryptopay_lite')
                ), 'PAYF102', [
                    'redirect' => $urls['failed']
                ]);
            }
        }

        Response::error(esc_html__('Model not found!', 'cryptopay_lite'), 'MOD103');
    }

    /**
     * @return void
     */
    public function currencyConverter() : void
    {   
        $paymentPrice = Services::calculatePaymentPrice(
            $this->fiatCurrency, $this->cryptoCurrency, $this->amount, $this->network
        );

        if (is_null($paymentPrice)) {
            Response::error(esc_html__('There was a problem converting currency!', 'cryptopay_lite'), 'GNR101');
        }

        Response::success(null, $paymentPrice);
    }

    /**
     * @return void
     */
    public function verifyPendingTransactions() : void
    {
        if ($this->model) {
            $code = $this->request->getParam('code') ?? 'all';
            (new Verifier($this->model))->verifyPendingTransactions(0, $code);

            Response::success();
        }

        Response::error(esc_html__('Model not found!', 'cryptopay_lite'), 'MOD103');
    }
}
