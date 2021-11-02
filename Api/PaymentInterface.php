<?php 

namespace AlbertMage\WeChatPay\Api;
 
 
interface PaymentInterface {


	/**
	 * @return array
	 */
	public function jsapi();

	/**
	 * @return \AlbertMage\WeChatPay\Api\Data\QrcodeInterface
	 */
	public function nativeScan();

	/**
	 * @param string $param
	 * @return string
	 */
	public function h5($param);


}