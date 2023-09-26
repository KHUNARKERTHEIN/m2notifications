<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\ResourceModel;

/**
 * Notifications CustomNotifications resource model
 */
class CustomNotifications extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('ksolves_custom_notifications', 'custom_notification_id');   //here "ksolves_custom_notifications" is table name and "custom_notification_id" is the primary key of custom table
    }
}