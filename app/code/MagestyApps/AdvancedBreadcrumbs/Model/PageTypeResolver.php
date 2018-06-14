<?php

namespace MagestyApps\AdvancedBreadcrumbs\Model;

use MagestyApps\AdvancedBreadcrumbs\Helper\Data;
use Magento\Framework\ObjectManagerInterface;

class PageTypeResolver
{
    private $objectManager;

    private $helper;

    public function __construct(
        ObjectManagerInterface $objectManager,
        Data $helper
    ) {
        $this->objectManager = $objectManager;
        $this->helper = $helper;
    }

    public function getBreadcrumbsModel()
    {
        $pageType = $this->helper->getPageType();

        if (($pageType == Data::PAGE_TYPE_CATEGORY_PRODUCT && $this->helper->isForceFullPath())
            || ($pageType == Data::PAGE_TYPE_CATEGORY_PRODUCT && !$this->helper->showOnlyOnePath())
            || ($pageType == Data::PAGE_TYPE_SEARCH && !$this->helper->isSearchEnabled())
        ) {
            $pageType = Data::PAGE_TYPE_DIRECT_PRODUCT;
        }

        switch ($pageType) {
            case Data::PAGE_TYPE_CATEGORY:
                $className = 'Category';
                break;
            case Data::PAGE_TYPE_CATEGORY_PRODUCT:
                $className = 'Product\\Category';
                break;
            case Data::PAGE_TYPE_SEARCH:
                $className = 'Product\\Search';
                break;
            case Data::PAGE_TYPE_DIRECT_PRODUCT:
                $className = 'Product\\Direct';
                break;
            case Data::PAGE_TYPE_CMS_PAGE:
                $className = 'CmsPage';
                break;
            default:
                $className = false;
                break;
        }

        if (!$className) {
            return false;
        }

        return $this->objectManager->get(
            'MagestyApps\\AdvancedBreadcrumbs\\Model\\PageType\\' . $className
        );
    }
}
