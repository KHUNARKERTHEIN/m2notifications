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

use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Framework\Session\SessionManager;

/**
 * SalesOrderPlaceObserver Observer.
 */
class SalesOrderPlaceObserver implements ObserverInterface
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
     * @var QuoteRepository
     */
    protected $_quoteRepository;


    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Ksolves\Notifications\Model\OrdersNotificationsFactory
     */
    protected $_ordersNotificationsFactory;

    /**
     * @var Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

     /**
     * [$_coreSession description].
     *
     * @var SessionManager
     */
    protected $_coreSession;

    /**
     * @param \Magento\Sales\Model\Order                               $ordersCollection
     * @param \Ksolves\Notifications\Model\OrdersNotificationsFactory  $ordersNotificationsFactory
     * @param \Magento\Framework\Message\ManagerInterface              $messageManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface     $timezone
     * @param \Magento\Checkout\Model\Session                          $checkoutSession
     * @param QuoteRepository                                          $quoteRepository
     * @param SessionManager                                           $coreSession
     */
    public function __construct(
        \Magento\Sales\Model\Order  $ordersCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Ksolves\Notifications\Model\OrdersNotificationsFactory $ordersNotificationsFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        QuoteRepository $quoteRepository,
        SessionManager $coreSession
    ) {
        $this->timezone                    = $timezone;
        $this->_order                      = $ordersCollection;
        $this->_ordersNotificationsFactory = $ordersNotificationsFactory;
        $this->_messageManager             = $messageManager;
        $this->_checkoutSession            = $checkoutSession;
        $this->_quoteRepository            = $quoteRepository;
        $this->_coreSession                = $coreSession;
    }

    /**
     * Sales order save commmit after on order complete state event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $orderInstance Order */

        $isMultiShipping = $this->_checkoutSession->getQuote()->getIsMultiShipping();
       
        try {
            if (!$isMultiShipping) {
            /** @var $orderInstance Order */
            $order = $observer->getOrder();
            $lastOrderId = $observer->getOrder()->getId();
                $this->ksSaveOrderNotificTable($lastOrderId);
            } else {
                $quoteId = $this->_checkoutSession->getLastQuoteId();
                $quote = $this->_quoteRepository->get($quoteId);
                if ($quote->getIsMultiShipping() == 1 || $isMultiShipping == 1) {
                    $orderIds = $this->_coreSession->getOrderIds();
                    foreach ($orderIds as $ids => $orderIncId) {
                        $lastOrderId = $ids;
                        /** @var $orderInstance Order */
                        $order = $this->ksSaveOrderNotificTable($lastOrderId);
                    }
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
    public function ksSaveOrderNotificTable($orderId)
    {
        $order   = $this->_order->load($orderId);
        if ($order->getState() == 'new') {
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
            
            if ($ks_data['customer_id'] != null) {
                $model = $this->_ordersNotificationsFactory->create();
                $model->setData($ks_data);
                $model->save();   
            } 
        }
    }
}
