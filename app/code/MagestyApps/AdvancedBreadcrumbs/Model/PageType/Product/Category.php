<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;

use Magento\Catalog\Model\Category as CategoryModel;
use MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;

class Category extends Product
{
    /**
     * @var CategoryModel|null
     */
    private $category = null;

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        if ($category = $this->getCategory()) {
            foreach ($category->getParentCategories() as $parentCategory) {
                if ($parentCategory->getId() == $category->getId()) {
                    continue;
                }

                $breadcrumbs[] = [
                    'category_id' => $parentCategory->getId(),
                    'title' => $parentCategory->getName(),
                    'link' => $parentCategory->getUrl(),
                    'last' => false
                ];
            }
        }

        return [$breadcrumbs];
    }

    /**
     * @return CategoryModel|null
     */
    public function getCategory()
    {
        if ($this->category === null) {
            $this->category = $this->getRegistryData('current_category');
        }

        return $this->category;
    }
}
