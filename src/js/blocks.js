(() => {
    const settings = wc.wcSettings.getSetting('cryptopay_lite_data', {});
    const { registerPaymentMethod } = window.wc.wcBlocksRegistry;
    const createElement = window.wp.element.createElement;
    const { decodeEntities } = window.wp.htmlEntities;
    const supports = { features: settings.supports };

    const name = decodeEntities(settings.name || '');
    const label = decodeEntities(settings.label || '');
    const button = decodeEntities(settings.button || '');

    const Content = () => {
        return decodeEntities(settings.content || '');
    }

    const ReactElement = (type, props = {}, ...childs) => {
        return Object(createElement)(type, props, ...childs);
    }

    const Label = ({components}) => {
        const {PaymentMethodLabel, PaymentMethodIcons} = components;

        const labelComp = ReactElement(PaymentMethodLabel, {
            text: label,
        });

        const iconsComp = ReactElement(PaymentMethodIcons, {
            icons: settings.icons,
        });

        return ReactElement('div', {
            className: name + '-payment-gateway', 
        }, labelComp, iconsComp);
    }

    registerPaymentMethod({
        name,
        supports,
        ariaLabel: label,
        paymentMethodId: name,
        canMakePayment: () => true,
        label: ReactElement(Label),
        edit: ReactElement(Content),
        content: ReactElement(Content),
        placeOrderButtonLabel: button
    });
})();