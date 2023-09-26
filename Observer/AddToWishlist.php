<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Wishlist add product action observer
 */
class AddToWishlist implements ObserverInterface
{
    /**
     * Request Interface
     * 
     * @var Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * Customer Session Model Factory
     * 
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
     
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     *  WishlistNotifications Model Factory
     * 
     * @var \Ksolves\Notifications\Model\WishlistNotifications
     */
    protected $_wishlistData;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Item\Collection
     */
    protected $_wishlistItemCollection;


    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistItemCollection
     * @param \Ksolves\Notifications\Model\WishlistNotifications $wishlistNotifications
     * @param RequestInterface $request
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistItemCollection,
        \Ksolves\Notifications\Model\WishlistNotifications $wishlistNotifications,
        RequestInterface $request
    ) {
        $this->timezone                 = $timezone;
        $this->_request                 = $request;
        $this->_customerSession         = $customerSession;
        $this->_wishlistItemCollection  = $wishlistItemCollection;
        $this->_wishlistData            = $wishlistNotifications;
    }
 
    /**
     * Get Wishlist Product
     *
     * @param \Magento\Framework\Event\Observer $observer
     * 
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product               = $observer->getProduct();
        $wishlistId            = $observer->getEvent()->getWishlist()->getId();
        $productId             = $product->getEntityId();
        $productName           = $product->getName();
        /* Get wishlist item collection */
        $wishlistObj           = $this->_wishlistItemCollection->addFieldToFilter('wishlist_id',$wishlistId)                     ->addFieldToFilter('product_id',$productId);
        $wishlistItemId = '';  
        foreach ($wishlistObj as $key => $value) {
            $wishlistItemId = $value['wishlist_item_id'];
        }
        /* current customer data */
        if ($this->_customerSession->isLoggedIn()) {
            $customerId        = $this->_customerSession->getCustomer()->getId();
            $customerName      = $this->_customerSession->getCustomer()->getName();
        }
        
        $data                  = $this->_wishlistData->setCustomerName($customerName);
        $data                  = $this->_wishlistData->setCustomerId($customerId);
        $data                  = $this->_wishlistData->setProductId($productId); 
        $data                  = $this->_wishlistData->setProductName($productName); 
        $data                  = $this->_wishlistData->setWishlistItemId($wishlistItemId); 
        $data                  = $this->_wishlistData->setCreatedAt($this->timezone->date()); 
        $data->save();
    }
}