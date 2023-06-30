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

	const MINI_TYPE = 'MINI';

	const H5_TYPE = 'H5';

	/**
	 * @param string $order_no
	 * @return \AlbertMage\WeChatPay\Api\Data\MiniInterface
	 */
	public function mini(string $order_no);

	/**
	 * @param int $order_id
	 * @return \AlbertMage\WeChatPay\Api\Data\JsapiInterface
	 */
	public function jsapi(int $order_id);

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