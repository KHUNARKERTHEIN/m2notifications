<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class OrdersNotifications model
 */
class OrdersNotifications extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Ksolves\Notifications\Model\ResourceModel\OrdersNotifications');
    }
}