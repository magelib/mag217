<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Block;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template\Context;
use MagestyApps\AdvancedBreadcrumbs\Helper\Data;
use MagestyApps\AdvancedBreadcrumbs\Model\PageTypeResolver;

class Breadcrumbs extends \Magento\Theme\Block\Html\Breadcrumbs
{
    /**
     * Template filename
     *
     * @var string
     */
    protected $_template = 'MagestyApps_AdvancedBreadcrumbs::breadcrumbs.phtml';

    /**
     * Breadcrumbs
     *
     * @var null|array
     */
    private $breadcrumbs = null;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var
     */
    private $pageTypeResolver;

    /**
     * Breadcrumbs constructor.
     * @param Context $context
     * @param Data $helper
     * @param PageTypeResolver $pageTypeResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        PageTypeResolver $pageTypeResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->pageTypeResolver = $pageTypeResolver;
    }

    /**
     * Get breadcrumbs for current page
     *
     * @return array
     */
    public function getBreadcrumbs()
    {
        if ($this->breadcrumbs === null) {
            if ($breadcrumbsModel = $this->pageTypeResolver->getBreadcrumbsModel()) {
                $this->breadcrumbs = $breadcrumbsModel->getAllBreadcrumbs();
            }
        }

        return $this->breadcrumbs;
    }

    /**
     * Check whether the structured data markup should be added
     *
     * @return bool
     */
    public function isStructuredDataEnabled()
    {
        return $this->helper->isStructuredDataEnabled();
    }
}
