<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;

/**
 * All Notifications page view
 */
class Index extends \Magento\Framework\App\Action\Action
{
	/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct( 
        \Magento\Framework\App\Action\Context $context,      
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

 	/**
     * Show All Notifications
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
 	public function execute()
 	{
    $this->resultPage = $this->resultPageFactory->create(); 
    $this->resultPage->getConfig()->getTitle()->set((__('Notifications'))); 
      return $this->resultPage;
    }
 }
 
