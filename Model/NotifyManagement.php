<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Service;

use AlbertMage\WeChatPay\Api\NotifyManagementInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use AlbertMage\Payment\Api\PaymentCaptureInterface;

/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class NotifyManagement implements NotifyManagementInterface
{

    /**
     * @var OrderInterfaceFactory
     */
    protected $orderFactory;

    /**
     * @var PaymentCaptureInterface
     */
    protected $paymentCapture;

    /**
     * @param OrderInterfaceFactory $orderFactory
     * @param PaymentCaptureInterface $paymentCapture
     */
    public function __construct(
        OrderInterfaceFactory $orderFactory,
        PaymentCaptureInterface $paymentCapture
    ) {
        $this->orderFactory = $orderFactory;
        $this->paymentCapture = $paymentCapture;
    }

	/**
	 * {@inheritdoc}
	 */
	public function notify(\AlbertMage\WeChatPay\Api\Data\NotifyInterface $notify)
	{
		
	}

}