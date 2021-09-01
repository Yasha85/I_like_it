<?php

namespace Amasty\CoolModule\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class InstallSchema implements InstallSchemaInterface
{

    const TABLE_NAME = 'amasty_blacklist';
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable($setup->getTable(self::TABLE_NAME))
            ->addColumn
            (
            'blacklist_product_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Blacklist Product ID'

            )->addColumn
            (
                'product_sku',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'default' => ''
                ],
                'Product Sku'
            )->setComment('Blacklist Table');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
