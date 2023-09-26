<?php
 /**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Model\CustomNotifications;

use Ksolves\Notifications\Model\ResourceModel\CustomNotifications\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * DataProvider class
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
        $this->storeManager = $storeManager;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $path = $this->storeManager->getStore()->getBaseUrl(
        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'ksolves/notifications/image/';
        foreach ($items as $item) {
            
            $customNotificationsData = $item->getData();
            if ($item->getCustomImage()) {
                $image['custom_image'][0]['name'] = $item->getCustomImage();
                $image['custom_image'][0]['url'] = $path.$item->getCustomImage();
                $customNotificationsData = array_merge($customNotificationsData, $image);
            }
                 $this->loadedData[$item->getId()] = $customNotificationsData;
        }
        return $this->loadedData;
    }
}
