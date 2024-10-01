;(($) => {
    $(document).ready(() => {
        // set vars
        window.CryptoPayLiteLang = CryptoPayLiteConfig.lang;
        window.CryptoPayLiteConfig = window.cplTyping(CryptoPayLiteConfig);
        window.CryptoPayLiteApp = InitCryptoPayLite('cryptopay-lite', CryptoPayLiteConfig);

        // auto start, if order exists and autoStart is true
        if (Boolean(window.CryptoPayLiteVars?.order && window.CryptoPayLiteVars?.autoStart)) {
            const params = window.CryptoPayLiteVars?.params || {};
            window.CryptoPayLiteApp.start(window.CryptoPayLiteVars.order, params);
        }
    });
})(jQuery);