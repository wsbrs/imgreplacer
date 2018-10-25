<?php

namespace SSA\Imgreplace\Plugin\Block\Product\View;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObjectFactory;
use SSA\Imgreplace\Helper\Product;

class Gallery
{
    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var Product
     */
    private $productHelper;

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * Gallery constructor.
     * @param Context $context
     * @param Product $productHelper
     * @param \Magento\Framework\Data\CollectionFactory $collectionFactory
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        Context $context,
        Product $productHelper,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        DataObjectFactory $dataObjectFactory
    ) {
        $this->request = $context->getRequest();
        $this->productHelper = $productHelper;
        $this->collectionFactory = $collectionFactory;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * Checks whether product has at least one alternative image.
     *  If not - original method is called.
     *
     * @param \Magento\Catalog\Block\Product\View\Gallery $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Data\Collection
     * @throws \Exception
     */
    public function aroundGetGalleryImages(\Magento\Catalog\Block\Product\View\Gallery $subject, \Closure $proceed)
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
            $images->addItem($this->dataObjectFactory->create(['data' => $image]));
        }

        return $images;
    }
}
