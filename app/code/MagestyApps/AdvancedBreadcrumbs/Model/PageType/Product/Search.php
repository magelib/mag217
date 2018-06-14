<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;

use MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;

class Search extends Product
{
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $searchQuery = $this->getHelper()->getSearchQuery();
        $searchUrl = $this->getHelper()->getSearchUrl();

        $breadcrumbs = [
            [
                'category_id' => -1,
                'title' =>  __("Search results for: '%1'", $searchQuery),
                'link' => $searchUrl,
                'last' => false
            ]
        ];

        return [$breadcrumbs];
    }
}
