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

namespace Ksolves\Notifications\Controller\Wishlist\Index;

use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Helper\Data as WishlistHelper;


/**
 * KsWishlistFromcart class
 */
class KsWishlistFromcart extends \Magento\Wishlist\Controller\AbstractIndex implements Action\HttpPostActionInterface
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var WishlistHelper
     */
    protected $wishlistHelper;

    /**
     * @var CheckoutCart
     */
    protected $cart;

    /**
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @param Action\Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param WishlistHelper $wishlistHelper
     * @param CheckoutCart $cart
     * @param CartHelper $cartHelper
     * @param Escaper $escaper
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Action\Context $context,
        \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistItemCollection,
        \Ksolves\Notifications\Model\WishlistNotificationsFactory $wishlistNotificationsFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\Product $productCollection,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        WishlistProviderInterface $wishlistProvider,
        WishlistHelper $wishlistHelper,
        CheckoutCart $cart,
        CartHelper $cartHelper,
        Escaper $escaper,
        Validator $formKeyValidator
    ) {
        $this->wishlistProvider = $wishlistProvider;
        $this->_wishlistItemCollection  = $wishlistItemCollection;
        $this->_customerSession         = $customerSession;
        $this->_wishlistNotificationsFactory            = $wishlistNotificationsFactory;
        $this->timezone                 = $timezone;
        $this->_productCollection  = $productCollection;
        $this->wishlistHelper = $wishlistHelper;
        $this->cart = $cart;
        $this->cartHelper = $cartHelper;
        $this->escaper = $escaper;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Add cart item to wishlist and remove from cart
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    { 

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }

        $wishlist = $this->wishlistProvider->getWishlist();

        if (!$wishlist) {
            throw new NotFoundException(__('Page not found.'));
        }

        try {
            $itemId = (int)$this->getRequest()->getParam('item');
            $item = $this->cart->getQuote()->getItemById($itemId);
            if (!$item) {
                throw new LocalizedException(
                    __("The cart item doesn't exist.")
                );
            }
            
            $productId = $item->getProductId();
            $buyRequest = $item->getBuyRequest();
            $wishlist->addNewItem($productId, $buyRequest);

            $this->cart->getQuote()->removeItem($itemId);
            $this->cart->save();

            $this->wishlistHelper->calculate();
            $wishlist->save();

            /* condition to check wishlist id */
            if ($wishlist->getId()) {
                 /* Get wishlist item collection */
                $wishlistObj =$this->_wishlistItemCollection->addFieldToFilter('wishlist_id',$wishlist->getId())->addFieldToFilter('product_id',$item->getProductId());
                foreach ($wishlistObj as $key => $value) {
                    $wishlistItemId = $value['wishlist_item_id'];
                }
                if ($item->getProductId()) {
                 $ks_productName = $this->_productCollection->load($item->getProductId())->getName();
                }
                if ($this->_customerSession->isLoggedIn()) {
                   /* save the records in ksolves_wishlist_notifications table */
                    $model = $this->_wishlistNotificationsFactory->create();
                    $model->setCustomerName($this->_customerSession->getCustomer()->getName());
                    $model->setCustomerId($this->_customerSession->getCustomer()->getId());
                    $model->setProductId($item->getProductId()); 
                    $model->setProductName($ks_productName); 
                    $model->setWishlistItemId($wishlistItemId); 
                    $model->setCreatedAt($this->timezone->date()); 
                    $model->save();
                }   
            }

            $this->messageManager->addSuccessMessage(__(
                "%1 has been moved to your wish list.",
                $this->escaper->escapeHtml($item->getProduct()->getName())
            ));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t move the item to the wish list.'));
        }
        return $resultRedirect->setUrl($this->cartHelper->getCartUrl());
    }
}
