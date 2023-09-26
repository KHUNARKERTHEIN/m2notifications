<?php
  /**
 * Ksolves
 *
 * @category  Ksolves
 * @package   Ksolves_Notifications
 * @author    Ksolves
 * @copyright Copyright (c) Ksolves Software Private Limited (https://www.ksolves.com/)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Ksolves\Notifications\Controller\Adminhtml\Sales\Order;

/**
 * KsSalesOrderUnhold class
 */
class KsSalesOrderUnhold extends \Magento\Sales\Controller\Adminhtml\Order\Unhold
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::unhold';

    /**
     * Unhold order
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->isValidPostRequest()) {
            $this->messageManager->addErrorMessage(__('Can\'t unhold order.'));
            return $resultRedirect->setPath('sales/*/');
        }
        $order = $this->_initOrder();
        if ($order) {
            try {

                if (!$order->canUnhold()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Can\'t unhold order.'));
                }
                $unhold = $this->orderManagement->unHold($order->getEntityId());
                /* condition to check the order is released from the holded status */
                if ($unhold) {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $ks_data = [];
            
                    $order =$objectManager->create('\Magento\Sales\Model\Order')->load($order->getEntityId());
                    if ($order->getCustomerId()) {
                        $ks_data['customer_id']   = $order->getCustomerId();
                    } else {
                        $ks_data['customer_id']   = null;   
                    }
                    
                    if ($ks_data['customer_id'] != null) {
                        $timezone = $objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
                        /* save the records in ksolves_orders_notifications table */
                         $model=$objectManager->create('\Ksolves\Notifications\Model\OrdersNotifications');
                         $model->setOrderStatus('Unholded');
                         $model->setOrderId($order->getEntityId());
                         $model->setCustomerId($ks_data['customer_id']);
                         $model->setCustomerName($order->getCustomerName());
                         $model->setCustomerEmail($order->getCustomerEmail());
                         $model->setIncrementId($order->getIncrementId());
                         $model->setCreatedAt($timezone->date());
                         $model->save();  
 
                        $this->messageManager->addSuccessMessage(__('You released the order from holding status.'));
                    }
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('The order was not on hold.'));
            }
            $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
            return $resultRedirect;
        }
        $resultRedirect->setPath('sales/*/');
        return $resultRedirect;
    }
}
