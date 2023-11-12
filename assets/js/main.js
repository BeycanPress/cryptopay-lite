(async() => {

    await new Promise((resolve) => {
        if (CryptoPayLite.providers.length > 0) {
            let providers = CryptoPayLite.providers;
            CryptoPayLite.providers = {}
            providers.forEach(function (provider, index) {
                CryptoPayLite.providers[provider.toLowerCase()] = window[provider];
                if (index === providers.length - 1) resolve(CryptoPayLite.providers);
            });
        } else {
            resolve(CryptoPayLite.providers = {});
        }
    });

    CryptoPayLite.startPayment = (order, params) => {
        if (!order.amount) {
            throw new Error('Order amount is required');
        }

        if (!order.currency) {
            throw new Error('Order currency is required');
        }

        return window.CryptoPayLiteApp = initCryptoPayLite('cryptopay', Object.assign(CryptoPayLite, {order, params}));
    }

    if (CryptoPayLite.autoLoad) {
        window.CryptoPayLiteApp = initCryptoPayLite('cryptopay', CryptoPayLite);
    }

})();