<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block;

/**
 * Class PopupData block
 */
class PopupData extends \Magento\Framework\View\Element\Template
{
	protected $_template = 'Ksolves_Notifications::popup_notifications.phtml';
	/**
	 * ConfigData class object
	 * @var \Ksolves\Notifications\Helper\ConfigData
     */
	protected $_configData;

	/**
	 * CollectionData class object
	 * @var \Ksolves\Notifications\Helper\CollectionsData 
     */
	protected $_collectionsData;

	/**
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Ksolves\Notifications\Helper\ConfigData $configData
     * @param \Ksolves\Notifications\Helper\CollectionsData $CollectionsData
     */
	public function __construct(
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Ksolves\Notifications\Helper\ConfigData $configData,
	    \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
	    array $data = []
	) { 
		$this->_configData                  = $configData;
		$this->_collectionsData             = $collectionsData;
	    parent::__construct($context, $data);
	}

	/**
	 * collections of all notifications 
	 * @return array
	 */
	public function getCollections($currentCustomerId , $currentCustomerGroup )
	{
		return $this->_collectionsData->getCollections($currentCustomerId , $currentCustomerGroup);
	}

	/**
	 * Customer groups collections
	 * @return array
	 */
	public function getCustomerGroups()
	{
	        /*    Get customer groups data      */
	    return $this->_collectionsData->getCustomerGroups();
	}

    /**
     * Customer groups collections
     * @return array
     */
    public function getCustomerSessions()
    {
        /*    Get customer Sessions data      */
         return $this->_collectionsData->getCustomerSessions();
    }

	/**
	 * get Order last added product image
	 * @return string
	 */
	public function getOrderProductImage($orderId)
	{
	    return $this->_collectionsData->getOrderProductImage($orderId);
	}

	/**
	 * get  product image
	 * @return string
	 */
	public function getProductImage($productId)
    {   
	    return $this->_collectionsData->getProductImage($productId);
    }

	/**
	 * get wishlist added product name
	 * @return string
	 */
	public function getWishlistProductName($productId)
	{
        return $this->_collectionsData->getProductName($productId);
	}

	/**
   	 * get custom notifications product image
     * @return string
     */
    public function getCustomImage($image)
    {
    	return $this->_collectionsData->getCustomImage($image);
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

	/**
	 * enable or disable the order Notifications
	 * @return int
	 */
	public function numberOfNotification()
	{
		return $this->_configData->getNotificationsConfig('no_of_notifications');
	}

    /**
   	 * get is_read field value
     * @return string
     */
    public function getIsReadValue($currentCustomerId,$customerGroupNotificId)
    {
    	return $this->_collectionsData->getIsReadValue($currentCustomerId,$customerGroupNotificId);
  	} 

   	/**
	 * custom message filter
	 * @return string
	 */
    public function getMsgByFilter($msg)
    {
    	return $this->_collectionsData->getMsgByFilter($msg);
    }

    /**
	 * get media path
	 * @return string
	 */
    public function getMediaPath()
    {
   		$store            = $this->_storeManager->getStore();
	    return $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

   	/**
	 * get date and time
	 * @return string
	 */
    public function getDateAndTime($dateTime)
    {
   		return $this->_collectionsData->getDateAndTime($dateTime);
    }

    /**
	 * redirect contoller to home 
	 * @return string
	 */
    public function getRedirect()
    {
    	$this->_collectionsData->getRedirect();
    }
}