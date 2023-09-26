<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Adminhtml\CustomNotifications;

/**
 * Notifications CustomNotifications Edit controller
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /* Get id from admin grid */
        $id = $this->getRequest()->getParam('id');

        /* load model class */
        $model = $this->_objectManager->create('Ksolves\Notifications\Model\CustomNotifications');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('ksolves_notifications/*');
                return;
            }
        }
        // set data in registry
        $this->_coreRegistry->register('custom_notifications', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(
            'Ksolves_Notifications::CustomNotifications'
        )->addBreadcrumb(
            __('Custom Notifications'), __('Custom Notifications')
        )->addBreadcrumb(
            __('Custom Notifications'), __('Custom Notifications')
        )->addBreadcrumb(
            $id ? __('Edit Custom Notification') : __('Add Custom Notification'),
            $id ? __('Edit Custom Notification') : __('Add Custom Notification')
        );

        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Update Custom Notifications') : __('Add Custom Notification'));
        return $resultPage;
    }

    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ksolves_Notifications::custom_notification_create') || $this->_authorization->isAllowed('Ksolves_Notifications::custom_notification_read');
    }
}
