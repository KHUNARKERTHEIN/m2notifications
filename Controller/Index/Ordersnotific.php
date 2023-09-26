<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;

/**
 * All Orders Notifications page view
 */
class Ordersnotific extends \Magento\Framework\App\Action\Action
{
	  /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
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
     * Show All Orders Notifications
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
 	public function execute()
 	{
       $this->resultPage = $this->resultPageFactory->create();  
       return $this->resultPage;
    }
 }
 
