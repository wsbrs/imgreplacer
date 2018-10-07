<?php
/**
 * Created by PhpStorm.
 * User: brs
 * Date: 06.10.18
 * Time: 19:47
 */

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
    protected $_request;

    /**
     * Current Product
     *
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var string
     */
    protected $_imageId;

    /**
     * List of IDs which are used in the Gallery block
     *
     * @var array
     */
    protected $_productGalleryImageIds = [
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
    )
    {
        $this->_request = $context->getRequest();
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
    public function beforeInit(\Magento\Catalog\Helper\Image $subject,
                               $product, $imageId, $attributes = [])
    {
        $this->_product = $product;
        $this->_imageId = $imageId;

        return [
            $product, $imageId, $attributes
        ];
    }

    public function aroundGetUrl(
        \Magento\Catalog\Helper\Image $subject,
        Callable $proceed
    )
    {
        if (!$this->isCalledFromProductGallery() &&
            ($imageUrl = $this->productHelper->getProductCategoryImageUrl($this->_product))
        ) {
            return $this->_prepareUrl($imageUrl);
        }

        // Calling parent method
        return $proceed();
    }

    /**
     * @return bool
     */
    protected function isCalledFromProductGallery()
    {
        return in_array($this->_imageId, $this->_productGalleryImageIds);
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function _getRequest()
    {
        return $this->_request;
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