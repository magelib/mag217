<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CmsPageSaveBefore implements ObserverInterface
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $page = $observer->getEvent()->getDataObject();
        $customBreadcrumbs = $page->getCustomBreadcrumbs();

        if (is_array($customBreadcrumbs)) {
            $breadcrumbs = [];
            foreach ($customBreadcrumbs as $k => $breadcrumb) {
                if ((isset($breadcrumb['delete']) && $breadcrumb['delete'] == "true")
                    || empty($breadcrumb['link_title'])
                ) {
                    continue;
                }

                $breadcrumbs[] = [
                    'link_title' => $breadcrumb['link_title'],
                    'link_url' => $breadcrumb['link_url'],
                    'sort_order' => $breadcrumb['sort_order'],
                ];
            }

            usort($breadcrumbs, [get_class($this), 'sortByOrder']);

            $breadcrumbs = json_encode($breadcrumbs);
            $page->setCustomBreadcrumbs($breadcrumbs);
        }
    }

    /**
     * Sort array by 'sort_order' value in each element
     *
     * @param $arrayA
     * @param $arrayB
     * @return int
     */
    public static function sortByOrder($arrayA, $arrayB)
    {
        $a = $arrayA['sort_order'];
        $b = $arrayB['sort_order'];

        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
