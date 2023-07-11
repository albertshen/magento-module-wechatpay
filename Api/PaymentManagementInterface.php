<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Api;
 
/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface PaymentManagementInterface {

	const GATEWAY = 'wechatpay';

	const MINI_TYPE = 'MINI';

	const H5_TYPE = 'H5';

	/**
	 * @param string $orderId
	 * @return \AlbertMage\WeChatPay\Api\Data\MiniInterface
	 */
	public function mini($orderId);

	/**
	 * @param int $orderId
	 * @return \AlbertMage\WeChatPay\Api\Data\JsapiInterface
	 */
	public function jsapi(int $orderId);

	/**
	 * @return \AlbertMage\WeChatPay\Api\Data\QrcodeInterface
	 */
	public function nativeScan();

	/**
	 * @param string $param
	 * @return string
	 */
	public function h5($param);

	/**
	 * @return bool
	 */
	public function notify();


}