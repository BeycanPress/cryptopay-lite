(async() => {
    
    if (typeof CryptoPayLite?.params?.length !== 'undefined') {
        CryptoPayLite.params = {};
    }

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

    CryptoPayLite.startPayment = (order = {}, params = {}) => {
        
        if (!CryptoPayLite?.order?.amount && !order.amount) {
            throw new Error('Order amount is required');
        }

        if (!CryptoPayLite?.order?.currency && !order.currency) {
            throw new Error('Order currency is required');
        }

        CryptoPayLite.order = Object.assign(CryptoPayLite.order || {}, order);

        CryptoPayLite.params = Object.assign(CryptoPayLite.params || {}, params);

        return window.CryptoPayLiteApp = initCryptoPayLite('cryptopay', CryptoPayLite);
    }

    if (CryptoPayLite.autoLoad) {
        window.CryptoPayLiteApp = initCryptoPayLite('cryptopay', CryptoPayLite);
    }

})();