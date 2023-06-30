<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Api;
 
/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface NotifyManagementInterface {

	/**
	 * @param \AlbertMage\WeChatPay\Api\Data\NotifyInterface $notify
	 * @return \AlbertMage\WeChatPay\Api\Data\MiniInterface
	 */
	public function notify(\AlbertMage\WeChatPay\Api\Data\NotifyInterface $notify);
}