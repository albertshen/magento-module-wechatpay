<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Service;
 
/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Payment implements \AlbertMage\WeChatPay\Api\PaymentInterface
{

    /**
     * @var Request
     */
    protected $request;

    protected $paymentGateway;

    protected $storeManager;
 
    public function __construct(
    	\AlbertMage\WeChatPay\Model\PaymentGateway $paymentGateway,
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
    	$this->paymentGateway = $paymentGateway;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

	/**
	 * {@inheritdoc}
	 */
	public function jsapi()
	{
		$order = $this->request->getBodyParams();
		try {
			$result = $this->paymentGateway->wechat()->mp($order);
			$response = [];
			$response['appId'] = $result->appId;
			$response['timeStamp'] = $result->timeStamp;
			$response['nonceStr'] = $result->nonceStr;
			$response['package'] = $result->package;
			$response['paySign'] = $result->paySign;
			return $response;
		} catch (\Albert\Payment\Exception\InvalidResponseException $e) {
			$error = json_decode($e->exception->getResponse()->getBody()->getContents());
			throw new \Magento\Framework\Exception\LocalizedException(
				__('code: '.$error->code.' '.$error->message),
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
		} catch (\Albert\Payment\Exception\InvalidResponseException $e) {
			$error = json_decode($e->exception->getResponse()->getBody()->getContents());
			throw new \Magento\Framework\Exception\LocalizedException(
				__('code: '.$error->code.' '.$error->message),
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