<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block;

/**
 * Notifications CustomNotific block
 */
class CustomNotific extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Magento\Cms\Model\Template\FilterProvider
     */
	protected $_filterProvider;

	/**
	 * @var \Ksolves\Notifications\Helper\CollectionsData
     */
	protected $_collectionsData;

	/**
	 * @var \Ksolves\Notifications\Helper\ConfigData
     */
	protected $_configData;

	/**
	 * @var \Ksolves\Notifications\Model\CustomNotifications
     */
	protected $_customCollections;

	/**
	 *  objectmanager
	 * @var \Magento\Framework\App\ObjectManager 
	 */
	  protected $_objectManager;

	/**
     * @param \Magento\Framework\App\ObjectManager $objectmanager
     * @param \Ksolves\Notifications\Helper\CollectionsData $CollectionsData
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Ksolves\Notifications\Model\CustomNotifications $customCollections
     */
	public function __construct(
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
	    \Ksolves\Notifications\Helper\ConfigData $configData,
	    \Magento\Framework\ObjectManagerInterface $objectManager,
	    \Ksolves\Notifications\Model\CustomNotifications $customCollections,
	    array $data = []
	) {
		$this->_configData          = $configData;
		$this->_collectionsData     = $collectionsData;
		$this->_filterProvider      = $filterProvider;
		$this->_objectManager       = $objectManager;
        $this->_customCollections   = $customCollections;
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
	    $this->pageConfig->getTitle()->set(__('Custom Notifications'));
	    if ($this->getCustomCollections()) {
	        $pager = $this->getLayout()->createBlock(
	            'Magento\Theme\Block\Html\Pager',
	            'ksolves.custom.notifications.pager'
	        )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
	            $this->getCustomCollections()
	        );
	        $this->setChild('pager', $pager);
	        $this->getCustomCollections()->load();
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
     * Retrieve Custom collection 
     *
     * @return array
     */
	public function getCustomCollections()
	{
		$customerGroup   = $this->getCustomerGroup();
		$page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;       
		$customCollections = $this->_customCollections->getCollection()
                            ->addFieldToFilter('customer_group',$customerGroup)
                            ->addFieldToFilter('status',1)
                            ->setOrder('created_at','DESC');
        $customCollections->setPageSize($pageSize) ;   
        $customCollections->setCurPage($page);
      	return $customCollections;
	}

    /**
	 * current Customer group
	 * @return string
	 */
	public function getCustomerGroup()
	{
	        /*    Get customer groups data      */
	    $customerGroupCollection = $this->_objectManager->create('\Magento\Customer\Model\Group');
        $customerSession         = $this->_objectManager->create('Magento\Customer\Model\Session');
	    if($customerSession->isLoggedIn())
	    {
	        $currentGroupId      = $customerSession->getCustomer()->getGroupId();
	        $collection          = $customerGroupCollection->load($currentGroupId); 
	        $currentCustomerGroup= $collection->getCustomerGroupCode();
	        return $currentCustomerGroup;
		}
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
	 * filter the admin custom message data
	 * @return string
	 */
	public function getMsgByFilter($msg)
    {
   	 	$customMsg =  $this->_filterProvider->getPageFilter()->filter($msg);
   	 	return filter_var($customMsg, FILTER_SANITIZE_STRING) ;
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
     * Current Customer Id 
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

    /**
	 * enable or disable the Notifications Module
	 * @return int
	 */
	public function enableNotificationModule()
	{
		return $this->_configData->getNotificationsConfig('enable'); 
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
