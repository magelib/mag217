<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagestyApps\AdvancedBreadcrumbs\Helper\Data;
use MagestyApps\AdvancedBreadcrumbs\Model\PageType\Product;
use Magento\Catalog\Helper\Category as CategoryHelper;

class Direct extends Product
{
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var array
     */
    private $categoryModels = [];

    /**
     * Direct constructor.
     * @param Http $request
     * @param UrlInterface $urlBuilder
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param CategoryHelper $categoryHelper
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        Http $request,
        UrlInterface $urlBuilder,
        Registry $registry,
        StoreManagerInterface $storeManager,
        Data $helper,
        CategoryHelper $categoryHelper,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($request, $urlBuilder, $registry, $storeManager, $helper);
        $this->categoryHelper = $categoryHelper;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $allPaths = [];
        $priorPaths = [];
        $defaultCategory = $this->getHelper()->getDefaultCategory();

        $product = $this->getProduct();
        foreach ($product->getCategoryIds() as $categoryId) {
            $category = $this->getCategoryModel($categoryId);

            if (!$this->categoryHelper->canShow($category)) {
                continue;
            }

            if ($this->getHelper()->showOnlyOnePath()
                && $product->getData('default_breadcrumbs')
                && $categoryId != $product->getData('default_breadcrumbs')
            ) {
                continue;
            }

            $key = $category->getParentId() . '_' . $category->getPosition();

            if ($defaultCategory && in_array($defaultCategory, $category->getPathIds())) {
                $priorPaths[$key] = $category->getPathInStore();
            } else {
                $allPaths[$key] = $category->getPathInStore();
            }
        }

        if (count($priorPaths)) {
            $allPaths = $priorPaths;
        }

        ksort($allPaths, SORT_NATURAL);

        $allPaths = $this->_removeDuplicates($allPaths);

        $crumbs = $this->preparePaths($allPaths);

        if ($this->getHelper()->showOnlyOnePath()) {
            $crumbs = $this->getLongestPath($crumbs);
        } elseif ($this->getHelper()->hideDuplicates()) {
            $crumbs = $this->hideDubCategories($crumbs);
        }

        return $crumbs;
    }

    /**
     * @param $categoryId
     * @param null $storeId
     * @return bool
     */
    public function getCategoryModel($categoryId, $storeId = null)
    {
        if (!$categoryId) {
            return false;
        }

        if ($storeId === null) {
            $storeId = $this->getStoreManager()->getStore()->getId();
        }

        if (!isset($this->categoryModels[$categoryId.'_'.$storeId])) {
            $category = $this->categoryRepository->get($categoryId, $storeId);
            $this->categoryModels[$categoryId] = $category;
        }

        return $this->categoryModels[$categoryId];
    }

    /**
     * @param array $pathArr
     * @return array
     */
    private function preparePaths(array $pathArr)
    {
        $result = [];

        foreach ($pathArr as $path) {
            $catIdsArr = explode(',', $path);
            krsort($catIdsArr);

            $newPath = [];
            foreach ($catIdsArr as $catId) {
                $category = $this->getCategoryModel($catId);

                if (!$category || !$this->categoryHelper->canShow($category)) {
                    continue;
                }

                $newPath[] = [
                    'category_id' => $category->getId(),
                    'title' => $category->getName(),
                    'link' => $category->getUrl(),
                    'last' => false
                ];
            }
            $result[] = $newPath;
        }

        if (!count($result)) {
            $result[] = [[
                'category_id' => 0,
                'title' => __('Home'),
                'link' => $this->getUrlBuilder()->getBaseUrl(),
                'last' => false
            ]];
        }

        return $result;
    }

    /**
     * Remove duplicated paths
     *
     * @param array $pathArr
     * @return mixed
     */
    private function _removeDuplicates(array $pathArr)
    {
        foreach ($pathArr as $k => $path) {
            foreach ($pathArr as $pathCompare) {
                if ((bool) strpos($pathCompare, $path) !== false) {
                    unset ($pathArr[$k]);
                }
            }
        }
        return $pathArr;
    }

    /**
     * @param array $crumbs
     * @return array
     */
    private function getLongestPath(array $crumbs)
    {
        if (count($crumbs) == 1) {
            return $crumbs;
        }

        $longestPath = '';
        foreach ($crumbs as $k => $path) {
            if (count($path) > count($longestPath)) {
                $longestPath = $crumbs[$k];
            }
        }

        return [$longestPath];
    }

    /**
     * Mark duplicated categories as hidden
     *
     * @param array $crumbs
     * @return array
     */
    private function hideDubCategories(array $crumbs)
    {
        $existCat = [];
        foreach ($crumbs as $pathKey => $path) {
            foreach ($path as $crumbKey => $crumb) {
                if (in_array($crumb['category_id'], $existCat)) {
                    $crumbs[$pathKey][$crumbKey]['hidden'] = true;
                } else {
                    $existCat[] = $crumb['category_id'];
                }
            }
        }
        return $crumbs;
    }
}
