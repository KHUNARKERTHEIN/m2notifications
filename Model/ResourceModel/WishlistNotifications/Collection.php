<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Model\ResourceModel\WishlistNotifications;

/**
 * Notifications WishlistNotifications collection
 */ 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'wishlist_notification_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Ksolves\Notifications\Model\WishlistNotifications',
            'Ksolves\Notifications\Model\ResourceModel\WishlistNotifications'
        );
    }
}