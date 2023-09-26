<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\ResourceModel;

/**
 * Notifications CustomerAdminNotifications resource model
 */
class CustomerAdminNotifications extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('ksolves_customer_admin_notifications', 'customer_notification_id');   //here "ksolves_customer_admin_notifications" is table name and "customer_notification_id" is the primary key of custom table
    }
}