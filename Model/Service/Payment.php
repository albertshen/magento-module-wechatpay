<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Service;

use Magento\Framework\Webapi\Rest\Request;
use AlbertMage\WeChatPay\Model\PaymentGateway;
use AlbertMage\WeChatPay\Model\Data\JsapiFactory;
use AlbertMage\WeChatPay\Model\Data\QrcodeFactory;
use Magento\Sales\Api\Data\OrderInterface;
use AlbertMage\Customer\Model\ResourceModel\SocialAccountRepository;

/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Payment implements \AlbertMage\WeChatPay\Api\PaymentInterface
{

    /**
     * @var Request
     */
    protected $request;

 	/**
	 * @var PaymentGateway
	 */
    protected $paymentGateway;

 	/**
	 * @var JsapiFactory
	 */
    protected $jsapiFactory;

 	/**
	 * @var QrcodeFactory
	 */
    protected $qrcodeFactory;

 	/**
	 * @var OrderInterface
	 */
    protected $orderInterface;

    /**
     * @var SocialAccountRepository
     */
    protected $socialAccountRepository;

	/**
	 * @param Request $request
	 * @param PaymentGateway $paymentGateway
	 * @param JsapiFactory $jsapiFactory
	 * @param QrcodeFactory $qrcodeFactory
	 * @param OrderInterface $orderInterface
	 * @param SocialAccountRepository $socialAccountRepository
	 */
    public function __construct(
        Request $request,
    	PaymentGateway $paymentGateway,
    	JsapiFactory $jsapiFactory,
    	QrcodeFactory $qrcodeFactory,
    	OrderInterface $orderInterface,
    	SocialAccountRepository $socialAccountRepository

    ) {
        $this->request = $request;
    	$this->paymentGateway = $paymentGateway;
        $this->jsapiFactory = $jsapiFactory;
        $this->qrcodeFactory = $qrcodeFactory;
        $this->orderInterface = $orderInterface;
        $this->socialAccountRepository = $socialAccountRepository;
    }

	/**
	 * {@inheritdoc}
	 */
	public function mini($orderId)
	{

		try {

			//$order = $this->orderInterface->loadByIncrementId($order_no);
			$order = $this->orderInterface->load($orderId);

			$weappUser = $this->socialAccountRepository->getMiniprogramAccount($order->getCustomerId());

			$orderData = [
			    'out_trade_no' => self::MINI_TYPE.'-'.$order->getIncrementId(),
			    'description' => $order->getIncrementId(),
			    'amount' => [
			        'total' => $order->getGrandTotal() * 1,
			    ],
			    'payer' => [
			        'openid' => $weappUser->getOpenId(),
			    ],
			];

			$result = $this->paymentGateway->wechat()->mini($orderData);

			$jsapi = $this->jsapiFactory->create();
			$jsapi->setAppId($result->appId);
			$jsapi->setTimeStamp($result->timeStamp);
			$jsapi->setNonceStr($result->nonceStr);
			$jsapi->setPackage($result->package);
			$jsapi->setSignType($result->signType);
			$jsapi->setPaySign($result->paySign);

			return $jsapi;

		} catch (\Yansongda\Pay\Exception\InvalidResponseException $e) {
			var_dump($e);exit;
			$response = json_decode($e->response['body'], true);
			throw new \Magento\Framework\Exception\LocalizedException(
				__('code: '.$response['code'].' '.$response['message']),
				null,
				$e->getCode()
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function jsapi(int $orderId)
	{
		//$order = $this->request->getBodyParams();
		try {
			$orderObject = $this->orderInterface->create()->load($orderId);

			$order = [
			    'out_trade_no' => time().'', //需为 string 类型
			    'description' => 'subject-测试',
			    'amount' => [
			        'total' => 1,
			    ],
			    'payer' => [
			        'openid' => 'onkVf1FjWS5SBxxxxxxxx',
			    ],
			];

			$result = $this->paymentGateway->wechat()->mp($order);

			$jsapi = $this->jsapiFactory->create();
			$jsapi->setAppId($result->appId);
			$jsapi->setTimeStamp($result->timeStamp);
			$jsapi->setNonceStr($result->nonceStr);
			$jsapi->setPackage($result->package);
			$jsapi->setPaySign($result->paySign);
			return $jsapi;
		} catch (\Yansongda\Pay\Exception\InvalidResponseException $e) {
			throw new \Magento\Framework\Exception\LocalizedException(
				__('code: '.$e->response['code'].' '.$e->response['message']),
				null,
				$e->getCode()
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function nativeScan()
	{
		$order = $this->request->getBodyParams();
		try {
			//$store = $this->storeManager->getStore()->getCode();
			$result = $this->paymentGateway->wechat()->scan($order);
			$qrcode = \Magento\Framework\App\ObjectManager::getInstance()->get(\Albert\Magento\WeChatPay\Model\Data\Qrcode::class);
			$qrcode->setCodeUrl($result->code_url);
			return $qrcode;
		} catch (\Yansongda\Pay\Exception\InvalidResponseException $e) {
			throw new \Magento\Framework\Exception\LocalizedException(
				__('code: '.$e->response['code'].' '.$e->response['message']),
				null,
				$e->getCode()
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function refund()
	{

		$order = $this->request->getBodyParams();
		try {
			$result = $this->paymentGateway->wechat()->refund($order);
			// $qrcode = \Magento\Framework\App\ObjectManager::getInstance()->get(\Albert\Magento\WeChatPay\Model\Data\Refund::class);
			// $qrcode->setCodeUrl($result->code_url);
			// return $qrcode;
		} catch (\Exception $e) {
			throw new \Magento\Framework\Exception\LocalizedException(
            	__('Totals calculation is not applicable to empty cart'),
            	null,
            	4001
        	);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function h5($param)
	{
		var_dump($param);exit;
		// $post = $this->request->getBodyParams();
		// //$store = $this->storeManager->getStore()->getCode();
		// //var_dump($post, $store);exit;
		// $this->restResponse->setStatus(true);
		// $this->restResponse->setMessage('');
		// $this->restResponse->setData($post);
		// return $this->restResponse;
	}

}