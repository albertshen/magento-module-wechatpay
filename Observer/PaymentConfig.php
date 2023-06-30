<?php

namespace AlbertMage\WeChatPay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class PaymentConfig implements ObserverInterface
{
    const XML_PATH_MCH_SECRET_CERT = 'payment/wechatpay/mch_secret_cert';

    const XML_PATH_MCH_PUBLIC_CERT = 'payment/wechatpay/mch_public_cert';

    const XML_PATH_WECHAT_PUBLIC_CERT = 'payment/wechatpay/wechat_public_cert';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * ConfigChange constructor.
     * @param RequestInterface $request
     * @param WriterInterface $configWriter
     */
    public function __construct(
        RequestInterface $request,
        WriterInterface $configWriter
    ) {
        $this->request = $request;
        $this->configWriter = $configWriter;

    }

    public function execute(EventObserver $observer)
    {
        $params = $this->request->getParam('groups');

        $wechatPublicCert = str_replace(["\r"], "", $params['wechatpay']['fields']['wechat_public_cert']['value']);

        $this->configWriter->save(self::XML_PATH_WECHAT_PUBLIC_CERT, $wechatPublicCert);

        return $this;
    }
}