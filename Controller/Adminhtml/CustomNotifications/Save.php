<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml\CustomNotifications;

/**
 * Notifications CustomNotifications save controller
 */
class Save extends \Ksolves\Notifications\Controller\Adminhtml\CustomNotifications
{
    /**
     * save action 
     * @return void
     */
    public function execute()
    {
        // get data from admin form
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                /* load model class */
                $model = $this->_objectManager->create('Ksolves\Notifications\Model\CustomNotifications');

                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                // get id
                $id = $this->getRequest()->getParam('custom_notification_id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                // get and set image field
                $data['custom_image'] = (isset($data['custom_image']) && !empty($data['custom_image'])) ? $data['custom_image'][0]['name'] : null;
                
                $customerGroupId = $this->getRequest()->getParam('customer_group_id');
                $customMessageStatus = $this->getRequest()->getParam('status');
                // get customer group collection
                $customerGroupData = $this->_objectManager->create('\Magento\Customer\Api\GroupRepositoryInterface');
                $group = $customerGroupData->getById($customerGroupId);
                $data['customer_group'] = $group->getCode();
                $data['created_at'] = $this->timezone->date(); 
                $data['updated_at'] = $this->timezone->date(); 
                if (!$data['custom_notification_id']) {
                    unset($data['custom_notification_id']);
                }

                unset($data['form_key']);
                // filter collection of customer admin notifications
                 $customerCollection = $this->_customerCollections->addFieldToFilter("group_id",$customerGroupId);
    
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                
                if (!$model->getId()) {
                   $CustomNotificationsid = 1;
                } else {
                    $CustomNotificationsid = $model->getId();  
                }
                // set data for getting custom notifications count
                foreach ($customerCollection as $data) {
                    $notifications = $this->_objectManager->create('Ksolves\Notifications\Model\CustomerAdminNotifications');
                    $notifications->setCustomerId($data->getId());
                    $notifications->setStatus($customMessageStatus);
                    $notifications->setCreatedAt($this->timezone->date());       
                    $notifications->setGroupNotificationsId($CustomNotificationsid);
                    $notifications->setCustomerGroupId($customerGroupId);
                    $notifications->save();
                } 
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('ksolves_notifications/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('ksolves_notifications/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('ksolves_notifications/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('ksolves_notifications/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('ksolves_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('ksolves_notifications/*/');           
    }
}

