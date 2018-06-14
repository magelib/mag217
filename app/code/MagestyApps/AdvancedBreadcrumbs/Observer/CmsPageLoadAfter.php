<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CmsPageLoadAfter implements ObserverInterface
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $page = $observer->getEvent()->getDataObject();
        $customBreadcrumbs = $page->getCustomBreadcrumbs();
        if (!is_array($customBreadcrumbs)) {
            $customBreadcrumbs = json_decode($customBreadcrumbs, true);
            $page->setCustomBreadcrumbs($customBreadcrumbs);
        }
    }
}
