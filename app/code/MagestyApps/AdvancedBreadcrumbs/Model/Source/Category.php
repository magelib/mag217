<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\Source;

use Magento\Catalog\Model\ResourceModel\Category\Collection;

class Category
{
    /**
     * @var \Magento\Catalog\Model\Category
     */
    private $categoryInstance;

    /**
     * Category constructor.
     * @param \Magento\Catalog\Model\Category $categoryInstance
     */
    public function __construct(
        \Magento\Catalog\Model\Category $categoryInstance
    ) {
        $this->categoryInstance = $categoryInstance;
    }

    /**
     * Get categories tree as an option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @var Collection $categories */
        $categories = $this->categoryInstance->getCollection();

        $categories->addAttributeToSelect('name')
            ->addOrderField('path');

        $options = [];

        $options[] = [
            'label' => __('--- no category ---'),
            'value' => ''
        ];

        foreach ($categories as $category) {
            /** @var \Magento\Catalog\Model\Category $category */
            if (!$category->getName()) {
                continue;
            }

            $padding = $this->_getLabelPadding($category->getLevel());
            $options[] = [
                'label' => $padding . ' ' . $category->getName(),
                'value' => $category->getId()
            ];
        }

        return $options;
    }

    /**
     * Get padding symbols for specific category $level
     *
     * @param integer $level
     * @return string
     */
    protected function _getLabelPadding($level)
    {
        $padding = '';
        for ($i = 0; $i <= ($level - 2); $i++) {
            $padding .= '...';
        }

        return $padding;
    }
}
