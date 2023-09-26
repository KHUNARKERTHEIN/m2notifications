<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml\CustomNotifications;

/**
 * Notifications CustomNotifications NewAction controller
 */
class NewAction extends \Ksolves\Notifications\Controller\Adminhtml\CustomNotifications
{
	/**
     * forward to edit controller 
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
