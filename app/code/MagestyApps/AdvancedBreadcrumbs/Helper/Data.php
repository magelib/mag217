<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;

class Data extends AbstractHelper
{
    const XML_PATH_HIDE_HOME_BREADCRUMB = 'magestyapps_advbreadcrumbs/general/hide_home_breadcrumb';
    const XML_PATH_STRUCTURED_DATA = 'magestyapps_advbreadcrumbs/general/structured_data';

    const XML_PATH_FORCE_FULL_PATH = 'magestyapps_advbreadcrumbs/product_pages/force_full_path';
    const XML_PATH_SEARCH_ENABLED = 'magestyapps_advbreadcrumbs/product_pages/search_crumb_enabled';
    const XML_PATH_ONLY_ONE_PATH = 'magestyapps_advbreadcrumbs/product_pages/one_path';
    const XML_PATH_HIDE_DUPLICATES = 'magestyapps_advbreadcrumbs/product_pages/hide_duplicates';
    const XML_PATH_DEFAULT_CATEGORY = 'magestyapps_advbreadcrumbs/product_pages/default_category';

    const PAGE_TYPE_DIRECT_PRODUCT = 'direct_product';
    const PAGE_TYPE_CATEGORY_PRODUCT = 'category_product';
    const PAGE_TYPE_CATEGORY = 'category';
    const PAGE_TYPE_SEARCH = 'search';
    const PAGE_TYPE_CMS_PAGE = 'cms_page';

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * Data constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * Get setting "Hide 'Home' breadcrumb"
     *
     * @return bool
     */
    public function getHideHomeBreadcrumb()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_HIDE_HOME_BREADCRUMB, 'store');
    }

    /**
     * Get setting "structured data enabled"
     *
     * @return bool
     */
    public function isStructuredDataEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_STRUCTURED_DATA, 'store');
    }

    /**
     * Get setting "Force show full path"
     *
     * @return bool
     */
    public function isForceFullPath()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_FORCE_FULL_PATH, 'store');
    }

    /**
     * Get setting "Enable 'Search results' breadcrumbs"
     *
     * @return bool
     */
    public function isSearchEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_SEARCH_ENABLED, 'store');
    }

    /**
     * Get setting "Show only one path"
     *
     * @return bool
     */
    public function showOnlyOnePath()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ONLY_ONE_PATH, 'store');
    }

    /**
     * Get setting "Hide Duplicated Categories"
     *
     * @return bool
     */
    public function hideDuplicates()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_HIDE_DUPLICATES, 'store');
    }

    public function getDefaultCategory()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEFAULT_CATEGORY, 'store');
    }

    /**
     * Get current product object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }

    /**
     * Get current category object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory()
    {
        return $this->coreRegistry->registry('current_category');
    }

    /**
     * get type of current page
     *
     * @return bool|string
     */
    public function getPageType()
    {
        $type = false;
        $refUrl = $this->_httpHeader->getHttpReferer();

        if ($refUrl
            && strpos($refUrl, '/catalogsearch/result/') !== false
            && $this->getProduct()
            && $this->isSearchEnabled()
        ) {
            $type = self::PAGE_TYPE_SEARCH;
        } elseif ($this->getCategory() && $this->getProduct()) {
            $type = self::PAGE_TYPE_CATEGORY_PRODUCT;
        } elseif (!$this->getCategory() && $this->getProduct()) {
            $type =  self::PAGE_TYPE_DIRECT_PRODUCT;
        } elseif ($this->getCategory() && !$this->getProduct()) {
            $type = self::PAGE_TYPE_CATEGORY;
        } elseif ($this->_getRequest()->getFullActionName() == 'cms_page_view') {
            $type = selF::PAGE_TYPE_CMS_PAGE;
        }

        return $type;
    }

    /**
     * get url to search results
     *
     * @return bool|mixed
     */
    public function getSearchUrl()
    {
        $refUrl =  $this->_httpHeader->getHttpReferer();
        if ($refUrl && strpos($refUrl, '/catalogsearch/result/') !== false) {
            return urldecode($refUrl);
        }

        return false;
    }

    /**
     * extract search query from an url
     *
     * @return bool|string
     */
    public function getSearchQuery()
    {
        $searchUrl =  $this->getSearchUrl();
        $query = false;

        $getParams = parse_url($searchUrl, PHP_URL_QUERY);
        $getParams = explode('&', $getParams);
        foreach ($getParams as $param) {
            $paramArr = explode('=', $param);
            if ($paramArr[0] == 'q') {
                $query = $paramArr[1];
                break;
            }
        }

        return $query;
    }
}
