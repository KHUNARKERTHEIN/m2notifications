/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/apply/main',
    'mage/translate',
    'jquery/ui',

], function ($, alert, mage) {

    /**
     * ksViewAllNotificationsCount widget
     */
    $.widget('mage.ksViewAllNotificationsCount', {
        options:
        {
            urlToUpdateCount: 'url',
            customerId : '1'
        },

        /**
         * @return void
         */
        _create: function () {
            url = this.options.urlToUpdateCount; 
            current_customer_id = this.options.customerId;

            $(".ks_update_count").on('click',function(){      
                $.ajax({
                url: url,
                data:{
                        custom_notific_id    : $(this).attr('data-customNotificId'),
                        order_id             : $(this).attr('data-orderId'),
                        order_status         : $(this).attr('data-orderStatus'),
                        wishlist_notic_id    : $(this).attr('data-wishlistNotificId'),
                        creditmemo_id        : $(this).attr('data-creditmemoId'),
                        shipment_id          : $(this).attr('data-shipmentId'),
                        invoice_id           : $(this).attr('data-invoiceId'),
                        customer_id          : current_customer_id 
                    },
                type:'POST'
                }); 


            });
        }
            
    });
    return $.mage.ksViewAllNotificationsCount;
});
