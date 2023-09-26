<?php
/**
 * Ksolves
 *
 * @category  Ksolves
 * @package   Ksolves_Notifications
 * @author    Ksolves
 * @copyright Copyright (c) Ksolves Software Private Limited (https://www.ksolves.com/)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Ksolves\Notifications\Observer;

use Ksolves\Notifications\Model\ResourceModel\OrdersNotifications\CollectionFactory as OrdersNotificationsCollection;
/**
 * SalesOrderShipmentObserver Observer.
 */
class SalesOrderShipmentObserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;


    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Ksolves\Notifications\Model\OrdersNotificationsFactory
     */
    protected $_ordersNotificationsFactory;

     /**
     * @var OrdersNotificationsCollection
     */
    protected $_ordersNotificationsCollection;

	/**
     * @param \Magento\Sales\Model\Order                               $ordersCollection
     * @param \Ksolves\Notifications\Model\OrdersNotificationsFactory  $ordersNotificationsFactory
     * @param \Magento\Framework\Message\ManagerInterface              $messageManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface      $timezone
     * @param OrdersNotificationsCollection      $ordersNotificationsCollection
     */
    public function __construct(
        \Magento\Sales\Model\Order  $ordersCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Ksolves\Notifications\Model\OrdersNotificationsFactory $ordersNotificationsFactory,
        OrdersNotificationsCollection $ordersNotificationsCollection
    ) {
        $this->timezone                       = $timezone;
        $this->_order                         = $ordersCollection;
        $this->_ordersNotificationsFactory    = $ordersNotificationsFactory;
        $this->_messageManager                = $messageManager;
        $this->_ordersNotificationsCollection = $ordersNotificationsCollection;
    }
	/**
	 * Execute observer.
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return $this
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $ks_shipment = $observer->getEvent()->getShipment();

        try {
            if (!empty($ks_shipment)) 
            {
                $shipment_id = $ks_shipment->getData('entity_id');
                $orderId     = $ks_shipment->getData('order_id');
                
                $ks_invoiceCheck = $this->ksCheckInvoice($orderId);

                if ($ks_invoiceCheck) {
                    $ksIsExist = $this->ksCheckInvoiceExistData($orderId,$ks_invoiceCheck,'complete');
                    if ($ksIsExist) {
                       $this->ksUpdateTable($ksIsExist,$shipment_id);
                    }else{
                       $this->ksSaveOrderNotificTable($orderId,$shipment_id);
                    }
                }else{
                   $this->ksSaveOrderNotificTable($orderId,$shipment_id);
                }
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
    

    /**
     * insert the new entry.
     *
     */
    public function ksSaveOrderNotificTable($orderId,$shipment_id)
    {
        $order   = $this->_order->load($orderId);
        $ks_data = [];
        $ks_data['order_id']       = $orderId;
        $ks_data['order_status']   = $order->getStatus();
        $customerId                = $order->getCustomerId();
        if ($order->getCustomerId()) {
            $ks_data['customer_id']   = $order->getCustomerId();
        } else {
            $ks_data['customer_id']   = null;   
        }

        $ks_data['customer_name']     = $order->getCustomerName();
        $ks_data['customer_email']    = $order->getCustomerEmail();
        $ks_data['increment_id']      = $order->getIncrementId();
        $ks_data['created_at']        = $this->timezone->date(); 
        $ks_data['shipment_id']       = $shipment_id;
        
        if ($ks_data['customer_id'] != null) {
            $model = $this->_ordersNotificationsFactory->create();
            $model->setData($ks_data);
            $model->save();   
        }
    }

    /**
     * Return the invoice status.
     *
     * @return bool|0|1
     */
    public function ksCheckInvoice($orderId)
    {
        $orderdetails = $this->_order->load($orderId);
        if ($orderdetails) {
            $invoice_id = '';
            foreach ($orderdetails->getInvoiceCollection() as $invoice)
            {
                $invoice_id = $invoice->getId();
            }
            if ($invoice_id) {
                return $invoice_id;
            }else{
                return $invoice_id;
            }
            
        }else{
            return false;
        }
    }

    /**
     * Return the invoice data exist or not.
     *
     * @return bool|0
     * @return int|tableid
     */
    public function ksCheckInvoiceExistData($orderId,$invoiceId,$status)
    {
        $collection = $this->_ordersNotificationsCollection->create()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToFilter('invoice_id', $invoiceId)
            ->addFieldToFilter('order_status', $status);
        if ($collection->getSize()) {
            foreach ($collection as $value) {
                return $value['order_notification_id'];
            }
        }
        return false;
    }


    /**
     * Update the exist table.
     */
    public function ksUpdateTable($tableId,$shipment_id)
    {
        $ksNotificModel = $this->_ordersNotificationsFactory->create()->load($tableId);
        if ($ksNotificModel) {
            $ksNotificModel->setShipmentId($shipment_id);
            $ksNotificModel->setCreatedAt($this->timezone->date());
            $ksNotificModel->save();
        }
    }
}