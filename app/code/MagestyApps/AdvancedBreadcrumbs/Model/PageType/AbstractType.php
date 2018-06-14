<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class AbstractType
{
    /**
     * @var Http
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Data
     */
    private $helper;

    /**
     * AbstractType constructor.
     * @param Http $request
     * @param UrlInterface $urlBuilder
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     */
    public function __construct(
        Http $request,
        UrlInterface $urlBuilder,
        Registry $registry,
        StoreManagerInterface $storeManager,
        Data $helper
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function getAllBreadcrumbs()
    {
        $breadcrumbs = $this->getBreadcrumbs();

        // Add first (Home) breadcrumb
        if (!$this->helper->getHideHomeBreadcrumb()) {
            $homeBreadcrumb = [
                'category_id' => 0,
                'title' => __('Home'),
                'link' => $this->urlBuilder->getBaseUrl(),
                'last' => false
            ];

            foreach ($breadcrumbs as $k => $breadcrumb) {
                array_unshift($breadcrumbs[$k], $homeBreadcrumb);
            }
        }

        // Add last breadcrumb
        if (count($breadcrumbs) == 1) {
            $breadcrumbs[0][] = [
                'title' => $this->getLastBreadcrumbTitle(),
                'last' => true
            ];
        }

        return $breadcrumbs;
    }

    /**
     * @return Http
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->urlBuilder;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getRegistryData($key)
    {
        return $this->registry->registry($key);
    }

    /**
     * @return Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * Get page breadcrumbs here.
     * This method should be overwritten in a Page Type model
     *
     * @return array
     */
    public function getBreadcrumbs()
    {
        return [];
    }

    /**
     * Get last breadcrumb title here.
     * This method should be overwritten in a Page Type model
     *
     * @return string
     */
    public function getLastBreadcrumbTitle()
    {
        return '';
    }
}
