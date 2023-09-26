<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\ResourceModel;

/**
 * Notifications OrdersNotifications resource model
 */
class OrdersNotifications extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('ksolves_orders_notifications', 'order_notification_id');   //here "ksolves_orders_notifications" is table name and "order_notification_id" is the primary key of custom table
    }
}