<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class CollectionsData extends AbstractHelper
{
 /**
  * WishlistNotifications model 
  * @var \Ksolves\Notifications\Model\ResourceModel\WishlistNotifications\Collection 
  */
 protected $_wishlistNotifications;

  /**
    * OrdersNotifications model 
    * @var \Ksolves\Notifications\Model\ResourceModel\OrdersNotifications\Collection 
    */
  protected $_ordersCollections;

  /**
   * CustomNotifications model 
   * @var \Ksolves\Notifications\Model\ResourceModel\CustomNotifications\Collection 
   */
  protected $_customCollections;

  /**
   *  objectmanager
   * @var \Magento\Framework\App\ObjectManager 
   */
  protected $_objectManager;

  /**
   *  objectmanager
   * @var \Magento\Framework\App\ObjectManager 
   */
  protected $_productFactory;

  /**
   *  helper image class object 
   * @var \Magento\Framework\App\ObjectManager 
   */
  protected $_helperImage;

  /**
   *  store manager
   * @var \Magento\Framework\App\ObjectManager 
   */
  protected $_storeManager;

  /**
   *  order factory model 
   * @var \Magento\Framework\App\ObjectManager 
   */
  protected $_orderFactory;

  /**
   *  filter provider
   * @var \Magento\Cms\Model\Template\FilterProvider
   */
  protected $_filterProvider;

  /**
   * @param \Magento\Framework\App\ObjectManager $objectmanager
   * @param \Magento\Store\Model\StoreManagerInterface $storeManager
   * @param \Magento\Sales\Model\Order $orderFactory
   * @param \Magento\Catalog\Helper\Image $helperImage
   * @param \Magento\Catalog\Model\Product $ProductFactory
   * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
   * @param \Ksolves\Notifications\Model\ResourceModel\WishlistNotifications\Collection 
   * @param \Ksolves\Notifications\Model\ResourceModel\OrdersNotifications\Collection 
   * @param \Ksolves\Notifications\Model\ResourceModel\CustomNotifications\Collection 
   */

  public function __construct(
    \Magento\Catalog\Model\Product $ProductFactory,
    \Ksolves\Notifications\Helper\ConfigData $configData,
    \Magento\Sales\Model\Order $orderFactory,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Catalog\Helper\Image $helperImage,
    \Magento\Cms\Model\Template\FilterProvider $filterProvider,
    \Ksolves\Notifications\Model\ResourceModel\WishlistNotifications\CollectionFactory $wishlistCollections,
    \Ksolves\Notifications\Model\ResourceModel\OrdersNotifications\CollectionFactory $ordersCollections,
    \Ksolves\Notifications\Model\ResourceModel\CustomNotifications\CollectionFactory $customCollections
  )
  {
    $this->_filterProvider      = $filterProvider;
    $this->_configData          = $configData;
    $this->_objectManager       = $objectManager;
    $this->_helperImage         = $helperImage;
    $this->_storeManager        = $storeManager;
    $this->_productFactory      = $ProductFactory;
    $this->_orderFactory        = $orderFactory;
    $this->_wishlistCollections = $wishlistCollections;
    $this->_ordersCollections   = $ordersCollections;
    $this->_customCollections   = $customCollections;

  }

  /**
  * collection of  wishlistnotifications 
  * @return array 
  */
  public function getWishlistCollections($currentCustomerId)
  {
    /*    Get wishlist notifications data      */
    $collection =   $this->_wishlistCollections->create();              
     $wishlistCollections = $collection->addFieldToFilter('customer_id',$currentCustomerId);

    return $wishlistCollections;
  }

  /**
  * collections of ordernatifications  
  * @return array 
  */
  public function getOrdersCollections($currentCustomerId)
  {
    /*    Get orders natifications  data      */
    $collection =   $this->_ordersCollections->create(); 
     $ordersCollections = $collection->addFieldToFilter('customer_id',$currentCustomerId);

    return $ordersCollections;
  }

  /**
  * collections of customnotifications  model 
  * @return array
  */
  public function getCustomCollections($currentCustomerGroup)
  { 
    /*    Get custom notifications data      */
    $collection =   $this->_customCollections->create(); 
     $customCollections = $collection->addFieldToFilter('customer_group_id',$currentCustomerGroup)->addFieldToFilter('status',1);

    return $customCollections;
  }

  /**
  * collections of customnotifications , ordernatifications and wishlistnotifications model 
  * @return array
  */
  public function getCollections($currentCustomerId , $currentCustomerGroup)
  {

    if ($this->_configData->getNotificationsConfig('enable_wishlistStatus') == 1) {
        $wishlistItems = $this->getWishlistCollections($currentCustomerId)->getData();
      } else {
        $wishlistItems = array() ;
      }

      if ($this->_configData->getNotificationsConfig('enable_orderStatus') == 1) {
        $ordersItems = $this->getOrdersCollections($currentCustomerId)->getData();
      } else {
        $ordersItems = array() ;
      }

      if ($this->_configData->getNotificationsConfig('enable_customStatus') == 1) {
        $customItems = $this->getCustomCollections($currentCustomerGroup)->getData();
      } else {
        $customItems =array() ;
      }


    /* Get data from all three collections */   
    $collections          = array_merge($wishlistItems , $ordersItems , $customItems);
    return $collections;
  }


  /**
   * Customer groups collections
   * @return array
   */
  public function getCustomerGroups()
  {
    /*    Get customer groups data      */
    return $this->_objectManager->create('\Magento\Customer\Model\Group');
  }

  /**
   * Customer groups collections
   * @return array
   */
  public function getCustomerSessions()
  {
    /*    Get customer Sessions data      */
    return $this->_objectManager->create('Magento\Customer\Model\Session');
  }

  /**
   * get Order Product image
   * @return string
   */
  public function getOrderProductImage($orderId)
  {
    $order            = $this->_orderFactory->load($orderId);
    $orderItems       = $order->getAllItems();
    foreach ($orderItems as $item) {
      $productId    = $item->getProductId();
    }
    $image            = $this->getProductImage($productId);
    return $image ;
  }

  /**
   * get Product image
   * @return string
   */
  public function getProductImage($productId)
  {   
    $store            = $this->_storeManager->getStore();
    $mediaPath        = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    $_product         = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
    if ($_product->getSmallImage() != null && $_product->getSmallImage() != "no_selection") {
        return $mediaPath.'catalog/product' .$_product->getImage() ; 
      } else {
        return $this->_helperImage->getDefaultPlaceholderUrl('small_image'); 
      }
  }

  /**
   * get Product Name
   * @return string
   */
  public function getProductName($productId)
  {
    $_product         = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
    return $_product->getName();
  }

  /**
   * get date and time
   * @return string
   */
  public function getDateAndTime($dateTime)
  {
    $day              = date('l', strtotime($dateTime));
    $time             = date("H:i",strtotime($dateTime));
    $date             = date('jS F Y',strtotime($dateTime)); 
    return $day.' '.$date.' at '.$time;
  }

  /**
   * get is_read field value
   * @return string
   */
  public function getIsReadValue($currentCustomerId,$customerGroupNotificId)
  {
    $readValue = 0;
    $filterCollection = $this->_objectManager->create('\Ksolves\Notifications\Model\ResourceModel\CustomerAdminNotifications\Collection')->addFieldToFilter('customer_id',$currentCustomerId)->addFieldToFilter('group_notifications_id',$customerGroupNotificId)->addFieldToFilter('status',1);
    foreach ($filterCollection as $data) {
      $readValue = $data->getIsRead();
    }
    return $readValue;
  }

  /**
   * redirect contoller to home 
   * @return void
   */
  public function getRedirect()
  {
    $store = $this->_storeManager->getStore();
    $redirect = $this->_objectManager->get('\Magento\Framework\App\Response\Http');
    $redirect->setRedirect($store->getBaseUrl());
    return ;
  }

  /**
   * custom message filter
   * @return string
   */
  public function getMsgByFilter($msg)
  {
    $store = $this->_storeManager->getStore();
    $customMsg =  $this->_filterProvider->getPageFilter()->filter($msg);
      $ksCustomMsg = filter_var($customMsg, FILTER_SANITIZE_STRING) ;
    if (strlen($ksCustomMsg) > 105) {
        $stringCut = substr($ksCustomMsg, 0, 105);
        $endPoint  = strrpos($stringCut, ' ');
        $ksCustomMsg    = $endPoint? substr($stringCut, 0, $endPoint):substr($stringCut, 0);
        $ksCustomMsg .= '... <a href="'.$store->getBaseUrl().'notifications/index/customnotific'.'">Read More</a>';
    }
    return  $ksCustomMsg;
  }

  /**
   * get custom notifications product image
   * @return string
   */
  public function getCustomImage($image)
  {
    $store            = $this->_storeManager->getStore();
    $mediaPath        = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'ksolves/notifications/image/';
    if (isset($image)) {
        return $mediaPath.$image;
    } else {
      return $this->_helperImage->getDefaultPlaceholderUrl('small_image');    
    }
  }
}
