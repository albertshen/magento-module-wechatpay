/**
 * Copyright © PHPDigital, Inc. All rights reserved.
 */
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
  
        return Component.extend({
            defaults: {
                template: 'AlbertMage_WeChatPay/payment/wechatpay'
            }
        });
    }
);