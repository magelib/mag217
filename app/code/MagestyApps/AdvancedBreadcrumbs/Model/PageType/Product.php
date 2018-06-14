<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType;

class Product extends AbstractType
{
    /**
     * @var \Magento\Catalog\Model\Product|null
     */
    private $product = null;

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->getRegistryData('current_product');
        }

        return $this->product;
    }

    /**
     * @return string
     */
    public function getLastBreadcrumbTitle()
    {
        $title = parent::getLastBreadcrumbTitle();
        if ($this->getProduct()) {
            $title = $this->getProduct()->getName();
        }

        return $title;
    }
}
