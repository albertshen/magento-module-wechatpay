<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Data;

use Magento\Framework\DataObject;
use AlbertMage\WeChatPay\Api\Data\QrcodeInterface;

/**
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Qrcode extends DataObject implements QrcodeInterface
{

    /**
     * {@inheritdoc}
     */
	public function getCodeUrl()
	{
		return $this->getData(self::CODE_URL);
	}

    /**
     * {@inheritdoc}
     */
	public function setCodeUrl($code_url)
	{
		return $this->setData(self::CODE_URL, $code_url);
	}

}