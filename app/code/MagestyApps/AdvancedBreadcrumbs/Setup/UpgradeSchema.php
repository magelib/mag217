<?php

namespace MagestyApps\AdvancedBreadcrumbs\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'custom_breadcrumbs',
            [
                'type' => Table::TYPE_TEXT,
                'comment' => 'Custom Breadcrumbs'
            ]
        );

        $setup->endSetup();
    }
}
