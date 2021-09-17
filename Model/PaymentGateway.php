<?php
/**
 * Copyright © PHPDigital, Inc. All rights reserved.
 *
 * Module Created By : Albert Shen
 */
namespace Albert\Magento\WeChatPay\Model;

use Albert\Payment\Pay;

class PaymentGateway
{

    protected $config;

    protected $rootPath;

    public function __construct() 
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->config = $objectManager->get('Magento\Payment\Helper\Data');
        $this->rootPath = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList')->getRoot();
    }

    public function wechat()
    {
        $paymentConfig = $this->config->getMethodInstance('wechatpay');

        $config = [
            'wechat' => [
                'default' => [
                    // 公众号 的 app_id
                    'mp_app_id' => $paymentConfig->getConfigData('mp_app_id'),
                    // 小程序 的 app_id
                    'mini_app_id' => '',
                    // app 的 app_id
                    'app_id' => '',
                    // 必填-商户号 
                    'mch_id' => $paymentConfig->getConfigData('mch_id'),
                    // 合单 app_id
                    'combine_app_id' => '',
                    // 合单商户号 
                    'combine_mch_id' => '',
                    // 必填-商户秘钥
                    'mch_secret_key' => $paymentConfig->getConfigData('mch_secret_key'),
                    // 必填-商户私钥
                    'mch_secret_cert' => $this->rootPath.'/'.$paymentConfig->getConfigData('mch_secret_cert_path'),
                    // 必填-商户公钥证书路径
                    'mch_public_cert_path' => $this->rootPath.'/'.$paymentConfig->getConfigData('mch_public_cert_path'),
                    // 选填-微信公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
                    'wechat_public_cert_path' => [
                        '6EE1E73D4311DF71787708DEA480F4D61A578AF3' => $this->rootPath.'/'.$paymentConfig->getConfigData('wechat_public_cert_path'),
                    ],
                    // 必填
                    'notify_url' => $paymentConfig->getConfigData('notify_url'),
                ]
            ],
            'logger' => [
                'enable' => false,
                'file' => $this->rootPath.'/wechatpay.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0
            ],
        ];

        Pay::config($config);

        return Pay::wechat();
    }
}