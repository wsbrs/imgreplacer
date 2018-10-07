<?php

namespace SSA\Imgreplace\Helper;


class Product
{

    /**
     * Checks whether product has existing extenal image for categories
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return null
     */
    public function getProductCategoryImageUrl(\Magento\Catalog\Model\Product $product)
    {
        $categoryImage = $product->getProductCategoryImage();
        if ($categoryImage && $this->validateImageUrl($categoryImage)
        ) {
            return $categoryImage;
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
        $urlPrefix = $product->getProductImageUrlPrefix();
        $files = $product->getProductImageFilenames();
        if (!$urlPrefix || !$files) {
            return [];
        }
        $urlPrefix = rtrim(trim($urlPrefix), '/') . '/';

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