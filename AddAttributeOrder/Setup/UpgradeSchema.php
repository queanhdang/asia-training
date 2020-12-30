<?php

namespace AHT\AddAttributeOrder\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $quote = 'quote';
        $orderTable = 'sales_order';
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($quote),
                    'custom_attribute',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Custom Attribute'
                    ]
                );
            //Order table
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'custom_attribute',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Custom Attribute'
                    ]
                );
        }
        $setup->endSetup();
    }
}
