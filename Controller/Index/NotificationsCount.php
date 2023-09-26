<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;

/**
 * Notifications count show
 */
class NotificationsCount extends \Magento\Framework\App\Action\Action
{
  /**
   * @var \Magento\Framework\Controller\Result\JsonFactory
   */
  protected $resultJsonFactory;

   /**
    * @var \Ksolves\Notifications\Helper\ConfigData
    */
  protected $_configData;

  /**
    * @var \Ksolves\Notifications\Helper\CollectionsData
    */
  protected $_collectionsData;

   /**
    * @var \Ksolves\Notifications\Model\ResourceModel\CustomerAdminNotifications\CollectionFactory
    */
  protected $_customAdminNotific;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Ksolves\Notifications\Helper\ConfigData $configData
     * @param \Ksolves\Notifications\Helper\CollectionsData $collectionsData
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
     * @param \Ksolves\Notifications\Model\ResourceModel\CustomerAdminNotifications\CollectionFactory $customAdminNotific
     */
    public function __construct(       
     \Magento\Framework\App\Action\Context $context ,
     \Ksolves\Notifications\Helper\ConfigData $configData,
     \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
     \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
     \Ksolves\Notifications\Model\ResourceModel\CustomerAdminNotifications\CollectionFactory $customAdminNotific
   )
    {
      $this->resultJsonFactory            = $resultJsonFactory; 
      $this->_customAdminNotific          = $customAdminNotific;
      $this->_configData                  = $configData;
      $this->_collectionsData             = $collectionsData;
      parent::__construct($context);
    }

  /**
     * Notifications  action
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
  public function execute()
  {
    $currentCustomerId     = $this->getRequest()->getPost('customer_id');
    $currentCustomerGroup  = $this->getRequest()->getPost('customer_group');
    if ($this->enableWishlistNotification() == 1) {
        $wishlistCount = $this->_collectionsData->getWishlistCollections($currentCustomerId)->addFieldToFilter('is_read',0)->count();
      } else {
        $wishlistCount = 0;
      }

      if ($this->enableOrderNotification() == 1) {
        $ordersCount   = $this->_collectionsData->getOrdersCollections($currentCustomerId)->addFieldToFilter('is_read',0)->count();
    } else {
      $ordersCount = 0;
    }

    if ($this->enableCustomNotification() == 1) {
      $collection =$this->_customAdminNotific->create();
        $customCount   = $collection->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('customer_group_id',$currentCustomerGroup)->addFieldToFilter('is_read',0)->addFieldToFilter('status',1)->count();
    } else {
      $customCount = 0;
    }
      $totalCount    = $wishlistCount +  $ordersCount + $customCount;
      $result = $this->resultJsonFactory->create();
      $result->setData($totalCount);
      return $result;
  }

  /**
   * enable or disable the order Notifications
   * @return int
   */
  public function enableOrderNotification()
  {
    return $this->_configData->getNotificationsConfig('enable_orderStatus');
  }

  /**
   * enable or disable the wishlist Notifications
   * @return int
   */
  public function enableWishlistNotification()
  {
    return $this->_configData->getNotificationsConfig('enable_wishlistStatus');
  }

  /**
   * enable or disable the custom Notifications
   * @return int
   */
  public function enableCustomNotification()
  {
    return $this->_configData->getNotificationsConfig('enable_customStatus');
  }

}
 
