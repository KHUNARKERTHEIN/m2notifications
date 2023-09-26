<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Thumbnail
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     *  helper image class object 
     * @var \Magento\Framework\App\ObjectManager 
     */
    protected $_helperImage;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Catalog\Helper\Image $helperImage
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\Image $helperImage,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->_helperImage         = $helperImage;
        $this->urlBuilder           = $urlBuilder;
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);    
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $path = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ).'ksolves/notifications/image/';
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['custom_image']) {
                    $item[$fieldName . '_src'] = $path.$item['custom_image'];
                    $item[$fieldName . '_alt'] = 'Custom Image';
                    $item[$fieldName . '_orig_src'] = $path.$item['custom_image'];
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'ksolves_notifications/customNotifications/edit',
                    ['id' => $item['custom_notification_id']]
                    );
                } else {
                    $item[$fieldName . '_src'] = $this->_helperImage->getDefaultPlaceholderUrl('small_image');    
                    $item[$fieldName . '_alt'] = 'Custom Image';
                    $item[$fieldName . '_orig_src'] = $this->_helperImage->getDefaultPlaceholderUrl('small_image');    
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'ksolves_notifications/customNotifications/edit',
                    ['id' => $item['custom_notification_id']]
                    );
                }
            }
        }

        return $dataSource;
    }
}