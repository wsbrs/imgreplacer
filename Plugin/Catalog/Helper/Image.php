<?php

namespace SSA\Imgreplace\Plugin\Catalog\Helper;

use Magento\Framework\App\Helper\Context;
use SSA\Imgreplace\Helper\Product;

class Image
{

    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Current Product
     *
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var string
     */
    private $imageId;

    /**
     * List of IDs which are used in the Gallery block
     *
     * @var array
     */
    private $productGalleryImageIds = [
        'product_page_image_small',
        'product_page_image_medium_no_frame',
        'product_page_image_large_no_frame',
    ];

    /**
     * @var Product
     */
    private $productHelper;

    public function __construct(
        Context $context,
        Product $productHelper
    ) {
        $this->request = $context->getRequest();
        $this->productHelper = $productHelper;
    }

    /**
     * Obtains current product from the original helper
     *
     * @param \Magento\Catalog\Helper\Image $subject
     * @param $product
     * @param $imageId
     * @param array $attributes
     * @return array
     */
    public function beforeInit(
        \Magento\Catalog\Helper\Image $subject,
        $product,
        $imageId,
        $attributes = []
    ) {
        $this->product = $product;
        $this->imageId = $imageId;

        return [
            $product, $imageId, $attributes
        ];
    }

    public function aroundGetUrl(
        \Magento\Catalog\Helper\Image $subject,
        \Closure $proceed
    ) {
        if (!$this->isCalledFromProductGallery() &&
            ($imageUrl = $this->productHelper->getProductCategoryImageUrl($this->product))
        ) {
            return $this->_prepareUrl($imageUrl);
        }

        // Calling parent method
        return $proceed();
    }

    /**
     * @return bool
     */
    private function isCalledFromProductGallery()
    {
        return in_array($this->imageId, $this->productGalleryImageIds);
    }

    /**
     * @param $url
     * @return null|string|string[]
     */
    private function _prepareUrl($url)
    {
        return preg_replace('/^https?:\/\//i', '//', $url);
    }
}
