<?php
 
namespace AHT\AddAttributeCategory\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
 
    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Constructor
     *
     * @param CategorySetupFactory $categorySetupFactory
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        
        /**
         * run this code if the module version stored in database is less than 1.0.1
         * i.e. the code is run while upgrading the module from version 1.0.0 to 1.0.1
         */ 
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'is_landing',
                [
                    'type'         => 'int',
                    'label'        => 'Is landing',
                    'input'        => 'int',
                    'sort_order'   => 100,
                    'source'       => '',
                    'global'       => 1,
                    'visible'      => true,
                    'required'     => false,
                    'user_defined' => false,
                    'default'      => null,
                    'group'        => '',
                    'backend'      => ''
                ]
            );
        }
        $setup->endSetup();
    }
}