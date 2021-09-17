<?php

namespace Albert\Magento\WeChatPay\Api\Data;

/**
 * Interface QrcodeInterface
 * @api
 * @since 1.0.1
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
