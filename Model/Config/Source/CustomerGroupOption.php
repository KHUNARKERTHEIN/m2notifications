<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\Config\Source;

/**
 *  CustomerGroupOption class
 *
 */
class CustomerGroupOption implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroup;

    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroups
     */
    function __construct(
       \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroups
    )
    {
      $this->_customerGroup     = $customerGroups;
    }

    /**
     * return customer groups 
     * @return array
     */
    public function toOptionArray()
    {
        $customerGroup = $this->_customerGroup;
        $options = array();
        foreach ($customerGroup as $data)
        {
            if ($data->getCode() == 'NOT LOGGED IN') {
                continue;
            }
           $options[] = array('value' => $data->getId(), 'label' => $data->getCode());
        }
        return $options;
    }
}
