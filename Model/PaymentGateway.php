<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model;

use Yansongda\Pay\Pay;
use Magento\Payment\Helper\Data;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem\DirectoryList;

/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class PaymentGateway extends DataObject
{

    /**
     * @var Magento\Payment\Helper\Data
     */
    protected $config;

    /**
     * @var Magento\Framework\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @param Data $config
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Data $config,
        DirectoryList $directoryList
    ) {
        $this->config = $config;
        $this->directoryList = $directoryList;
    }

    public function wechat()
    {
        $wechatPay = $this->getData('wechatpay');

        if (null === $wechatPay) { 

            $paymentConfig = $this->config->getMethodInstance('wechatpay');

            $config = [
                'wechat' => [
                    'default' => [
                        // 必填-商户号，服务商模式下为服务商商户号
                        // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
                        'mch_id' => $paymentConfig->getConfigData('mch_id'),
                        // 必填-商户秘钥
                        // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
                        'mch_secret_key' => $paymentConfig->getConfigData('mch_secret_key'),
                        // 必填-商户私钥 字符串或路径
                        // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
                        // 文件名形如：apiclient_key.pem
                        'mch_secret_cert' => $paymentConfig->getConfigData('mch_secret_cert'),
                        // 必填-商户公钥证书路径
                        // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
                        // 文件名形如：apiclient_cert.pem
                        'mch_public_cert_path' => $paymentConfig->getConfigData('mch_public_cert'),
                        // 必填-微信回调url
                        // 不能有参数，如?号，空格等，否则会无法正确回调
                        'notify_url' => $paymentConfig->getConfigData('notify_url'),
                        // 选填-公众号 的 app_id
                        // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
                        'mp_app_id' => $paymentConfig->getConfigData('mp_app_id'),
                        // 选填-小程序 的 app_id
                        'mini_app_id' => $paymentConfig->getConfigData('mini_app_id'),
                        // 选填-app 的 app_id
                        'app_id' => '',
                        // 选填-合单 app_id
                        'combine_app_id' => '',
                        // 选填-合单商户号 
                        'combine_mch_id' => '',
                        // 选填-服务商模式下，子公众号 的 app_id
                        'sub_mp_app_id' => '',
                        // 选填-服务商模式下，子 app 的 app_id
                        'sub_app_id' => '',
                        // 选填-服务商模式下，子小程序 的 app_id
                        'sub_mini_app_id' => '',
                        // 选填-服务商模式下，子商户id
                        'sub_mch_id' => '',
                        // 选填-微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
                        'wechat_public_cert_path' => [
                            '6EE1E73D4311DF71787708DEA480F4D61A578AF3' => $paymentConfig->getConfigData('wechat_public_cert')
                        ],
                        // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
                        'mode' => Pay::MODE_NORMAL
                    ]
                ],
                'logger' => [
                    'enable' => false,
                    'file' => $this->directoryList->getRoot().'/wechatpay.log',
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

            $wechatPay = Pay::wechat();

            $this->setData('wechatpay', $wechatPay);

        }
        return $wechatPay;

    }
}