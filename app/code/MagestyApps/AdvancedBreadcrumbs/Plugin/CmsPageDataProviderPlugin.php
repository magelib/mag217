<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Plugin;

use Magento\Cms\Model\Page\DataProvider;

class CmsPageDataProviderPlugin
{
    /**
     * @param DataProvider $subject
     * @param $loadedItems
     * @return mixed
     */
    public function afterGetData(DataProvider $subject, $loadedItems)
    {
        if ($loadedItems) {
            foreach ($loadedItems as $k => $item) {
                if (isset($item['custom_breadcrumbs']) && !is_array($item['custom_breadcrumbs'])) {
                    $loadedItems[$k]['custom_breadcrumbs'] = json_decode($item['custom_breadcrumbs'], true);
                }
            }
        }

        return $loadedItems;
    }
}
