<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\WeChatPay\Model\Config\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Certificate extends \Magento\Config\Model\Config\Backend\File
{

    /**
     * Save uploaded file before saving config value
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $configWriter = $objectManager->create(\Magento\Framework\App\Config\Storage\WriterInterface::class);

        $fieldConfig = $this->getFieldConfig();

        $file = $this->getFileData();

        if (isset($file['tmp_name'])) {
            if ('mch_secret_cert_path' == $fieldConfig['id']) {  
                $mchSecretCert = str_replace(["-----BEGIN PRIVATE KEY-----", "-----END PRIVATE KEY-----", "\r\n", "\n"], "", file_get_contents($file['tmp_name']));
                $configWriter->save(\AlbertMage\WeChatPay\Observer\PaymentConfig::XML_PATH_MCH_SECRET_CERT, $mchSecretCert);
            }
            if ('mch_public_cert_path' == $fieldConfig['id']) {  
                $mchPublicCert = str_replace(["\r"], "", file_get_contents($file['tmp_name']));
                $configWriter->save(\AlbertMage\WeChatPay\Observer\PaymentConfig::XML_PATH_MCH_PUBLIC_CERT, $mchPublicCert);
            }
        }

        $this->setValue('');

        return $this;
    }

    /**
     * Prepend path with scope info
     *
     * E.g. 'stores/2/path' , 'websites/3/path', 'default/path'
     *
     * @param string $path
     * @return string
     */
    protected function _prependScopeInfo($path)
    {
        $fieldConfig = $this->getFieldConfig();
        $uploadDir = $fieldConfig['upload_dir']['value'];
        $scopeInfo = $uploadDir . '/' . $this->getScope();
        if (ScopeConfigInterface::SCOPE_TYPE_DEFAULT != $this->getScope()) {
            $scopeInfo .= '/' . $this->getScopeId();
        }
        return $scopeInfo . '/' . $path;
    }

    /**
     * Retrieve upload directory path
     *
     * @param string $uploadDir
     * @return string
     * @since 100.1.0
     */
    protected function getUploadDirPath($uploadDir)
    {
        $this->_mediaDirectory = $this->_filesystem->getDirectoryWrite(DirectoryList::ROOT);
        return $this->_mediaDirectory->getAbsolutePath($uploadDir);
    }


    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['pem', 'crt'];
    }
}