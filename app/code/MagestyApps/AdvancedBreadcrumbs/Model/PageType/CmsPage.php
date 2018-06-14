<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType;

use Magento\Framework\App\Request\Http;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class CmsPage extends AbstractType
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var PageInterface|null
     */
    private $cmsPage = null;

    /**
     * CmsPage constructor.
     * @param Http $request
     * @param UrlInterface $urlBuilder
     * @param Registry $registry
     * @param Data $helper
     * @param StoreManagerInterface $storeManager
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        Http $request,
        UrlInterface $urlBuilder,
        Registry $registry,
        Data $helper,
        StoreManagerInterface $storeManager,
        PageRepositoryInterface $pageRepository
    ) {
        parent::__construct($request, $urlBuilder, $registry, $storeManager, $helper);
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        $cmsPage = $this->getCmsPage();
        if ($customBreadcrumbs = $cmsPage->getCustomBreadcrumbs()) {
            foreach ($customBreadcrumbs as $customBreadcrumb) {
                if (!isset($customBreadcrumb['link_title'])) {
                    continue;
                }

                $link = '';
                if (isset($customBreadcrumb['link_url'])) {
                    $link = $customBreadcrumb['link_url'];
                    if (strpos($link, 'http://') === false
                        && strpos($link, 'https://') === false
                    ) {
                        $link = $this->getUrlBuilder()->getBaseUrl() . ltrim($link, '/');
                    }
                }

                $breadcrumbs[] = [
                        'category_id' => -1,
                        'title' =>  $customBreadcrumb['link_title'],
                        'link' => $link,
                        'last' => false
                ];
            }
        }

        return [$breadcrumbs];
    }

    /**
     * @return null|string
     */
    public function getLastBreadcrumbTitle()
    {
        return $this->getCmsPage()->getTitle();
    }

    /**
     * @return PageInterface|null
     */
    private function getCmsPage()
    {
        if ($this->cmsPage == null) {
            $pageId = $this->getRequest()->getParam('page_id');
            $this->cmsPage = $this->pageRepository->getById($pageId);
        }

        return $this->cmsPage;
    }
}
