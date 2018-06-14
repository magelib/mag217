<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\UrlInterface;
use Magento\Theme\Block\Html\Breadcrumbs;
use MagestyApps\AdvancedBreadcrumbs\Helper\Additional;
use MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class AdditionalPagesBreadcrumbs implements ObserverInterface
{
    /** @var Additional $helper */
    private $helper;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * AdditionalPagesBreadcrumbs constructor.
     * @param Additional $helper
     * @param Data $dataHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Additional $helper,
        Data $dataHelper,
        UrlInterface $urlBuilder
    ) {
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Element\AbstractBlock $block */
        $block = $observer->getEvent()->getBlock();
        $crumbs = $this->helper->getCrumbs($block->getNameInLayout());

        if (!$crumbs) {
            return $this;
        }

        /** @var Breadcrumbs $breadcrumbsBlock */
        $breadcrumbsBlock = $block->getLayout()->getBlock('breadcrumbs');
        if (!$breadcrumbsBlock) {
            return $this;
        }

        //Add homepage breadcrumb
        $breadcrumbsBlock->addCrumb('home', [
            'label' => __('Home'),
            'title' => __('Go to Home Page'),
            'link'  => $this->urlBuilder->getBaseUrl()
        ]);

        //Add custom breadcrumbs
        foreach ($crumbs as $crumb) {
            $breadcrumbsBlock->addCrumb($crumb['code'], [
                'label' => isset($crumb['title']) ? $crumb['title'] : '',
                'title' => isset($crumb['title']) ? $crumb['title'] : '',
                'link'  => isset($crumb['url']) ? $crumb['url'] : false,
            ]);
        }

        return $this;
    }
}
