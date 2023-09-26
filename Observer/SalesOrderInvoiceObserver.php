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
/**
 * SalesOrderInvoiceObserver Observer.
 */
class SalesOrderInvoiceObserver implements \Magento\Framework\Event\ObserverInterface
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
     * @param \Magento\Sales\Model\Order                               $ordersCollection
     * @param \Ksolves\Notifications\Model\OrdersNotificationsFactory  $ordersNotificationsFactory
     * @param \Magento\Framework\Message\ManagerInterface              $messageManager
     * @param\Magento\Framework\Stdlib\DateTime\TimezoneInterface      $timezone
     */
    public function __construct(
        \Magento\Sales\Model\Order  $ordersCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Ksolves\Notifications\Model\OrdersNotificationsFactory $ordersNotificationsFactory
    ) {
        $this->timezone                    = $timezone;
        $this->_order                      = $ordersCollection;
        $this->_ordersNotificationsFactory = $ordersNotificationsFactory;
        $this->_messageManager             = $messageManager;
    }
	/**
	 * Execute observer.
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return $this
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $ks_invoice = $observer->getEvent()->getInvoice();
        try {
            if (!empty($ks_invoice)) {
               $invoice_id  = $ks_invoice->getData('entity_id');
               $orderId     = $ks_invoice->getData('order_id');
           
                $ks_data = [];
                $ks_data['order_id']       = $orderId;
                $order                     = $this->_order->load($orderId);
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
                $ks_data['invoice_id']        = $invoice_id;
                
                if ($ks_data['customer_id'] != null) {
                    $model = $this->_ordersNotificationsFactory->create();
                    $model->setData($ks_data);
                    $model->save();  
                }
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
	}
}    