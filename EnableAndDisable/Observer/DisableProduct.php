<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace AHT\EnableAndDisable\Observer;

use Magento\Customer\Helper\Address as CustomerAddress;
use Magento\Customer\Model\Address\AbstractAddress;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;

/**
 * Class AddVatRequestParamsOrderComment
 */
class DisableProduct implements ObserverInterface
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_stockItemRepository;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_getSalableQuantityDataBySku;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_productFactory;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_logger;
    /**
     * Undocumented function
     *
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository
     */
    public function __construct(
        // \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_stockItemRepository = $stockItemRepository;
        $this->_getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->_productFactory = $productFactory;
        $this->_logger = $logger;
    }
    /**
     * Undocumented function
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var $orderInstance Order */
        $orderInstance = $observer->getOrder();
        $items = $orderInstance->getAllVisibleItems();
        $ids[] = array();
        for ($i = count($items) - 1; $i>=0; $i--) {
            // $this->_logger->debug($items[$i]);
            // $this->_logger->debug($items[$i]->getProductId());
            $salable_qty = $this->_getSalableQuantityDataBySku->execute($items[$i]->getSku())[0]["qty"];
            $product = $this->_productFactory->create()->load($items[$i]->getProductId());
            if($product->getTypeId() == "Configurable") {
                $flag = 0;
                $childrens = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($childrens as $child) {
                    if ($child->getStatus() == 1) {
                        $flag++;
                        break;
                    }
                }
                if($flag == 0) {
                    $product->setData('status', 2);
                    $product->getResource()->saveAttribute($product, 'status');
                }
            } else {
                // $this->_logger->debug($salable_qty - $items[$i]->getQtyOrdered());
                if (($salable_qty - $items[$i]->getQtyOrdered()) == 0) {
                    $product->setData('status', 2);
                    $product->getResource()->saveAttribute($product, 'status');
                }
            }
        }
    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}
