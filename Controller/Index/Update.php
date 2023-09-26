<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;
/**
 * Notifications count update 
 */
class Update extends \Magento\Framework\App\Action\Action
{
  /**
   * OrdersNotifications model 
   *
   * @var \Ksolves\Notifications\Model\OrdersNotifications
   */
  protected $_ordersNotifications;

   /**
    * CustomNotifications model 
    *
    * @var \Ksolves\Notifications\Model\CustomNotifications
    */
  protected $customNotifications;

   /**
    * WishlistNotifications model 
    *
    * @var \Ksolves\Notifications\Model\WishlistNotifications
    */
  protected $wishlistNotifications;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Ksolves\Notifications\Model\OrdersNotifications $ordersNotifications
     * @param \Ksolves\Notifications\Model\CustomNotifications $customNotifications,
     * @param \Ksolves\Notifications\Model\WishlistNotifications $wishlistNotifications
     */
    public function __construct(       
     \Magento\Framework\App\Action\Context $context ,
     \Ksolves\Notifications\Model\OrdersNotifications $ordersNotifications,
     \Ksolves\Notifications\Model\CustomNotifications $customNotifications,
     \Ksolves\Notifications\Model\CustomerAdminNotifications $customerAdminNotifications,
     \Ksolves\Notifications\Model\WishlistNotifications $wishlistNotifications
   )
    {
      $this->_ordersNotifications     = $ordersNotifications;
      $this->_wishlistNotifications   = $wishlistNotifications;
      $this->_customNotifications     = $customNotifications;
      $this->_customerAdminNotifications     = $customerAdminNotifications;

      parent::__construct($context);
    }

  /**
     * Notifications  action
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
  public function execute()
  {
     /* update order notifications is_read field */
      $customNoticId      = $this->getRequest()->getPost('custom_notific_id');
      $currentCustomerId  = $this->getRequest()->getPost('customer_id');
      $wishlistNotificId  = $this->getRequest()->getPost('wishlist_notic_id');
      $orderId            = $this->getRequest()->getPost('order_id');
      $orderStatus        = $this->getRequest()->getPost('order_status');
      $creditmemoId       = $this->getRequest()->getPost('creditmemo_id');
      $shipmentId         = $this->getRequest()->getPost('shipment_id');
      $invoiceId          = $this->getRequest()->getPost('invoice_id');
      
      if ($wishlistNotificId) {
        $this->_wishlistNotifications->load($wishlistNotificId);
        $this->_wishlistNotifications->setIsRead(1)->save();
      }
      
      if ($customNoticId) {
        $customerAdminCollections = $this->_customerAdminNotifications->getCollection()->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('group_notifications_id',$customNoticId);
        foreach ($customerAdminCollections as $data) {
          $this->_customerAdminNotifications->load($data->getId());
          $this->_customerAdminNotifications->setIsRead(1)->save();
        } 
      }

      $ordersCollections = [];
      if ($creditmemoId) {
        $ordersCollections = $this->_ordersNotifications->getCollection()->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('creditmemo_id',$creditmemoId);
      }

      if ($shipmentId) {
        $ordersCollections = $this->_ordersNotifications->getCollection()->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('shipment_id',$shipmentId);
      } 

      if ($invoiceId) {
        $ordersCollections = $this->_ordersNotifications->getCollection()->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('invoice_id',$invoiceId);
      } 

      if ($orderId) {
        $ordersCollections = $this->_ordersNotifications->getCollection()->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('order_id',$orderId)->addFieldToFilter('order_status',$orderStatus);
      }
      
      if (count($ordersCollections) > 0) {
        foreach ($ordersCollections as $data) {
          $this->_ordersNotifications->load($data->getId());
          $this->_ordersNotifications->setIsRead(1)->save();
        }   
      }
   }
 }
 
