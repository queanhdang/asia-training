<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\AddAttributeOrder\Block\Order;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class History extends \Magento\Sales\Block\Order\History
{
    /**
     * @var string
     */
    protected $_template = 'AHT_AddAttributeOrder::order/history.phtml';
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $resource;
    protected $_orderCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->resource = $resource;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context,$orderCollectionFactory, $customerSession, $orderConfig, $data);
    }
    /**
     * Provide order collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getOrderCollectionFactory()
    {
        if ($this->_orderCollectionFactory === null) {
            $this->_orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->_orderCollectionFactory;
    }
    /**
     * Get customer orders
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        
        if($this->getNameRequest()==null) {
            if (!$this->orders) {
                $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                    '*'
                )->addFieldToFilter(
                    'status',
                    ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
                )->setOrder(
                    'created_at',
                    'desc'
                );
            }
        }
        else {
            if (!$this->orders) {
                $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                    '*'
                )->addFieldToFilter(
                    'status',
                    $this->getNameRequest()
                )->setOrder(
                    'created_at',
                    'desc'
                );
            }

        }
        return $this->orders;
    }
    /**
     * Undocumented function
     *
     * @return 
     */
    public function getStatus() {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('sales_order_status');
        $sql = "SELECT * FROM " . $tableName . " WHERE status IN ( ";
        $items = $this->_orderConfig->getVisibleOnFrontStatuses();
        for($i = 0; $i < count($items) - 1; $i++) {
            $sql = $sql . "'" . $items[$i] . "'" . ",";
        }
        $sql = $sql . "'" . $items[count($items) - 1] . "'" . " )";
        return $connection->fetchAll($sql);
    }
    /**
     * Undocumented function
     *
     * @return 
     */
    public function getNameRequest() {
        return $this->getRequest()->getParam('status');
    }
}
