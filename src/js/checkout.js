;(($) => {  
    $(document).ready(() => {
        // CryptoPay JS App
        let startedApp;

        $form = $('form.checkout');

        function handleUnloadEvent() {
            if((navigator.userAgent.indexOf('MSIE') !== -1 ) || (!!document.documentMode)) {
                // IE handles unload events differently than modern browsers
                e.preventDefault();
                return undefined;
            }

            return true;
        }

        function scrollToNotices() {
            var scrollElement = $('.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout');
    
            if ( ! scrollElement.length ) {
                scrollElement = $form;
            }
            
            if ( scrollElement.length ) {
                $( 'html, body' ).animate( {
                    scrollTop: ( scrollElement.offset().top - 100 )
                }, 1000 );
            }
        }

        function printError(errorMessage) {
            $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
            $form.prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + errorMessage + '</div>');
            $form.removeClass('processing').unblock();
            $form.find('.input-text, select, input:checkbox').trigger('validate').trigger('blur');
            scrollToNotices();
            $(document.body).trigger('checkout_error' , [errorMessage]);
        }

        async function checkFormProcess(network, currency, init = true) {
            let data = $form.serialize();
            const params = CryptoPayLiteVars.params || {};
            network = cplHelpers.removeNoNeededParams(network);
            
            data += '&cp_init=' + init;
            data += '&cp_params=' + JSON.stringify(params);
            data += '&cp_network=' + JSON.stringify(network);
            data += '&cp_currency=' + JSON.stringify(currency);

            let res = await new Promise((resolve) => {
                $.ajax({
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    url: wc_checkout_params.checkout_url,
                    success:	function(response) {
                        $(window).off('beforeunload', handleUnloadEvent);

                        if (response.success) {
                            $(".woocommerce-NoticeGroup-checkout").remove();
                            if (response.data && response.data.init) {
                                startedApp.store.payment.setInit(response.data.init);
                            }
                            resolve(null);
                        } else {
                            if (response.data) {
                                if (true === response.data.reload) {
                                    window.location.reload();
                                    return;
                                }
    
                                if (true === response.data.refresh) {
                                    $(document.body).trigger('update_checkout');
                                }
                            }

                            if (response.message || response.messages) {
                                printError(response.message ? response.message : response.messages);
                            } else {
                                printError('<div class="woocommerce-error">' + CryptoPayLiteLang.defaultErrorMsg + '</div>'); 
                            }

                            resolve({error: true});
                        }
                    },
                    error:	function(jqXHR, textStatus, errorThrown) {
                        $(window).off('beforeunload', handleUnloadEvent);

                        printError(
                            '<div class="woocommerce-error">' +
                            (errorThrown || CryptoPayLiteLang.defaultErrorMsg) +
                            '</div>'
                        );

                        resolve({
                            error: true,
                        });
                    },
                    complete: function() {
                        cplHelpers.closePopup();
                        CryptoPayLiteApp.dynamicData.add({
                            wcForm: cplHelpers.formToObj('form.checkout')
                        });
                    }
                });
            });

            return res;
        }

        if (CryptoPayLiteConfig.init) {
            CryptoPayLiteApp.events.add('init', async (ctx) => {
                ctx.disableAutoQr = true;
            }, 'wc_checkout');
            CryptoPayLiteApp.events.add('payNow', (ctx) => {
                cplHelpers.waitingPopup(CryptoPayLiteLang.checkingForm);
                return checkFormProcess(ctx.network, ctx.order.paymentCurrency, false);
            }, 'wc_checkout');
        } else {
            CryptoPayLiteApp.events.add('init', async (ctx) => {
                ctx = Object.assign(ctx, (await checkFormProcess(ctx.network, ctx.currency)));
            }, 'wc_checkout');
        }
    
        $(document).on('updated_checkout', function () {
            if (!startedApp) {
                startedApp = CryptoPayLiteApp.start(CryptoPayLiteVars.order);
            } else {
                startedApp.reStart(CryptoPayLiteVars.order);
            }
        });
    
        $('form.checkout').on('change', 'input[name="payment_method"]', function() {
            $(document.body).trigger('update_checkout');
        });
    });
})(jQuery);