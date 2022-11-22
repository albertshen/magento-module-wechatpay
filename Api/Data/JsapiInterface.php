<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Api\Data;

/**
 * Interface JsapiInterface
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface JsapiInterface
{

    const APPID = 'appId';

    const TIME_STAMP = 'timeStamp';

    const NONCE_STR = 'nonceStr';

    const PACKAGE = 'package';

    const PAY_SIGN = 'paySign';

    /**
     * Get appId
     *
     * @return string
     */
    public function getAppId();

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId);

    /**
     * Get timeStamp
     *
     * @return string
     */
    public function getTimeStamp();

    /**
     * @param string $timeStamp
     * @return $this
     */
    public function setTimeStamp($timeStamp);

    /**
     * Get nonceStr
     *
     * @return string
     */
    public function getNonceStr();

    /**
     * @param string $nonceStr
     * @return $this
     */
    public function setNonceStr($nonceStr);

    /**
     * Get package
     *
     * @return string
     */
    public function getPackage();

    /**
     * @param string $package
     * @return $this
     */
    public function setPackage($package);

    /**
     * Get paySign
     *
     * @return string
     */
    public function getPaySign();

    /**
     * @param string $paySign
     * @return $this
     */
    public function setPaySign($paySign);

}
