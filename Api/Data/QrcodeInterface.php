<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Api\Data;

/**
 * Interface QrcodeInterface
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface QrcodeInterface
{

    const CODE_URL = 'code_url';

    /**
     * Get code url
     *
     * @return string
     */
    public function getCodeUrl();

    /**
     * @param string $code_url
     * @return $this
     */
    public function setCodeUrl($code_url);

}
