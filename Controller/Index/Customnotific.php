<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;

/**
 * All Custom Notifications page view
 */
class Customnotific extends \Magento\Framework\App\Action\Action
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
     * Show All Custom Notifications
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
 	public function execute()
 	{
       $this->resultPage = $this->resultPageFactory->create();  
       return $this->resultPage;
    }
 }
 
