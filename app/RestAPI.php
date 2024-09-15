<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

// Classes
use BeycanPress\CryptoPayLite\Payment;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Services\Verifier;
use BeycanPress\CryptoPayLite\Services\Converter;
use BeycanPress\CryptoPayLite\PluginHero\BaseAPI;
use BeycanPress\CryptoPayLite\PluginHero\Http\Request;
use BeycanPress\CryptoPayLite\PluginHero\Http\Response;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Data\DynamicDataType;
use BeycanPress\CryptoPayLite\Types\Transaction\ParamsType;
use BeycanPress\CryptoPayLite\Types\Transaction\AddressesType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;
use BeycanPress\CryptoPayLite\Types\Enums\PaymentDataProcess as Process;
// Exceptions
use BeycanPress\CryptoPayLite\Exceptions\InitializeException;

class RestAPI extends BaseAPI
{
    /**
     * @var string
     */
    private string $addon;

    /**
     * @var int
     */
    private int $currentUserId;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var PaymentDataType
     */
    private PaymentDataType $paymentData;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->currentUserId = Helpers::getCurrentUserId();

        // set current user id for woocommerce process
        if ($this->currentUserId) {
            add_filter('woocommerce_checkout_customer_id', function () {
                wp_set_current_user($this->currentUserId);
                return $this->currentUserId;
            }, 11);
        }

        // create payment data
        add_action('rest_pre_dispatch', [$this, 'middleware'], 10, 3);

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
                'set-reminder-email' => [
                    'callback' => 'setReminderEmail',
                    'methods' => ['POST']
                ],
                'currency-converter' => [
                    'callback' => 'currencyConverter',
                    'methods' => ['GET']
                ],
                'verify-pending-transactions' => [
                    'callback' => 'verifyPendingTransactions',
                    'methods' => ['GET', 'POST']
                ],
                'custom-endpoints' => [
                    'callback' => 'customEndpoint',
                    'methods' => ['GET', 'POST']
                ]
            ]
        ]);
    }

    /**
     * @param string $key
     * @return object
     */
    public function getObjectParam(string $key): object
    {
        return (object) $this->request->getParam($key);
    }

    /**
     * The PaymentDataType class is created to contain all the data to manage the payment process.
     * @see PaymentDataType
     * @param mixed $result
     * @param \WP_REST_Server $server
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function middleware(mixed $result, \WP_REST_Server $server, \WP_REST_Request $request): mixed
    {
        // check if request is cryptopay
        if ('cryptopay-lite' === (Helpers::getRoutePaths($request->get_route())[0] ?? null)) {
            if ($addon = $this->request->getParam('cp_addon')) {
                try {
                    // check addon
                    Helpers::checkIntegration($this->addon = $addon);
                    $this->paymentData = new PaymentDataType($addon);
                    $this->paymentData->setUserId($this->currentUserId);
                    $this->paymentData->setHash($this->request->getParam('hash'));
                    $this->paymentData->setOrder(
                        OrderType::fromObject($this->getObjectParam('order'))
                    );
                    $this->paymentData->setParams(
                        ParamsType::fromObject($this->getObjectParam('params'))
                    );
                    $this->paymentData->setNetwork(
                        NetworkType::fromObject($this->getObjectParam('network'))
                    );
                    $this->paymentData->setDynamicData(
                        DynamicDataType::fromObject($this->getObjectParam('dynamicData'))
                    );
                } catch (\Exception $e) {
                    Helpers::debug($e->getMessage(), 'ERROR', $e);
                    Response::error($e->getMessage(), 'INT100');
                }
            }
        }

        return $result;
    }

    /**
     * When a network or currency is selected during the payment process,
     * this endpoint is used to obtain the payment amount,
     * payment address and parameters that the relevant network may need.
     * @return void
     */
    public function init(): void
    {
        try {
            $payment = new Payment($this->paymentData->getAddon());
            $payment->setOrder($this->paymentData->getOrder());
            $payment->setParams($this->paymentData->getParams());
            $init = $payment->init($this->paymentData->getNetwork());
            Response::success(null, $init->prepareForJsSide());
        } catch (InitializeException $e) {
            Helpers::debug($e->getMessage(), 'ERROR', $e);
            Response::error($e->getMessage(), 'INT100');
        }
    }

    /**
     * When the user confirms the payment or a payment is captured with the QR process,
     * if a model has been created for the relevant addon, it is used to create transaction data.
     * @return void
     */
    public function createTransaction(): void
    {
        $this->paymentData->setProcess(Process::CREATE_TRANSACTION);

        if ($this->paymentData->getModel() instanceof AbstractTransaction) {
            // check hash
            if (!$this->paymentData->getHash()) {
                Response::badRequest($this->getErrorMessage('IDE100'), 'IDE100', ['redirect' => 'reload']);
            }

            // check transaction exists
            if ($this->paymentData->getModel()->getTransactionByHash($this->paymentData->getHash())) {
                Response::error($this->getErrorMessage('TAE101'), 'TAE101', ['redirect' => 'reload']);
            }

            Helpers::debug('Create transaction filters before', 'INFO', $this->paymentData->forDebug());

            // data customizer
            $this->paymentData = Hook::callFilter('edit_payment_data', $this->paymentData);
            $this->paymentData = Hook::callFilter('before_payment_started_' . $this->addon, $this->paymentData);

            Helpers::debug('Create transaction filters after', 'INFO', $this->paymentData->forDebug());

            // get receiver address
            $receiver = Helpers::getReceiver($this->paymentData);

            $this->paymentData->getModel()->insert([
                'userId' => $this->currentUserId,
                'updatedAt' => current_time('mysql'),
                'createdAt' => current_time('mysql'),
                'status' => Status::PENDING->getValue(),
                'hash' => $this->paymentData->getHash(),
                'testnet' => Helpers::getTestnetStatus(),
                'orderId' => $this->paymentData->getOrder()->getId(),
                'code' => $this->paymentData->getNetwork()->getCode(),
                'order' => $this->paymentData->getOrder()->toJson(),
                'params' => $this->paymentData->getParams()->toJson(),
                'network' => $this->paymentData->getNetwork()->toJson(),
                'addresses' => (new AddressesType($receiver))->toJson(),
            ]);

            if ($this->paymentData->getModel()->getError()) {
                Helpers::debug('Create transaction error', 'INFO', [
                    'dbError' => $this->paymentData->getModel()->getError()
                ]);
                Response::error($this->paymentData->getModel()->getError(), 'INT100', ['redirect' => 'reload']);
            }

            Hook::callAction('payment_started', $this->paymentData);
            Hook::callAction('payment_started_' . $this->addon, $this->paymentData);

            $urls = Hook::callFilter('payment_redirect_urls_' . $this->addon, $this->paymentData);

            // set redirect urls to dynamic data
            $this->paymentData->getDynamicData()->set('redirectUrls', !is_object($urls) ? $urls : null);

            Response::success(esc_html__('Success'), $this->paymentData->getDynamicData()->all());
        }

        Response::error($this->getErrorMessage('MOD100'), 'MOD100', ['redirect' => 'reload']);
    }

    /**
     * If there is a model for the relevant addon and transaction data is created,
     * it is used to update the data after the payment transaction is completed.
     * The same process works in verifier.
     * @see Verifier
     * @return void
     */
    public function paymentFinished(): void
    {
        $this->paymentData->setProcess(Process::PAYMENT_FINISHED);

        if ($this->paymentData->getModel() instanceof AbstractTransaction) {
            // check hash
            if (!$this->paymentData->getHash()) {
                Response::badRequest($this->getErrorMessage('IDE100'), 'IDE100', ['redirect' => 'reload']);
            }

            // check transaction exists
            if (!$transaction = $this->paymentData->getModel()->getTransactionByHash($this->paymentData->getHash())) {
                Response::error($this->getErrorMessage('TNF101'), 'TNF101', ['redirect' => 'reload']);
            }

            $failedMessage = $this->getErrorMessage('PFE100');

            try {
                $this->paymentData->setStatus(Verifier::verifyTransaction($transaction));
            } catch (\Exception $e) {
                $this->paymentData->setStatus(false);
                $failedMessage = sprintf($this->getErrorMessage('PFE101'), $e->getMessage());
                $this->paymentData->getParams()->set('failedReason', $failedMessage);
                Helpers::debug('Payment finished error', 'ERROR', $e);
            }

            Helpers::debug('Payment finished filters before', 'INFO', $this->paymentData->forDebug());

            // data customizer
            $this->paymentData = Hook::callFilter('edit_payment_data', $this->paymentData);
            $this->paymentData = Hook::callFilter('before_payment_finished_' . $this->addon, $this->paymentData);

            Helpers::debug('Payment finished filters after', 'INFO', $this->paymentData->forDebug());

            // update transaction
            $this->paymentData->getModel()->updateWithPaymentData($this->paymentData, $transaction);

            Hook::callAction('payment_finished', $this->paymentData);
            Hook::callAction('payment_finished_' . $this->addon, $this->paymentData);

            // get redirect urls
            $urls = Hook::callFilter('payment_redirect_urls_' . $this->addon, $this->paymentData);

            if (is_object($urls) || is_array($urls) && !isset($urls['success']) && !isset($urls['failed'])) {
                Response::badRequest($this->getErrorMessage('PFE102'), 'PFE102', ['redirect' => 'reload']);
            }

            if ($this->paymentData->getStatus()) {
                $msg = esc_html__('Payment completed successfully', 'cryptopay');
                $msg = Hook::callFilter('payment_success_message_' . $this->addon, $msg);
                Response::success($msg, ['redirect' => $urls['success']]);
            } else {
                $msg = Hook::callFilter('payment_failed_message_' . $this->addon, $failedMessage);
                Response::error($msg, 'PFE100', ['redirect' => $urls['failed']]);
            }
        }

        Response::error($this->getErrorMessage('MOD100'), 'MOD100', ['redirect' => 'reload']);
    }

    /**
     * The user creates a reminder email without waiting for the payment to be completed.
     * @return void
     */
    public function setReminderEmail(): void
    {
        $email = $this->request->getParam('email');

        if (!$email) {
            Response::error(esc_html__('Please enter a valid email!', 'cryptopay'), 'GNR101');
        }

        Helpers::debug('Added reminder email', 'INFO', [
            'email' => $email
        ]);

        $this->paymentData->getModel()->update([
            'reminderEmail' => $email
        ], [
            'hash' => $this->paymentData->getHash()
        ]);

        Response::success(esc_html__("Reminder email set successfully!", 'cryptopay'));
    }

    /**
     * Currency converter process
     * @see Converter
     * @return void
     */
    public function currencyConverter(): void
    {
        try {
            Helpers::debug('Currency converting', 'INFO', $this->paymentData->forDebug());
            Response::success(null, Converter::convert($this->paymentData));
        } catch (\Exception $e) {
            Helpers::debug($e->getMessage(), 'ERROR', $e);
            Response::error($this->getErrorMessage('CCE101'), 'CCE101');
        }
    }


    /**
     * Verify pending transactions endpoint
     * @return void
     */
    public function verifyPendingTransactions(): void
    {
        if ('wp' == Helpers::getSetting('cronType')) {
            Response::error(esc_html__('Please disable WP Cron from settings!', 'cryptopay'), 'ERR');
        }

        try {
            $models = Helpers::getModels();

            Helpers::debug('Starting verify pending transactions on RestAPI', 'INFO', [
                'models' => array_keys($models)
            ]);

            foreach ($models as $model) {
                Verifier::verifyPendingTransactions($model);
            }
        } catch (\Throwable $th) {
            Helpers::debug($th->getMessage(), 'ERROR', $th);
            Response::error($th->getMessage(), 'ERR', [
                $th->getTrace()
            ]);
        }

        Response::success();
    }

    /**
     * Developers can set custom endpoints for their addons.
     * @return void
     */
    public function customEndpoint(): void
    {
        $endpoint = $this->request->getParam('endpoint');

        if (!$endpoint) {
            Response::error($this->getErrorMessage('CEE100'), 'CEE100');
        }

        Hook::callAction('custom_endpoint_' . $endpoint, $this->request, $this->paymentData);
    }

    /**
     * Get error message by code
     * @param string $code
     * @return string
     */
    private function getErrorMessage(string $code): string
    {
        $errorMap = [
            'INT100' => esc_html__('Internal error!', 'cryptopay'),
            'MOD100' => esc_html__('Model not found!', 'cryptopay'),
            'CEE100' => esc_html__('Endpoint not found!', 'cryptopay'),
            'IDE100' => esc_html__('Please enter a valid data.', 'cryptopay'),
            'TAE101' => esc_html__('Transaction already exists!', 'cryptopay'),
            'TNF101' => esc_html__('Transaction record not found!', 'cryptopay'),
            'PFE100' => esc_html__('Payment not verified via Blockchain', 'cryptopay'),
            'PFE101' => esc_html__('Payment not verified via Blockchain - Because reason: %s', 'cryptopay'),
            'PFE102' => esc_html__('Redirect links cannot found!', 'cryptopay'),
            // @phpcs:ignore
            'CCE101' => esc_html__('There was a problem converting currency! Make sure your currency value is available in the relevant API or you define a custom value for your currency.', 'cryptopay'),
        ];

        return $errorMap[$code] ?? esc_html__('An unknown error occurred!', 'cryptopay');
    }
}
