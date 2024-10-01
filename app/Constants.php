<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

// @phpcs:disable Generic.Files.LineLength 

use BeycanPress\CryptoPayLite\PluginHero\Hook;

class Constants
{
    /**
     * @return string
     */
    public static function getImagesUrl(): string
    {
        return Helpers::getProp('pluginUrl') . 'assets/images/';
    }

    /**
     * @return string
     */
    public static function getLogoUrl(): string
    {
        return Helpers::getProp('pluginUrl') . 'assets/images/icon-256x256.png';
    }

    /**
     * @return string
     */
    public static function getApiUrl(): string
    {
        return Helpers::getAPI('RestAPI')->getUrl();
    }

    /**
     * @return object<string>'
     */
    public static function getLangParams(): object
    {
        return (object) Hook::callFilter('lang', [
            "selectNetwork" => esc_html__('Select network', 'cryptopay'),
            "orderId" => esc_html__('Order ID:', 'cryptopay'),
            "orderAmount" => esc_html__('Order amount:', 'cryptopay'),
            "selectedNetwork" => esc_html__('Selected network:', 'cryptopay'),
            "waitingBlockConfirmations" => esc_html__('Waiting for block confirmations:', 'cryptopay'),
            "waitingTransactionConfirmations" => esc_html__('Waiting for transaction confirmation...', 'cryptopay'),
            "openInExplorer" => esc_html__('Open in explorer', 'cryptopay'),
            "waitingConfirmation" => esc_html__('Waiting confirmation...', 'cryptopay'),
            "selectWallet" => esc_html__('Select wallet', 'cryptopay'),
            "selectPaymentMethod" => esc_html__('Select payment method', 'cryptopay'),
            "pleaseTryToConnectAgain" => esc_html__('Please try to connect again by selecting network {networkName} from your wallet!', 'cryptopay'),
            "walletConnectionTimedOut" => esc_html__('Wallet connection timed out!', 'cryptopay'),
            "connectionError" => esc_html__('Connection error!', 'cryptopay'),
            "paymentCurrency" => esc_html__('Payment currency:', 'cryptopay'),
            "amountToBePaid" => esc_html__('Amount to be paid:', 'cryptopay'),
            "payNow" => esc_html__('Pay now', 'cryptopay'),
            "payWith" => esc_html__('Pay with {name}', 'cryptopay'),
            "loading" => esc_html__('Loading...', 'cryptopay'),
            "waitingApproval" => esc_html__('Waiting approval...', 'cryptopay'),
            "paymentRejected" => esc_html__('Payment rejected!', 'cryptopay'),
            "transferAmountError" => esc_html__('Transfer amount need to be bigger from zero!', 'cryptopay'),
            "transactionCreateFail" => esc_html__('Transaction create fail! Please try again.', 'cryptopay'),
            "pleaseTryAgain" => esc_html__('Please try again!', 'cryptopay'),
            "insufficientBalance" => esc_html__('Insufficient balance!', 'cryptopay'),
            "openWallet" => esc_html__('Open wallet', 'cryptopay'),
            "paymentAddress" => esc_html__('Payment address:', 'cryptopay'),
            "paymentTimedOut" => esc_html__('Payment timed out!', 'cryptopay'),
            "connectionRejected" => esc_html__('Connection rejected!', 'cryptopay'),
            "pleaseWait" => esc_html__('Please wait...', 'cryptopay'),
            "convertingError" => esc_html__('There was a problem converting currency! Make sure your currency value is available in the relevant API or you define a custom price value for your currency.', 'cryptopay'),
            "transactionSent" => esc_html__('Transaction sent', 'cryptopay'),
            "notFoundAnyWallet" => esc_html__('No working wallet or qr payment service was found on this network. Please make sure you have a {networkName} wallet working on the browser or contact the administrator regarding the situation.', 'cryptopay'),
            "alreadyProcessing" => esc_html__('There is currently a process on the wallet. Please try again after completing the relevant process.', 'cryptopay'),
            "wallet-not-found" => esc_html__('Wallet not found!', 'cryptopay'),
            "rpcAccessForbidden" => esc_html__('RPC Address refused to connect (This is not an error!). Blockchain networks main RCP API addresses are public, so it can restrict you, in which case you need to get a unique RPC API address from any provider as stated in the documentation. Please report the situation to the site administrator.', 'cryptopay'),
            "rpcConnectionError" => esc_html__('This is an RPC connection error. The relevant RPC address may have been entered incorrectly, or if it is a public RPC, your IP address may have been banned. So please make sure everything is OK. Try custom RPC providers. Please report the situation to the site administrator.', 'cryptopay'),
            "invalidAddress" => esc_html__('Failed to match network with corresponding payout wallet. Please notify the site administrator of the situation.', 'cryptopay'),
            "anyError" => esc_html__('An unexpected error has occurred, please try again or contact the site administrator.', 'cryptopay'),
            "continuePaymentProcess" => esc_html__('Continue payment process', 'cryptopay'),
            'defaultErrorMsg' => esc_html__('Error processing checkout. Please try again.', 'cryptopay'),
            "checkingForm" => esc_html__('Checking form data! Please wait...', 'cryptopay'),
            "tokenMetadataNotFound" => esc_html__('Token metadata not found!', 'cryptopay'),
            "moralisApiKeyNotSet" => esc_html__('Moralis API key not set! Please contact the site administrator.', 'cryptopay'),
            "detected" => esc_html__('Detected', 'cryptopay'),
            "download" => esc_html__('Download', 'cryptopay'),
            "openInApp" => esc_html__('Open In App', 'cryptopay'),
            "openInAppManual" => esc_html__('Open In App (Manual)', 'cryptopay'),
            "onlyDesktop" => esc_html__('Only Desktop', 'cryptopay'),
            "websocketConnectionFailed" => esc_html__('Websocket connection failed!', 'cryptopay'),
            "websocketNotSupported" => esc_html__('A transaction was caught, but execution aborted due to an error on Websocket. Please specify a supported Websocket address.', 'cryptopay'),
            "corsError" => esc_html__('You cannot connect to our QR verifier service because you do not have a valid license. If you see this message as a visitor, please report it to the site administrator.', 'cryptopay'),
            "change" => esc_html__('Change', 'cryptopay'),
            "changeNetwork" => esc_html__('Change network', 'cryptopay'),
            "web3Domain" => esc_html__('Web3 Domain: ', 'cryptopay'),
            "notFoundAnyCurrency" => esc_html__('No active currencies were found on this network. Please report this to the administrator.', 'cryptopay'),
            "notFoundAnyCurrencyForPayment" => esc_html__('Sorry, no currency found! Please report this to the administrator.', 'cryptopay'),
            "notFoundAnyNetworkForPayment" => esc_html__('Sorry, no network found! Please report this to the administrator.', 'cryptopay'),
            "paymentCompleting" => esc_html__('Payment is being completed, please wait...', 'cryptopay'),
            "openInAppMsg" => esc_html__('This is a informational message. You tried to redirect to the application with Deep Link. If no application guidance has been made. It means you do not have the relevant wallet application. Please get the relevant wallet app from the app market.', 'cryptopay'),
            "openInAppManualMsg" => esc_html__('This is an informational message. This wallet does not support Deep Linking. Therefore, you can try to connect to this wallet directly using the browser in the application.', 'cryptopay'),
            "onlyDesktopMsg" => esc_html__('This is an informational message. This wallet does not have support on mobile platforms. So please try connecting to the wallet via a desktop browser.', 'cryptopay'),
            "downloadManualMsg" => esc_html__('This is an informational message. No download link is provided for this wallet. So please try connecting after downloading the wallet.', 'cryptopay'),
            "downloadMsg" => esc_html__('This is an informational message. You were redirected to the download link because the relevant wallet could not be detected in your browser. Please try connecting again after installing the wallet.', 'cryptopay'),
            "processing" => esc_html__('Processing...', 'cryptopay'),
            "instantPaymentMsg" => esc_html__('This is an informational message. Instant payment data not loaded. Please report the situation to the site administrator', 'cryptopay'),
            "email" => esc_html__('Email', 'cryptopay'),
            "save" => esc_html__('Save', 'cryptopay'),
            "enterYourEmailAddress" => esc_html__('Enter your email address', 'cryptopay'),
            "pleaseEnterAValidEmailAddress" => esc_html__('Please enter a valid email address', 'cryptopay'),
            "setReminderEmail" => esc_html__('Set reminder email', 'cryptopay'),
            "importantInformation" => esc_html__('Important information', 'cryptopay'),
            "continue" => esc_html__('Continue', 'cryptopay'),
            "setReminderEmailMsg" => esc_html__('When you set up a reminder email, you won\'t have to wait for payment and will receive an email notification when the process is completed in the background.', 'cryptopay'),
            "selectCurrency" => esc_html__('Select currency', 'cryptopay'),
            "gettingPrice" => esc_html__('The price is being taken...', 'cryptopay'),
            "search" => esc_html__('Search', 'cryptopay'),
            "connectionWallet" => esc_html__('Connecting to wallet...', 'cryptopay'),
            "discount" => esc_html__('Discount', 'cryptopay'),
            "checkingSanctions" => esc_html__('Checking address sanctions...', 'cryptopay'),
            "sanctionedMessage" => esc_html__('Your wallet has been marked by some sanction-controlling platforms. That \'s why we don\'t accept payments from this wallet address. {source}', 'cryptopay'),
            "source" => esc_html__('Source:', 'cryptopay'),
            "rpcTimeout" => esc_html__('RPC Timeout! We cannot continue the process because the RPC API of the current blockchain does not respond to our requests. Please report this to the website administrator!', 'cryptopay'),
            "redirecting" => esc_html__('Redirecting...', 'cryptopay'),
            "connecting" => esc_html__('Connecting...', 'cryptopay'),
            "cannotPaySameAddress" => esc_html__('The payment cannot proceed because the payer and receiver address are the same! Please pay with a different wallet!', 'cryptopay'),
            "reownProjectIdRequired" => esc_html__('Reown Project ID is required! Please contact the site administrator.', 'cryptopay'),
        ]);
    }

    /**
     * @return array<string,string>
     */
    public static function getCountryCurrencies(): array
    {
        return [
            'USD' => 'United States Dollar',
            'EUR' => 'Euro Member Countries',
            'GBP' => 'United Kingdom Pound',
            'ALL' => 'Albania Lek',
            'AFN' => 'Afghanistan Afghani',
            'ARS' => 'Argentina Peso',
            'AWG' => 'Aruba Guilder',
            'AUD' => 'Australia Dollar',
            'AZN' => 'Azerbaijan New Manat',
            'BSD' => 'Bahamas Dollar',
            'BBD' => 'Barbados Dollar',
            'BDT' => 'Bangladeshi taka',
            'BYR' => 'Belarus Ruble',
            'BZD' => 'Belize Dollar',
            'BMD' => 'Bermuda Dollar',
            'BOB' => 'Bolivia Boliviano',
            'BAM' => 'Bosnia and Herzegovina Convertible Marka',
            'BWP' => 'Botswana Pula',
            'BGN' => 'Bulgaria Lev',
            'BRL' => 'Brazil Real',
            'BND' => 'Brunei Darussalam Dollar',
            'KHR' => 'Cambodia Riel',
            'CAD' => 'Canada Dollar',
            'KYD' => 'Cayman Islands Dollar',
            'CLP' => 'Chile Peso',
            'CNY' => 'China Yuan Renminbi',
            'COP' => 'Colombia Peso',
            'CRC' => 'Costa Rica Colon',
            'HRK' => 'Croatia Kuna',
            'CUP' => 'Cuba Peso',
            'CZK' => 'Czech Republic Koruna',
            'DKK' => 'Denmark Krone',
            'DOP' => 'Dominican Republic Peso',
            'XCD' => 'East Caribbean Dollar',
            'EGP' => 'Egypt Pound',
            'SVC' => 'El Salvador Colon',
            'EEK' => 'Estonia Kroon',
            'FKP' => 'Falkland Islands (Malvinas) Pound',
            'FJD' => 'Fiji Dollar',
            'GHC' => 'Ghana Cedis',
            'GIP' => 'Gibraltar Pound',
            'GTQ' => 'Guatemala Quetzal',
            'GGP' => 'Guernsey Pound',
            'GYD' => 'Guyana Dollar',
            'HNL' => 'Honduras Lempira',
            'HKD' => 'Hong Kong Dollar',
            'HUF' => 'Hungary Forint',
            'ISK' => 'Iceland Krona',
            'INR' => 'India Rupee',
            'IDR' => 'Indonesia Rupiah',
            'IRR' => 'Iran Rial',
            'IMP' => 'Isle of Man Pound',
            'ILS' => 'Israel Shekel',
            'JMD' => 'Jamaica Dollar',
            'JPY' => 'Japan Yen',
            'JEP' => 'Jersey Pound',
            'KZT' => 'Kazakhstan Tenge',
            'KPW' => 'Korea (North) Won',
            'KRW' => 'Korea (South) Won',
            'KGS' => 'Kyrgyzstan Som',
            'LAK' => 'Laos Kip',
            'LVL' => 'Latvia Lat',
            'LBP' => 'Lebanon Pound',
            'LRD' => 'Liberia Dollar',
            'LTL' => 'Lithuania Litas',
            'MKD' => 'Macedonia Denar',
            'MYR' => 'Malaysia Ringgit',
            'MUR' => 'Mauritius Rupee',
            'MXN' => 'Mexico Peso',
            'MNT' => 'Mongolia Tughrik',
            'MZN' => 'Mozambique Metical',
            'NAD' => 'Namibia Dollar',
            'NPR' => 'Nepal Rupee',
            'ANG' => 'Netherlands Antilles Guilder',
            'NZD' => 'New Zealand Dollar',
            'NIO' => 'Nicaragua Cordoba',
            'NGN' => 'Nigeria Naira',
            'NOK' => 'Norway Krone',
            'OMR' => 'Oman Rial',
            'PKR' => 'Pakistan Rupee',
            'PAB' => 'Panama Balboa',
            'PYG' => 'Paraguay Guarani',
            'PEN' => 'Peru Nuevo Sol',
            'PHP' => 'Philippines Peso',
            'PLN' => 'Poland Zloty',
            'QAR' => 'Qatar Riyal',
            'RON' => 'Romania New Leu',
            'RUB' => 'Russia Ruble',
            'SHP' => 'Saint Helena Pound',
            'SAR' => 'Saudi Arabia Riyal',
            'RSD' => 'Serbia Dinar',
            'SCR' => 'Seychelles Rupee',
            'SGD' => 'Singapore Dollar',
            'SBD' => 'Solomon Islands Dollar',
            'SOS' => 'Somalia Shilling',
            'ZAR' => 'South Africa Rand',
            'LKR' => 'Sri Lanka Rupee',
            'SEK' => 'Sweden Krona',
            'CHF' => 'Switzerland Franc',
            'SRD' => 'Suriname Dollar',
            'SYP' => 'Syria Pound',
            'TWD' => 'Taiwan New Dollar',
            'THB' => 'Thailand Baht',
            'TTD' => 'Trinidad and Tobago Dollar',
            'TRY' => 'Turkey Lira',
            'TRL' => 'Turkey Lira',
            'TVD' => 'Tuvalu Dollar',
            'UAH' => 'Ukraine Hryvna',
            'UYU' => 'Uruguay Peso',
            'UZS' => 'Uzbekistan Som',
            'VEF' => 'Venezuela Bolivar',
            'VND' => 'Viet Nam Dong',
            'YER' => 'Yemen Rial',
            'ZWD' => 'Zimbabwe Dollar'
        ];
    }
}
