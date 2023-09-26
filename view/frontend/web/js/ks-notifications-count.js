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
     * ksNotificationsCount Widget
     */
    $.widget('mage.ksNotificationsCount', {
        options:
        {
            urlToUpdateCount: 'url'
        },

        /**
         * @return void
         */
        _create: function () {
            url = this.options.urlToUpdateCount;       
            $(".ks_count_update").on('click',function(){ 
                if ($(this).attr('data-orderId') || $(this).attr('data-shipmentId') || $(this).attr('data-invoiceId') || $(this).attr('data-creditmemoId') ) {
                    $.ajax({
                    url: url,
                    data:{
                        order_id      : $(this).attr('data-orderId'),
                        order_status  : $(this).attr('data-orderStatus'),
                        creditmemo_id : $(this).attr('data-creditmemoId'),
                        shipment_id   : $(this).attr('data-shipmentId'),
                        invoice_id    : $(this).attr('data-invoiceId'),
                        customer_id   : $(this).attr('data-customerId')
                    },
                    type:'POST'
                    }).done(function (response) {
                      true;
                    }).fail(function (error) { 
                        alert({
                        content: $.mage.__('Sorry, something went wrong.')
                        });
                    });
                }

                if ($(this).attr('data-wishlistNotificId')) {
                    $.ajax({
                    url: url,
                    data:{
                        wishlist_notic_id    : $(this).attr('data-wishlistNotificId'),
                        customer_id          : $(this).attr('data-customerId')
                    },
                    type:'POST'
                    });
                }

                if ($(this).attr('data-customNotificId')) {
                    $.ajax({
                    url: url,
                    data:{
                        custom_notific_id    : $(this).attr('data-customNotificId'),
                        customer_id          : $(this).attr('data-customerId')
                    },
                    type:'POST'
                    });
                }   
            });
        }        
    });
    return $.mage.ksNotificationsCount;
});
