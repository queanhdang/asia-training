<?php
 
namespace AHT\AddAttribute\Setup;
 
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
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'editor_attribute1' // attribute code to remove
            );
        }
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            /**
             * Remove attribute
             */
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'editor_attribute11' // attribute code to remove
            );
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'multiselect_attribute11' // attribute code to remove
            );
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'select_attribute11' // attribute code to remove
            );
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'textarea_attribute11' // attribute code to remove
            );
            $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'text_attribute11' // attribute code to remove
            );
            // // get default attribute set id
            // $attributeSetId = $categorySetup->getAttributeSetId(\Magento\Catalog\Model\Product::ENTITY, 'Test Attribute Set 1');
            // $attributeGroupName = 'My Group Test';
            // // your custom attribute group/tab
            // $categorySetup->addAttributeGroup(
            //     \Magento\Catalog\Model\Product::ENTITY,
            //     $attributeSetId,
            //     $attributeGroupName, // attribute group name
            //     100 // sort order
            // );
        }
        // if (version_compare($context->getVersion(), '1.0.3') < 0) {
        //     $attributeSetId = $categorySetup->getAttributeSetId(\Magento\Catalog\Model\Product::ENTITY, 'Test Attribute Set 1');
        //     $eavSetup->addAttribute(
        //         \Magento\Catalog\Model\Product::ENTITY,
        //         'wysi_attribute',
        //         [
        //             'type' => 'text', // data type to be saved in database table
        //             'backend' => '',
        //             'frontend' => '',
        //             'label' => 'Text editor',
        //             'input' => 'texteditor', // form element type displayed in the form
        //             'class' => '',
        //             'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE, // can also use \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE, // scope of the attribute (global, store, website)
        //             'visible' => true,
        //             'required' => true,
        //             'user_defined' => false,
        //             'searchable' => false,
        //             'filterable' => false,
        //             'comparable' => false,
        //             'visible_on_front' => false,
        //             'used_in_product_listing' => true,
        //             'unique' => false,
        //             'apply_to' => '',
        //         ]
        //     );
        //     $categorySetup->addAttributeToGroup(
        //         \Magento\Catalog\Model\Product::ENTITY,
        //         $attributeSetId,
        //         'My Group Test', // attribute group
        //         'wysi_attribute', // attribute code
        //         10 // sort order
        //     );    
        // }
        // if (version_compare($context->getVersion(), '1.0.4') < 0) {
        //     /**
        //      * Remove attribute
        //      */
        //     $eavSetup->removeAttribute(
        //         \Magento\Catalog\Model\Product::ENTITY,
        //         'wysi_attribute' // attribute code to remove
        //     );
           
        // }
    }
}