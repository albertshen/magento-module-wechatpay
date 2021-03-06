<?php 

namespace AlbertMage\WeChatPay\Model\Data;
 
class Qrcode extends \Magento\Framework\Model\AbstractExtensibleModel implements \AlbertMage\WeChatPay\Api\Data\QrcodeInterface
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