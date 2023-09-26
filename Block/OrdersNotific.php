<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block;

/**
 * Notifications OrdersNotific block
 */
class OrdersNotific extends \Magento\Framework\View\Element\Template
{
	/**
	 * CollectionData class object
	 * @var \Ksolves\Notifications\Helper\CollectionsData 
     */
	protected $_collectionsData;

	/**
	 * ConfigDataclass object
	 * @var \Ksolves\Notifications\Helper\ConfigData 
     */
	protected $_configData;

	/**
	 * @var \Ksolves\Notifications\Model\OrdersNotifications
     */
	protected $_ordersCollections;

	/**
	 *  objectmanager
	 * @var \Magento\Framework\App\ObjectManager 
	 */
	protected $_objectManager;

	/**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\ObjectManager $objectmanager
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Ksolves\Notifications\Helper\CollectionsData $CollectionsData
     */
	public function __construct(
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Magento\Framework\ObjectManagerInterface $objectManager,
	    \Ksolves\Notifications\Helper\CollectionsData $collectionsData,
	    \Ksolves\Notifications\Helper\ConfigData $configData,
	    \Ksolves\Notifications\Model\OrdersNotifications $ordersCollections,
	    array $data = []
	) {
		$this->_configData          = $configData;
		$this->_collectionsData     = $collectionsData;
		$this->_objectManager       = $objectManager;
        $this->_ordersCollections   = $ordersCollections;
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
	    $this->pageConfig->getTitle()->set(__('Order Notifications'));
	    if ($this->getOrdersCollections()) {
	        $pager = $this->getLayout()->createBlock(
	            'Magento\Theme\Block\Html\Pager',
	            'ksolves.orders.notifications.pager'
	        )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
	            $this->getOrdersCollections()
	        );
	        $this->setChild('pager', $pager);
	        $this->getOrdersCollections()->load();
	    }
	    return $this;
	}

	/**
     * pagination 
     *
     * @return string
     */
	public function getPagerHtml()
	{
	    return $this->getChildHtml('pager');
	}

	/**
     * Retrieve Orders collection 
     *
     * @return array
     */
	public function getOrdersCollections()
	{
		$currentCustomerId =$this->getCustomerId();
		$page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;  
		$ordersCollections = $this->_ordersCollections->getCollection()
                            ->addFieldToFilter('customer_id',$currentCustomerId)
                            ->setOrder('created_at','DESC');
        $ordersCollections->setPageSize($pageSize) ; 
        $ordersCollections->setCurPage($page);
        return $ordersCollections;
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
	 * get Order last added product image
	 * @return string
	 */
	public function getOrderProductImage($orderId)
	{
		return $this->_collectionsData->getOrderProductImage($orderId); 
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
	 * enable or disable the order Notifications
	 * @return int
	 */
	public function enableOrderNotification()
	{
		return $this->_configData->getNotificationsConfig('enable_orderStatus');
	}

}