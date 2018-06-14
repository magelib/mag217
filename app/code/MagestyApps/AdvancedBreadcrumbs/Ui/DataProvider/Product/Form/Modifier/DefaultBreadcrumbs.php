<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product\Direct;

class DefaultBreadcrumbs extends AbstractModifier
{
    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $meta;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var Direct
     */
    private $breadcrumbsModel;

    /**
     * DefaultBreadcrumbs constructor.
     * @param ArrayManager $arrayManager
     * @param LocatorInterface $locator
     * @param Direct $breadcrumbsModel
     */
    public function __construct(
        ArrayManager $arrayManager,
        LocatorInterface $locator,
        Direct $breadcrumbsModel
    ) {
        $this->arrayManager = $arrayManager;
        $this->locator = $locator;
        $this->breadcrumbsModel = $breadcrumbsModel;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $this->data = $data;
        return $this->data;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $attrPath = $this->arrayManager->findPath(
            'default_breadcrumbs',
            $this->meta,
            null,
            'children'
        );

        $optionsPath = $attrPath.'/arguments/data/config/options';

        $this->meta = $this->arrayManager->set(
            $optionsPath,
            $this->meta,
            $this->getBreadcrumbsAvailable()
        );

        return $this->meta;
    }

    /**
     * @return array
     */
    public function getBreadcrumbsAvailable()
    {
        $availablePaths = [
            ['value' => '', 'label' => __('Detect Automatically')]
        ];

        $categoryIds = $this->locator->getProduct()->getCategoryIds();
        foreach ($categoryIds as $categoryId) {

            $pathStr = ['Home'];

            $category = $this->breadcrumbsModel->getCategoryModel($categoryId);
            $path = explode(',', $category->getPathInStore());
            krsort($path);

            foreach ($path as $pathCatId) {
                if (!$pathCatId) {
                    continue;
                }

                $pathCat = $this->breadcrumbsModel->getCategoryModel($pathCatId);
                if ($pathCat->getLevel() < 2) {
                    continue;
                }

                $pathStr[] = $pathCat->getName();
            }

            $availablePaths[] = [
                'value' => $categoryId,
                'label' => implode(' / ', $pathStr)
            ];
        }

        return $availablePaths;
    }
}
