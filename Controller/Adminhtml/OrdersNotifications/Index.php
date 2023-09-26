<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml\OrdersNotifications;

/**
 * Notifications OrdersNotifications list controller
 */
class Index extends \Ksolves\Notifications\Controller\Adminhtml\OrdersNotifications
{
    /**
     * OrdersNotifications list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ksolves_Notifications::OrdersNotifications');
        $resultPage->getConfig()->getTitle()->prepend(__('Orders Notifications'));
        $resultPage->addBreadcrumb(__('OrdersNotifications'), __('OrdersNotifications'));
        $resultPage->addBreadcrumb(__('OrdersNotifications'), __('OrdersNotifications'));
        return $resultPage;
    }
}