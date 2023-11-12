<?php

namespace BeycanPress\CryptoPayLite;

class Lang
{
    public static function get() : array
    {
        return [
            "selectNetwork" => esc_html__('Select network', 'cryptopay_lite'),
            "orderId" => esc_html__('Order ID:', 'cryptopay_lite'),
            "orderAmount" => esc_html__('Order amount:', 'cryptopay_lite'),
            "selectedNetwork" => esc_html__('Selected network:', 'cryptopay_lite'),
            "waitingBlockConfirmations" => esc_html__('Waiting for block confirmations:', 'cryptopay_lite'),
            "waitingTransactionConfirmations" => esc_html__('Waiting for transaction confirmation...', 'cryptopay_lite'),
            "openInExplorer" => esc_html__('Open in explorer', 'cryptopay_lite'),
            "waitingConfirmation" => esc_html__('Waiting confirmation...', 'cryptopay_lite'),
            "selectWallet" => esc_html__('Select wallet', 'cryptopay_lite'),
            "pleaseTryToConnectagain" => esc_html__('Please try to connect again by selecting network {networkName} from your wallet!', 'cryptopay_lite'),
            "walletConnectionTimedOut" => esc_html__('Wallet connection timed out!', 'cryptopay_lite'),
            "connectionError" => esc_html__('Connection error!', 'cryptopay_lite'),
            "paymentCurrency" => esc_html__('Payment currency:', 'cryptopay_lite'),
            "amountToBePaid" => esc_html__('Amount to be paid:', 'cryptopay_lite'),
            "payNow" => esc_html__('Pay now', 'cryptopay_lite'),
            "loading" => esc_html__('Loading...', 'cryptopay_lite'),
            "waitingApproval" => esc_html__('Waiting approval...', 'cryptopay_lite'),
            "paymentRejected" => esc_html__('Payment rejected!', 'cryptopay_lite'),
            "transferAmountError" => esc_html__('Transfer amount need to be bigger from zero!', 'cryptopay_lite'),
            "transactionCreateFail" => esc_html__('Transaction create fail!', 'cryptopay_lite'),
            "pleaseTryAgain" => esc_html__('Please try again!', 'cryptopay_lite'),
            "insufficientBalance" => esc_html__('Insufficient balance!', 'cryptopay_lite'),
            "openWallet" => esc_html__('Open wallet', 'cryptopay_lite'),
            "paymentAddress" => esc_html__('Payment address:', 'cryptopay_lite'),
            "paymentTimedOut" => esc_html__('Payment timed out!', 'cryptopay_lite'),
            "connectionRejected" => esc_html__('Connection rejected!', 'cryptopay_lite'),
            "pleaseWait" => esc_html__('Please wait...', 'cryptopay_lite'),
            "convertingError" => esc_html__('There was a problem converting currency! Make sure your currency value is available in the relevant API!', 'cryptopay_lite'),
            "transactionSent" => esc_html__('Transaction sent', 'cryptopay_lite'),
            "notFoundAnyWallet" => esc_html__('No working wallets were found on this network. Please make sure you have a {networkName} wallet.', 'cryptopay_lite'),
            "alreadyProcessing" => esc_html__('There is currently a process on the wallet. Please try again after completing the relevant process.', 'cryptopay_lite'),
            "wallet-not-found" => esc_html__('Wallet not found!', 'cryptopay_lite'),
            "detected" => esc_html__('Detected', 'cryptopay_lite'),
            "download" => esc_html__('Download', 'cryptopay_lite'),
            "openInApp" => esc_html__('Open In App', 'cryptopay_lite'),
            "rpcConnectionError" => esc_html__('This is an RPC connection error. The relevant RPC address may have been entered incorrectly, or if it is a public RPC, your IP address may have been banned. So please make sure everything is OK. Try custom RPC providers. Please report the situation to the site administrator.', 'cryptopay_lite'),
            "processing" => esc_html__('Processing...', 'cryptopay_lite'),
        ];
    }

}