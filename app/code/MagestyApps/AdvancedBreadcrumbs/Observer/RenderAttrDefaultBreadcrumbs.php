<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Context;
use Magento\Catalog\Block\Adminhtml\Form\Renderer\Fieldset\Element;
use MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product\Direct;

class RenderAttrDefaultBreadcrumbs implements ObserverInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var Direct
     */
    private $breadcrumbsModel;

    /**
     * RenderAttrDefaultBreadcrumbs constructor.
     * @param Context $context
     * @param Direct $breadcrumbsModel
     */
    public function __construct(
        Context $context,
        Direct $breadcrumbsModel
    ) {
        $this->context = $context;
        $this->breadcrumbsModel = $breadcrumbsModel;
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        if ($this->context->getFullActionName() != 'catalog_product_edit'
            || !($block instanceof Element)
            || !$block->getAttribute()
            || $block->getAttributeCode() != 'default_breadcrumbs'
        ) {
            return $this;
        }

        $availablePaths = [
            '' => __('Detect Automatically')
        ];

        $categoryIds = $block->getDataObject()->getCategoryIds();
        foreach ($categoryIds as $categoryId) {

            $pathStr = ['Home'];

            $category = $this->breadcrumbsModel->getCategoryModel($categoryId);
            $path = explode(',', $category->getPathInStore());
            krsort($path);

            foreach ($path as $pathCatId) {
                if (!$pathCatId) {
                    continue;
                }

                $pathCat = $this->breadcrumbsModel->getCategoryModel($pathCatId);
                if ($pathCat->getLevel() < 2) {
                    continue;
                }

                $pathStr[] = $pathCat->getName();
            }

            $availablePaths[$categoryId] = implode(' / ', $pathStr);

        }

        $block->getElement()->setData('values', $availablePaths);

        return $this;
    }
}
