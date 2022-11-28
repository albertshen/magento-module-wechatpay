<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Api;
 
/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface PaymentInterface {

	const GATEWAY = 'wechatpay';

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