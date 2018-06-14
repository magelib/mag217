<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Plugin;

use Magento\Framework\App\PageCache\Identifier;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Framework\App\Request\Http;

class CacheIdentifierPlugin
{
    /**
     * @var CatalogSession
     */
    private $catalogSession;

    /**
     * @var Http
     */
    private $request;

    /**
     * CacheIdentifierPlugin constructor.
     * @param CatalogSession $catalogSession
     * @param Http $request
     */
    public function __construct(
        CatalogSession $catalogSession,
        Http $request
    ) {
        $this->catalogSession = $catalogSession;
        $this->request = $request;
    }

    /**
     * Add category id to cache identifier
     */
    public function afterGetValue(Identifier $identifier, $result)
    {
        $currentHost = $this->request->getServer('HTTP_HOST');
        $ref = parse_url($this->request->getServer('HTTP_REFERER'));

        if (empty($ref['host']) || $ref['host'] != $currentHost) {
            return $result;
        }

        $path = isset($ref['path']) ? str_replace('/index.php', '', $ref['path']) : '';
        $suffix = md5($path);

        return $result . '_' . $suffix;
    }
}
