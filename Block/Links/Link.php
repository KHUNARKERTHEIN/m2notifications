<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block\Links;

/**
 * Class Link block
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
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
		$this->_assetRepo                   = $context->getAssetRepository();
		$this->_configData                  = $configData;
		$this->_collectionsData             = $collectionsData;
	    parent::__construct($context, $data);
	}
	/**
	 * Render block HTML.
	 * @return string
	 */
	public function link()
	{
		$store = $this->_storeManager->getStore();
        $mediaPath = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$ks_enable = $this->_configData->getNotificationsConfig('enable'); 
		if ($ks_enable == 1) {
			$logoImage    = $this->_assetRepo->getUrl('Ksolves_Notifications::images/notification_icon.jpg');	
			$ks_image 	  = $this->_configData->getNotificationsConfig('notifications_image'); 
			if ($ks_image == '') {
				$result='<li class="popup"><img src="'.$logoImage.'" alt="notifications" style="width:20px;height:20px" /><span id="ks_count" ></span></li>';
			} else {
				$result='<li class="popup"><img src="'.$mediaPath.'ks/logo/images/'.$ks_image.'" alt="notifications" style="width:20px;height:20px" /><span id="ks_count" ></span></li>';	
			}    	
	     	return $result ;   
		}
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

}