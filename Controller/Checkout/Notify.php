<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Controller\Checkout;

use Magento\Framework\App\Action\Action;

/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Notify extends Action
{

    /**
     * @var \AlbertMage\Payment\Api\PaymentCaptureInterface
     */
    protected $paymentCapture;

    /**
     * @param \AlbertMage\Payment\Api\PaymentCaptureInterface $paymentCapture
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \AlbertMage\Payment\Api\PaymentCaptureInterface $paymentCapture
    ) {
        $this->paymentCapture = $paymentCapture;
        parent::__construct($context);
    }

    public function execute()
    {
        $str = '{"id":"5a6d1746-6d72-5cfb-b9ae-16c6fbdd770d","create_time":"2021-08-11T23:40:08+08:00","resource_type":"encrypt-resource","event_type":"TRANSACTION.SUCCESS","summary":"支付成功","resource":{"original_type":"transaction","algorithm":"AEAD_AES_256_GCM","ciphertext":{"mchid":"1525330731","appid":"wx14e621bda5433838","out_trade_no":"PD000002156","transaction_id":"4200001156202108116220267523","trade_type":"JSAPI","trade_state":"SUCCESS","trade_state_desc":"支付成功","bank_type":"BOC_CREDIT","attach":"","success_time":"2021-08-11T23:40:08+08:00","payer":{"openid":"oQj-F0g0rAYzORurrCsebDwD74cM"},"amount":{"total":1,"payer_total":1,"currency":"CNY","payer_currency":"CNY"}},"associated_data":"transaction","nonce":"Fmx78BIUZ5LX"}}';

        $paymentData = json_decode($str, true);

        $this->capturePayment($paymentData);
    }

    public function capturePayment($paymentData)
    {
        $orderNo = $paymentData['resource']['ciphertext']['out_trade_no'];
        $transactionId = $paymentData['resource']['ciphertext']['transaction_id'];

        $order = $this->orderFactory->create()->loadByIncrementId($orderNo);

        $this->paymentCapture
            ->setOrder($order)
            ->setPaymenGateway(\AlbertMage\WeChatPay\Api\PaymentInterface::GATEWAY)
            ->setTransactionId($transactionId)
            ->setPaymentRawData((array) $paymentData)
            ->capture();
    }
}
