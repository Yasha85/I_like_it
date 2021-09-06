<?php

namespace Amasty\CoolModule\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $setup->getConnection()->addColumn
            (
                $setup->getTable(InstallSchema::TABLE_NAME),
                'product_qty',
                [
                    'type' => Table::TYPE_INTEGER,
                    11,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Product QTY'
                ]
            );
        }
        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $setup->getConnection()->addColumn
            (
                $setup->getTable(InstallSchema::TABLE_NAME),
                'email_body',
                [
                    'type' => Table::TYPE_TEXT,
                    255,
                    'default' => '',
                    'nullable' => false,
                    'comment' => 'Email Body'
                ]
            );
        }

        $setup->endSetup();
    }
}
