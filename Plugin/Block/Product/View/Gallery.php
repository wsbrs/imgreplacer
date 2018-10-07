<?php

namespace SSA\Imgreplace\Plugin\Block\Product\View;

use Magento\Framework\App\Helper\Context;
use SSA\Imgreplace\Helper\Product;

class Gallery
{
    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $_request;

    /**
     * @var Product
     */
    private $productHelper;

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    private $collectionFactory;

    /**
     * Gallery constructor.
     * @param Context $context
     * @param Product $productHelper
     * @param \Magento\Framework\Data\CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Product $productHelper,
        \Magento\Framework\Data\CollectionFactory $collectionFactory
    )
    {
        $this->_request = $context->getRequest();
        $this->productHelper = $productHelper;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Checks whether product has at least one alternative image.
     *  If not - original method is called.
     *
     * @param \Magento\Catalog\Block\Product\View\Gallery $subject
     * @param callable $proceed
     * @return \Magento\Framework\Data\Collection
     * @throws \Exception
     */
    public function aroundGetGalleryImages(\Magento\Catalog\Block\Product\View\Gallery $subject, Callable $proceed)
    {
        $productAltImages = $this->productHelper->getProductDetailsImages($subject->getProduct());
        if (empty($productAltImages)) {
            // No alternative images - proceeding to the default gallery
            return $proceed();
        }

        $images = $this->collectionFactory->create();
        foreach ($productAltImages as $productAltImage) {
            $image = [];
            $image['url'] = $productAltImage;
            $image['id'] = uniqid('gallery_' . $subject->getProduct()->getId() . '_');
            $image['path'] = $productAltImage;
            $image['small_image_url'] = $productAltImage;
            $image['medium_image_url'] = $productAltImage;
            $image['large_image_url'] = $productAltImage;
            $images->addItem(new \Magento\Framework\DataObject($image));
        }

        return $images;
    }
}