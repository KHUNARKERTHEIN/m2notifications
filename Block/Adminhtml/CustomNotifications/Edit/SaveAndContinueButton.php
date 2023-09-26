<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Block\Adminhtml\CustomNotifications\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * SaveAndContinueButton class
 */
class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->_isAllowedAction('Ksolves_Notifications::custom_notification_create') || $this->_isAllowedAction('Ksolves_Notifications::custom_notification_update')) {
            $data = [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit'],
                    ],
                ],
                'sort_order' => 80,
            ];
        }
        return $data;
    }
}
