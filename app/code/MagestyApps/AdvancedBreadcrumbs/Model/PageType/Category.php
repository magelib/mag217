<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType;

use Magento\Catalog\Model\Category as CategoryModel;

class Category extends AbstractType
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
            /** @var CategoryModel $parentCategory */
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

    public function getLastBreadcrumbTitle()
    {
        $title = parent::getLastBreadcrumbTitle();
        if ($this->getCategory()) {
            $title = $this->getCategory()->getName();
        }

        return $title;
    }

    /**
     * @return CategoryModel|null
     */
    private function getCategory()
    {
        if ($this->category === null) {
            $this->category = $this->getRegistryData('current_category');
        }

        return $this->category;
    }
}
