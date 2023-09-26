<?php 
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */
namespace Ksolves\Notifications\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
/**
 * Notifications PopupData 
 */
class PopupData extends \Magento\Framework\App\Action\Action
{
  /**
   * @var \Magento\Framework\Controller\Result\JsonFactory
   */
  protected $jsonFactory;

  /**
   * @param \Magento\Framework\App\Action\Context $context
   * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
   */
  public function __construct(       
    Context $context,
    JsonFactory $jsonFactory
  )
  {
    $this->jsonFactory = $jsonFactory;
    parent::__construct($context);
  }

  /**
   * Notifications  action
   *
   * @return \Magento\Framework\View\Result\PageFactory
   */
  public function execute()
  {
    $html = $this->_view->getLayout()->createBlock(
        'Ksolves\Notifications\Block\PopupData'
    )->toHtml();
    /** @var \Magento\Framework\Controller\Result\Json $resultJson */
    $resultJson = $this->jsonFactory->create();
    return $resultJson->setData($html); 
  }
}