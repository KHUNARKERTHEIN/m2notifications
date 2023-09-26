<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class WishlistNotifications model
 */
class WishlistNotifications extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Ksolves\Notifications\Model\ResourceModel\WishlistNotifications');
    }
}