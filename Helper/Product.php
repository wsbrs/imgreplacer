<?php

namespace SSA\Imgreplace\Helper;

class Product
{

    /**
     * @var \SSA\Imgreplace\Model\Config
     */
    private $config;

    /**
     * Product constructor.
     *
     * @param \SSA\Imgreplace\Model\Config $config
     */
    public function __construct(
        \SSA\Imgreplace\Model\Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Checks whether product has existing extenal image for categories
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return null
     */
    public function getProductCategoryImageUrl(\Magento\Catalog\Model\Product $product)
    {
        if (!$this->config->isEnabled()) {
            return null;
        }

        $urlPrefix = $this->config->getCategoryUrlPrefix();
        $categoryImage = trim($product->getProductCategoryImage());
        if (!$urlPrefix || !$categoryImage) {
            return null;
        }

        $categoryImage = ltrim($categoryImage, '/');
        $imageUrl = $urlPrefix . $categoryImage;
        if ($this->validateImageUrl($imageUrl)) {
            return $imageUrl;
        }

        return null;
    }

    /**
     * Returns the list of existing external product images for gallery
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getProductDetailsImages(\Magento\Catalog\Model\Product $product)
    {
        if (!$this->config->isEnabled()) {
            return [];
        }

        $urlPrefix = $this->config->getProductUrlPrefix();
        $files = $product->getProductImageFilenames();
        if (!$urlPrefix || !$files) {
            return [];
        }

        $result = [];

        foreach (explode(',', $files) as $item) {
            $item = ltrim(trim($item), '/');
            $url = $urlPrefix . $item;
            if ($this->validateImageUrl($url)) {
                $result[] = $url;
            }
        }

        return $result;
    }

    /**
     * Checking the URL exists and it's an image
     *
     * @param string $url
     * @return bool
     */
    private function validateImageUrl($url)
    {
        return @getimagesize($url);
    }
}
