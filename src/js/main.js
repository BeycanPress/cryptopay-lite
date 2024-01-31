;(($) => {
    $(document).ready(() => {
        // set vars
        window.CryptoPayLang = CryptoPayConfig.lang;
        window.CryptoPayConfig = window.cpTyping(CryptoPayConfig);
        window.CryptoPayApp = InitCryptoPay('cryptopay-lite', CryptoPayConfig);

        // auto start, if order exists and autoStart is true
        if (Boolean(window.CryptoPayVars?.order && window.CryptoPayVars?.autoStart)) {
            const params = window.CryptoPayVars?.params || {};
            window.CryptoPayApp.start(window.CryptoPayVars.order, params);
        }

        // methods
        window.CryptoPayModal = {
            open: ()  => {
                $(".cpl-modal").css('display', 'flex');
                $(document).trigger('cpModaOpened');
            },
            close: () => {
                $(".cpl-modal").hide();
                $(document).trigger('cpModalClosed');
            }
        }

        // events
        $(window).on('click', function(e) {
            if (e.target == $(".cpl-modal")[0]) {
                CryptoPayModal.close();
            }
        });
        
        $(document).on('click', '.cpl-modal-close', function() {
            CryptoPayModal.close();
        });

        if (!window.customElements.get('cpl-powered-by')) {
            const template = document.createElement('template');
            template.innerHTML = `
                <div class="footer">
                    <span class="powered-by">
                        Powered by
                    </span>
                    <a href="https://beycanpress.com/cryptopay?utm_source=powered_by_lite&utm_medium=web_component" target="_blank">CryptoPay</a>
                </div>
                <style>
                    .footer {
                        display: flex;
                        font-size: 14px;
                        padding: 20px 30px;
                        padding-bottom: 0px;
                        justify-content: center;
                        font-family: Arial, Helvetica, sans-serif;
                    }
                    .footer a {
                        font-weight: 600;
                        color: #7F7F7F;
                        text-decoration: none;
                    }
                    .footer .powered-by {
                        color: #7F7F7F;
                        font-weight: 400;
                        margin-right: 5px;
                    }
                </style>
            `;
            
            class CPPoweredBy extends HTMLElement {
                constructor(){
                    super();
                    this.attachShadow({ mode: 'open'});
                    this.shadowRoot.appendChild(template.content.cloneNode(true));
                } 
            }

            window.customElements.define('cpl-powered-by', CPPoweredBy);
        }
    });
})(jQuery);