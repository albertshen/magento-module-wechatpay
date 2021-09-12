/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Module Created By : Rohan Hapani
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
                component: 'Albert_WeChatPay/js/view/payment/method-renderer/wechatpay'
            }
        );
        return Component.extend({});
    }
);