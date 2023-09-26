<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml\CustomNotifications;

/**
 * Notifications CustomNotifications delete controller
 */
class Delete extends \Ksolves\Notifications\Controller\Adminhtml\CustomNotifications
{
    /**
     * delete action
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Ksolves\Notifications\Model\CustomNotifications');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('ksolves_notifications/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_redirect('ksolves_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('ksolves_notifications/*/');
    }
}
