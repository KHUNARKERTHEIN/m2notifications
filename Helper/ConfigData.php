<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Deprecated!!!
 * Ksolves Notifications Config Helper
 */
class ConfigData extends AbstractHelper
{
	const XML_PATH_NOTIFICATION = 'ks_notifications/';

 	/**
     * @return Magento\Store\Model\ScopeInterface
     */
	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

	/**
     * @return string
     */
	public function getNotificationsConfig($code, $storeId = null)
	{
	
		return $this->getConfigValue(self::XML_PATH_NOTIFICATION .'notificationsconfig/'. $code, $storeId);
	}

}
