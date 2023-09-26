<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Controller\Adminhtml;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
/**
 * CustomNotifications controller
 */
abstract class CustomNotifications extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Customer\Model\ResourceModel\Customer\Collection
     */
    protected $_customerCollections;
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \Magento\Framework\Filesystem 
     */    
    protected $filesystem;

    /**
     * @var \Magento\Framework\Filesystem 
     */    
    protected $_file;

    /**
     *  helper image class object 
     * @var \Magento\Framework\App\ObjectManager 
     */
    protected $_helperImage;

    /**
     * Initialize Group Controller
     *
     * @param \Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollections
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\Framework\Filesystem $filesystem $filesystem
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     * @param \Magento\Catalog\Helper\Image $helperImage
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollections,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Helper\Image $helperImage,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->_customerCollections         = $customerCollections;
        $this->timezone                     = $timezone;
        $this->_coreRegistry                = $coreRegistry;
        $this->_helperImage                 = $helperImage;
        $this->_resultRedirectFactory       = $resultRedirectFactory;
        $this->resultForwardFactory         = $resultForwardFactory;
        $this->resultPageFactory            = $resultPageFactory;
        $this->directoryList                = $directoryList;
        $this->uploaderFactory              = $uploaderFactory;
        $this->adapterFactory               = $adapterFactory;
        $this->filesystem                   = $filesystem;
        $this->_file                        = $file;
        parent::__construct($context);
    }

    /**
     * Initiate action
     *
     * @return this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Ksolves_Notifications::CustomNotifications')->_addBreadcrumb(__('CustomNotifications'), __('CustomNotifications'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ksolves_Notifications::CustomNotifications');
    }
}