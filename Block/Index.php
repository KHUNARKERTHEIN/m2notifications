<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block;

class Index extends \Magento\Framework\View\Element\Template
{
	/**
	 * ConfigData class object
	 * @var \Ksolves\Notifications\Helper\ConfigData
     */
	protected $_configData;

	/**
	 * @var \Ksolves\Notifications\Helper\CollectionsData
     */
	protected $_collectionsData;

	/**
	 *  objectmanager
	 * @var \Magento\Framework\App\ObjectManager 
	 */
	protected $_objectManager;

	/**
     * @param \Magento\Framework\App\ObjectManager $objectmanager
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Ksolves\Notifications\Helper\ConfigData $configData
     * @param \Ksolves\Notifications\Helper\CollectionsData $CollectionsData
     */
	public function __construct(
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Magento\Framework\ObjectManagerInterface $objectManager,
	    \Ksolves\Notifications\Helper\ConfigData $configData,
	    \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
	    array $data = []
	) {
		$this->_configData          = $configData;
		$this->_collectionsData     = $collectionsData;
		$this->_objectManager       = $objectManager;
	    parent::__construct($context, $data);
	}

	/**
	 * Get Wishlist Collection
	 * @return array
	 */
	public function getWishlistCollections($currentCustomerId)
	{ 
		return $this->_collectionsData->getWishlistCollections($currentCustomerId)->setOrder('created_at','DESC')->setPageSize(5);
	}

	/**
	 * Get Order collection
	 * @return array
	 */
	public function getOrdersCollections($currentCustomerId)
	{
		return $this->_collectionsData->getOrdersCollections($currentCustomerId)->setOrder('created_at','DESC')->setPageSize(4);
	}

	/**
	 * Get Custom collections
	 * @return array
	 */
	public function getCustomCollections($currentCustomerGroup)
	{
		return $this->_collectionsData->getCustomCollections($currentCustomerGroup)->setOrder('created_at','DESC')->setPageSize(4);
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
	 * get Order last added product image
	 * @return string
	 */
	public function getOrderProductImage($orderId)
	{
		return $this->_collectionsData->getOrderProductImage($orderId); 
	}

	/**
	 * get wishlist added product image
	 * @return string
	 */
	public function getWishlistProductImage($productId)
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
	 * enable or disable the Notifications Module
	 * @return int
	 */
	public function enableNotificationModule()
	{
		return $this->_configData->getNotificationsConfig('enable'); 
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
	 * get custom message
	 * @return string
	 */
	public function getMsgByFilter($msg)
    {
    	return $this->_collectionsData->getMsgByFilter($msg);
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
   	 * get is_read field value
     * @return string
     */
    public function getIsReadValue($currentCustomerId,$customerGroupNotificId)
    {
    	return $this->_collectionsData->getIsReadValue($currentCustomerId,$customerGroupNotificId);
  	}

  	/**
	 * redirect contoller to home 
	 * @return void
	 */
    public function getRedirect()
    {
		return $this->_collectionsData->getRedirect();
    }
}
