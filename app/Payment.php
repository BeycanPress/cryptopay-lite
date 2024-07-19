<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

// Classes
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Services\Converter;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
// Types
use BeycanPress\CryptoPayLite\Types\InitType;
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Data\ConfigDataType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Transaction\ParamsType;
// Exceptions
use BeycanPress\CryptoPayLite\Exceptions\InitializeException;
use BeycanPress\CryptoPayLite\Exceptions\NoActiveNetworkException;
use BeycanPress\CryptoPayLite\Exceptions\NoActiveCurrencyException;

class Payment
{
    /**
     * @var string
     */
    private string $addon;

    /**
     * @var string
     */
    private bool $autoStart = true;

    /**
     * @var ConfigDataType
     */
    private ConfigDataType $config;

    /**
     * @var PaymentDataType
     */
    private PaymentDataType $data;

    /**
     * @param string $addon
     */
    public function __construct(string $addon)
    {
        $this->addon = $addon;
        $this->config = new ConfigDataType($addon);
        $this->data = new PaymentDataType($this->addon);
        $this->data->setUserId(Helpers::getCurrentUserId());

        // set default data for handle errors
        $this->data->setOrder(new OrderType());
        $this->data->setParams(new ParamsType());
        $this->config->setConfirmation(true);
    }

    /**
     * @param bool $autoStart
     * @return self
     */
    public function setAutoStart(bool $autoStart): self
    {
        $this->autoStart = $autoStart;
        return $this;
    }

    /**
     * @param OrderType $order
     * @return self
     */
    public function setOrder(OrderType $order): self
    {
        $this->data->setOrder($order);
        return $this;
    }

    /**
     * @param ParamsType $params
     * @return self
     */
    public function setParams(ParamsType $params): self
    {
        $this->data->setParams($params);
        return $this;
    }

    /**
     * @param boolean $confirmation
     * @return self
     */
    public function setConfirmation(bool $confirmation): self
    {
        $this->config->setConfirmation($confirmation);
        return $this;
    }

    /**
     * @param array<string> $deps
     * @return string
     */
    public function modal(array $deps = []): string
    {
        $this->config->setModal(true);
        return $this->html($deps);
    }

    /**
     * @param array<string> $deps
     * @param bool $loading
     * @return string
     */
    public function html(array $deps = [], bool $loading = false): string
    {
        try {
            $networks = EvmChains::getNetworks();

            // if no have network more than one, throw exception
            if (empty($networks)) {
                throw new NoActiveNetworkException(
                    esc_html__(
                        'No network is active, please activate at least one network!',
                        'cryptopay'
                    )
                );
            }

            // get js providers
            $jsProviders = $this->getJsProviders();

            $this->config->setNetworks($networks);

            $appKey = Helpers::addScript('app.min.js');

            // add dependencies for main js
            $deps = array_merge(
                $deps,
                $jsProviders->keys,
                ['jquery', $appKey],
            );

            Helpers::setProp('mainJsKey', $mainJsKey = Helpers::addScript('main.min.js', $deps), true);

            // config for cryptopay js app
            $this->config = Hook::callFilter('edit_config_data', $this->config);
            $this->config = Hook::callFilter('edit_config_data_' . $this->addon, $this->config);

            // vars for the here js files
            $vars = Hook::callFilter('js_variables', [
                'addon' => $this->addon,
                'autoStart' => $this->autoStart,
                'apiUrl' => Constants::getApiUrl()
            ]);

            // if order exists, add order to vars
            if ($this->data->getOrder()->exists()) {
                $vars['order'] = $this->data->getOrder()->prepareForJsSide();
            }

            // if params exists, add params to vars
            if ($params = $this->data->getParams()->toArray()) {
                $vars['params'] = $params;
            }

            // JS Variables
            $config = $this->config->prepareForJsSide();
            wp_localize_script($mainJsKey, 'CryptoPayLiteVars', $vars);
            wp_localize_script($mainJsKey, 'CryptoPayLiteConfig', $config);

            $html = Hook::callFilter('before_html', '', $this->config);

            $html .= Helpers::view('cryptopay', [
                'loading' => $loading,
                'addon' => $this->addon,
            ]);

            $html = Hook::callFilter('after_html', $html, $this->config);

            return $html;
        } catch (\Exception $e) {
            Helpers::debug($e->getMessage(), 'ERROR', $e);
            return $e->getMessage();
        }
    }

    /**
     * @return object
     */
    private function getJsProviders(): object
    {
        if (wp_script_is('cryptopay-evm-chains-provider', 'enqueued')) {
            $id = 'cryptopay-evm-chains-provider';
        } else {
            $id = Helpers::addScript('evm-chains-provider.js');
        }

        $providers = ['EvmChains' => $id];

        return (object) [
            'names' => array_keys($providers),
            'keys' => array_values($providers)
        ];
    }

    /**
     * @param NetworkType $network
     * @return InitType
     */
    public function init(NetworkType $network): InitType
    {
        try {
            Helpers::debug('Init', 'INFO', $network->forDebug());

            // set network
            $this->data->setNetwork($network);

            Helpers::debug('Payment filters before', 'INFO', $this->data->forDebug());

            // data customizer
            $this->data = Hook::callFilter('edit_payment_data', $this->data);
            $this->data = Hook::callFilter('init_' . $this->addon, $this->data);

            Helpers::debug('Payment filters after', 'INFO', $this->data->forDebug());

            // get payment currency from order
            // because in api payment currency setting,f
            // that's why we need to get payment currency from order
            // but if payment currency not set, get first currency from network
            if (!$paymentCurrency = $this->data->getOrder()->getPaymentCurrency()) {
                Helpers::debug('Get first currency from network', 'INFO');
                $paymentCurrency = $network->getCurrencies()->first();
            }

            // if payment currency not set, throw exception
            if (is_null($paymentCurrency)) {
                throw new NoActiveCurrencyException(
                    esc_html__(
                        'No active currencies were found on this network. Please report this to the administrator.',
                        'cryptopay'
                    )
                );
            } else {
                $this->data->getOrder()->setPaymentCurrency($paymentCurrency);
            }

            // if payment amount not set, convert amount and set
            if (!$this->data->getOrder()->getPaymentAmount()) {
                Helpers::debug('Calculate payment amount', 'INFO');
                $this->data->getOrder()->setPaymentAmount(Converter::convert($this->data));
            }

            // init data
            $receiver = Helpers::getReceiver($this->data);
            $blockConfirmationCount = Helpers::getBlockConfirmationCount($network->getCode());

            // just for pretty
            $order = $this->data->getOrder();

            return new InitType(
                $order,
                $receiver,
                $blockConfirmationCount
            );
        } catch (\Exception $e) {
            Helpers::debug($e->getMessage(), 'ERROR', $e);
            throw new InitializeException($e->getMessage(), $e->getCode());
        }
    }
}
