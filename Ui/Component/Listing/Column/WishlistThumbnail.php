<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Ksolves\Notifications\Helper\CollectionsData;

/**
 * Class WishlistThumbnail
 */
class WishlistThumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * CollectionData class object
     * @var \Ksolves\Notifications\Helper\CollectionsData 
     */
    protected $_collectionsData;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CollectionsData $collectionsData
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        CollectionsData $collectionsData,
        \Magento\Framework\UrlInterface $urlBuilder,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->context              = $context;
        $this->urlBuilder           = $urlBuilder;
        $this->_collectionsData     = $collectionsData;
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
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['product_id']) {
                    $item[$fieldName . '_src'] = $this->_collectionsData->getProductImage($item['product_id']);
                    $item[$fieldName . '_orig_src'] = $this->_collectionsData->getProductImage($item['product_id']);
                    $item[$fieldName . '_alt'] = $item['product_name'];
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'catalog/product/edit',
                    ['id' => $item['product_id'], 'store' => $this->context->getRequestParam('store')]
                );
                }
            }
        }

        return $dataSource;
    }
}