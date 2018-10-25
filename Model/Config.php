<?php

namespace SSA\Imgreplace\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{

    const XML_STATUS = 'ssa_imgreplace/general/status';
    const XML_URL_PREFIX_CATEGORY = 'ssa_imgreplace/general/url_prefix_category';
    const XML_URL_PREFIX_PRODUCT = 'ssa_imgreplace/general/url_prefix_product';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        ScopeConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @param null|int $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return boolval($this->config->getValue(
            self::XML_STATUS,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }

    /**
     * @param $url
     * @return string
     */
    private function preparePrefix($url)
    {
        return rtrim(trim($url), '/') . '/';
    }

    /**
     * @param null|int $store
     * @return bool
     */
    public function getCategoryUrlPrefix($store = null)
    {
        return $this->preparePrefix($this->config->getValue(
            self::XML_URL_PREFIX_CATEGORY,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }

    /**
     * @param null|int $store
     * @return bool
     */
    public function getProductUrlPrefix($store = null)
    {
        return $this->preparePrefix($this->config->getValue(
            self::XML_URL_PREFIX_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }
}
