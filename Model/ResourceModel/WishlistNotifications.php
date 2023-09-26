<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\ResourceModel;

/**
 * Notifications WishlistNotifications resource model
 */
class WishlistNotifications extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('ksolves_wishlist_notifications', 'wishlist_notification_id');   //here "ksolves_wishlist_notifications" is table name and "wishlist_notification_id" is the primary key of custom table
    }
}