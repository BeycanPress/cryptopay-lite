;(($) => {

    const instant = CryptoPayLiteVars.instant;

    if ($(".start-cryptopay-instant").length > 0) {
        let symbol = $(".woocommerce-Price-currencySymbol").html();
        $(document).on('change keyup', '[name="quantity"]', function (e) {
            let quantity = $(this).val();
            let total = parseFloat((instant.amount * quantity)).toFixed(instant.decimals);
            $(".woocommerce-Price-amount bdi").html(
                `<span class="woocommerce-Price-currencySymbol">${symbol}</span>${total}`
            );
        });
    }

    let startedApp;
    const autoStarter = (productId, quantity, total) => {
        let order = {
            amount: parseFloat(total),
            currency: instant.currency
        }

        let params = {
            quantity,
            productId,
            instant: true
        }

        if (!startedApp) {
            startedApp = CryptoPayLiteApp.start(order, params);
        } else {
            startedApp.reStart(order, params);
        }
    }

    $(document).on('click', ".start-cryptopay-instant", function (e) {
        if (!instant) {
            return cplHelpers.errorPopup(CryptoPayLiteLang.instantPaymentMsg);
        }

        let productId = instant.productId;
        let quantity = $('[name="quantity"]').val();

        let total = parseFloat((instant.amount * quantity)).toFixed(instant.decimals);

        autoStarter(productId, quantity, total);

        CryptoPayLiteApp.modal.open();
    });
})(jQuery);
