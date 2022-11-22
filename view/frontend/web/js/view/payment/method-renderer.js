/**
 * Copyright © PHPDigital, Inc. All rights reserved.
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'wechatpay',
                component: 'AlbertMage_WeChatPay/js/view/payment/method-renderer/wechatpay'
            }
        );
        return Component.extend({});
    }
);