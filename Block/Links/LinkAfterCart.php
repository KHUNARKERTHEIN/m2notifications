<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Block\Links;

/**
 * Class LinkAfterCart block
 */
class LinkAfterCart extends \Magento\Framework\View\Element\Html\Link
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
	public function ksLinkAfterCart()
	{
		$ks_store = $this->_storeManager->getStore();
        $mediaPath = $ks_store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$ks_enable = $this->_configData->getNotificationsConfig('enable'); 
		if ($ks_enable == 1) {
			$ks_logoImage    = $this->_assetRepo->getUrl('Ksolves_Notifications::images/notification_icon.jpg');	
			$ks_image 	  = $this->_configData->getNotificationsConfig('notifications_image'); 
			if ($ks_image == '') {
				$result='<div class="icon-after-cart"><img src="'.$ks_logoImage.'" alt="notifications" style="width:20px;height:20px" /><span id="link_ks_count" ></span></div>';
			} else {
				$result='<div class="icon-after-cart"><img src="'.$mediaPath.'ks/logo/images/'.$ks_image.'" alt="notifications" style="width:20px;height:20px" /><span id="link_ks_count" ></span></div>';	
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