<?php
/**
 * Copyright © 2021 PHPDigital. All rights reserved.
 * See more information at http://www.phpdigital.com
 */
namespace AlbertMage\WeChatPay\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Model\Order\Payment\Transaction;
use Albert\Payment\Pay;

/**
 * Class Test
 */
class Notify extends Action
{

   protected $invoiceService;

   protected $transactionFactory;

   protected $messageManager;

   protected $invoiceSender;

   protected $orderFactory;

   protected $transactionBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder
    ) {
        $this->orderFactory = $orderFactory;
        $this->invoiceService = $invoiceService;
        $this->transactionFactory = $transactionFactory;
        $this->messageManager = $messageManager;
        $this->invoiceSender = $invoiceSender;
        $this->transactionBuilder = $transactionBuilder;
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
        $order = $this->orderFactory->create()->loadByIncrementId($paymentData['resource']['ciphertext']['out_trade_no']);

        $this->addTransactionToOrder($order, $paymentData);

        $this->generateInvoice($order);
    }

    /**
     * Create Invoice Based on Order Object
     * @param \Magento\Sales\Model\Order $order
     * @return $this
     */
    public function generateInvoice($order)
    {
        try {
            // $order = $this->orderRepository->get($orderId);
            if (!$order->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('The order no longer exists.'));
            }
            if(!$order->canInvoice()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                        __('The order does not allow an invoice to be created.')
                    );
            }

            $invoice = $this->invoiceService->prepareInvoice($order);
            if (!$invoice) {
                throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t save the invoice right now.'));
            }
            if (!$invoice->getTotalQty()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('You can\'t create an invoice without products.')
                );
            }
            $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $invoice->getOrder()->setCustomerNoteNotify(false);
            $invoice->getOrder()->setIsInProcess(true);
            $order->addStatusHistoryComment('Automatically INVOICED', false);
            $transactionSave = $this->transactionFactory->create()->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();

            // send invoice emails, If you want to stop mail disable below try/catch code
            // try {
            //     $this->invoiceSender->send($invoice);
            // } catch (\Exception $e) {
            //     $this->messageManager->addError(__('We can\'t send the invoice email right now.'));
            // }
        } catch (\Exception $e) {
            
            $this->messageManager->addError($e->getMessage());
        }

        return $invoice;
    }

    public function addTransactionToOrder($order, $paymentData = array()) {
        try {
            // Prepare payment object
            $payment = $order->getPayment();
            $payment->setMethod('wechatpayment'); 
            $payment->setLastTransId($paymentData['resource']['ciphertext']['transaction_id']);
            $payment->setTransactionId($paymentData['resource']['ciphertext']['transaction_id']);
            $payment->setAdditionalInformation([Transaction::RAW_DETAILS => (array) $paymentData]);

            // Formatted price
            $formatedPrice = $order->getBaseCurrency()->formatTxt($order->getGrandTotal());
 
            // Prepare transaction
            $transaction = $this->transactionBuilder->setPayment($payment)
                            ->setOrder($order)
                            ->setTransactionId($paymentData['resource']['ciphertext']['transaction_id'])
                            ->setAdditionalInformation([Transaction::RAW_DETAILS => (array) $paymentData])
                            ->setFailSafe(true)
                            ->build(Transaction::TYPE_CAPTURE);

            // Add transaction to payment
            $payment->addTransactionCommentsToOrder($transaction, __('The authorized amount is %1.', $formatedPrice));
            $payment->setParentTransactionId(null);

            // Save payment, transaction and order
            $payment->save();
            $order->save();
            $transaction->save();
 
            return  $transaction->getTransactionId();

        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }
    }
}
