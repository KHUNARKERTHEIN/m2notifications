<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml\WishlistNotifications;

/**
 * Notifications WishlistNotifications list controller
 */
class Index extends \Ksolves\Notifications\Controller\Adminhtml\WishlistNotifications
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
        $resultPage->setActiveMenu('Ksolves_Notifications::WishlistNotifications');
        $resultPage->getConfig()->getTitle()->prepend(__('Wishlist Notifications'));
        $resultPage->addBreadcrumb(__('WishlistNotifications'), __('WishlistNotifications'));
        return $resultPage;
    }
}