<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block;

/**
 * Notifications WishlistNotific block
 */
class WishlistNotific extends \Magento\Framework\View\Element\Template
{
	/**
	 *  objectmanager
	 * @var \Magento\Framework\App\ObjectManager 
	 */
	protected $_objectManager;

	/**
	 * @var \Ksolves\Notifications\Helper\CollectionsData 
	 */
	protected $_collectionsData;

	/**
	 * @var \Ksolves\Notifications\Model\WishlistNotifications 
	 */
	protected $_wishlistCollections;

	/**
     * @param \Magento\Framework\App\ObjectManager $objectmanager
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Ksolves\Notifications\Helper\CollectionsData $CollectionsData
     * @param \Ksolves\Notifications\Model\WishlistNotifications $wishlistCollections
     */
	public function __construct(
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Magento\Framework\ObjectManagerInterface $objectManager,
	    \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
	    \Ksolves\Notifications\Helper\ConfigData $configData,
	    \Ksolves\Notifications\Model\WishlistNotifications $wishlistCollections,
	    array $data = []
	) {
		$this->_configData          = $configData;
		$this->_collectionsData     = $collectionsData;
		$this->_objectManager       = $objectManager;
		$this->_wishlistCollections = $wishlistCollections;
	    parent::__construct($context, $data);
	}

	/**
     * Preparing global layout
     *
     * @return $this
     */
	protected function _prepareLayout()
	{
	    parent::_prepareLayout();
	    $this->pageConfig->getTitle()->set(__('Wishlist Notifications'));        
	    if ($this->getWishlistCollections()) {
	        $pager = $this->getLayout()->createBlock(
	            'Magento\Theme\Block\Html\Pager',
	            'ksolves.notifications.pager'
	        )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
	            $this->getWishlistCollections()
	        );
	        $this->setChild('pager', $pager);
	        $this->getWishlistCollections()->load();
	    }
	    return $this;
	}

	/**
     * pagination 
     *
     * @return array
     */
	public function getPagerHtml()
	{
	    return $this->getChildHtml('pager');
	}

	/**
     * Retrieve Wishlist collection 
     *
     * @return array
     */
	public function getWishlistCollections()
	{
		$currentCustomerId =$this->getCustomerId();
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;                 
	/*	 $wishlistCollections = $this->_wishlistCollections->create();*/
		 $wishlistCollections = $this->_wishlistCollections->getCollection()
                            ->addFieldToFilter('customer_id',$currentCustomerId)
                            ->setOrder('created_at','DESC');  
        $wishlistCollections->setPageSize($pageSize) ;      
        $wishlistCollections->setCurPage($page);
    	return $wishlistCollections;
	}

    /**
     * Current Customer id
     * @return int
     */
    public function getCustomerId()
    {
        /*    Get customer Sessions data      */
        $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
        if($customerSession->isLoggedIn())
	    {
	        $currentCustomerId   = $customerSession->getCustomer()->getId();
	        return $currentCustomerId;  
	    }
	}

	/**
     * Current Customer name
     * @return int
     */
    public function getCustomerName()
    {
        /*    Get customer Sessions data      */
        $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
        if($customerSession->isLoggedIn())
	    {
	        $currentCustomerName = $customerSession->getCustomer()->getName() ;
	        return $currentCustomerName;  
	    }
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
	 * get date and time
	 * @return string
	 */
    public function getDateAndTime($dateTime)
    {
   		return $this->_collectionsData->getDateAndTime($dateTime);
    }

    /**
	 * redirect contoller to home 
	 * @return void
	 */
    public function getRedirect()
    {
		return $this->_collectionsData->getRedirect();
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
	 * enable or disable the wishlist Notifications
	 * @return int
	 */
	public function enableWishlistNotification()
	{
		return $this->_configData->getNotificationsConfig('enable_wishlistStatus');
	}

}
