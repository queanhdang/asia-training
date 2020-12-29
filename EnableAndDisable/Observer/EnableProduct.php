<?php

namespace AHT\EnableAndDisable\Observer;

use Magento\Framework\Event\ObserverInterface;

class EnableProduct implements ObserverInterface
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_stockItemRepository;
    /**
     * Undocumented function
     *
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository
     */
    public function __construct(
        // \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->_stockItemRepository = $stockItemRepository;
    }
    /**
     * Undocumented function
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $productTypeId = $product->getTypeId();
        $stockProduct = $this->getStockItem($product->getId());
        if ($stockProduct->getIsInstock()) {
            if ($productTypeId == 'configurable') {
                $flag = 0;
                $childrens = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($childrens as $child) {
                    $childStock = $this->getStockItem($child->getId());
                    $qty_stock = $childStock->getQty();
                    $instock = $childStock->getIsInstock();
                    if ($qty_stock > 0 && $instock) {
                        $flag++;
                        $child->setData('status', 1);
                        $child->getResource()->saveAttribute($child, 'status');
                    } else {
                        $child->setData('status', 2);
                        $child->getResource()->saveAttribute($child, 'status');
                    }
                }
                if ($flag > 0) {
                    $product->setData('status', 1);
                    $product->getResource()->saveAttribute($product, 'status');
                } else {
                    $product->setData('status', 2);
                    $product->getResource()->saveAttribute($product, 'status');
                }
            } else {
                $this->setValue($product);
            }
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $product
     * @return void
     */
    public function setValue($product)
    {
        $childStock = $this->getStockItem($product->getId());
        $qty_stock = $childStock->getQty();
        $instock = $childStock->getIsInstock();
        if ($qty_stock > 0 && $instock) {
            $product->setData('status', 1);
            $product->getResource()->saveAttribute($product, 'status');
        } else {
            $product->setData('status', 2);
            $product->getResource()->saveAttribute($product, 'status');
        }
    }
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}
