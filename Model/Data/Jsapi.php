<?php 
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Data;

use Magento\Framework\DataObject;
use AlbertMage\WeChatPay\Api\Data\JsapiInterface;

/**
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Jsapi extends DataObject implements JsapiInterface
{

    /**
     * {@inheritdoc}
     */
    public function getAppId()
    {
    	return $this->getData(self::APPID);
    }

    /**
     * {@inheritdoc}
     */
    public function setAppId($appId)
    {
    	return $this->setData(self::APPID, $appId);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeStamp()
    {
    	return $this->getData(self::TIME_STAMP);
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeStamp($timeStamp)
    {
    	return $this->setData(self::TIME_STAMP, $timeStamp);
    }

    /**
     * {@inheritdoc}
     */
    public function getNonceStr()
    {
    	return $this->getData(self::NONCE_STR);
    }

    /**
     * {@inheritdoc}
     */
    public function setNonceStr($nonceStr)
    {
    	return $this->setData(self::NONCE_STR, $nonceStr);
    }

    /**
     * {@inheritdoc}
     */
    public function getPackage()
    {
    	return $this->getData(self::PACKAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPackage($package)
    {
    	return $this->setData(self::PACKAGE, $package);
    }

    /**
     * {@inheritdoc}
     */
    public function getSignType()
    {
        return $this->getData(self::SIGN_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setSignType($signType)
    {
        return $this->setData(self::SIGN_TYPE, $signType);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaySign()
    {
		return $this->getData(self::PAY_SIGN);
    }

    /**
     * {@inheritdoc}
     */
    public function setPaySign($paySign)
    {
    	return $this->setData(self::PAY_SIGN, $paySign);
    }

}
