<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Adminhtml\CustomNotifications;

/**
 * Notifications CustomNotifications Index controller
 */
class Index extends \Ksolves\Notifications\Controller\Adminhtml\CustomNotifications
{
    /**
     * Notifications list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ksolves_Notifications::CustomNotifications');
        $resultPage->getConfig()->getTitle()->prepend(__('Custom Notifications'));
        $resultPage->addBreadcrumb(__('CustomNotifications'), __('CustomNotifications'));
        return $resultPage;
    }
}