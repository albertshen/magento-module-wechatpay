<?php 

namespace Albert\Magento\WeChatPay\Api;
 
 
interface PaymentInterface {


	/**
	 * @return array
	 */
	public function jsapi();

	/**
	 * @return \Albert\Magento\WeChatPay\Api\Data\QrcodeInterface
	 */
	public function nativeScan();

	/**
	 * @param string $param
	 * @return string
	 */
	public function h5($param);


}