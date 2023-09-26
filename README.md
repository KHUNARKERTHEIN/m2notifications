# Magento 2 Notifications

Overview: As a Magento 2 store admin, you always wish to stay updated about the latest information on the events happening in your store. Ksolves comes up with a Magento notifications module for every group of customers like general, wholesaler, guest customer, and retailer. 

Features and Capabilities :

 Custom notification alerts: When the customer creates an order, adds products in the wishlist, or shipping of the product is initiated, an invoice is generated, a credit memo is generated. All custom notifications provided by show in the pop-up box to the customers. 

 Order Notifications: Customers can see various notifications related to their order, wishlist and custom notifications that are sent by the admin.  

Features available for admin:

1. Notification module :
	Enable / Disable the notifications module.

2. Order notifications :
	Enable / Disable the notifications related to orders.

3. Wishlist Notifications :
	Enable / Disable notifications related to the wishlist products.

4. Admin Notifications :
    Enable / Disable the custom notifications sent by admin.

5. Customize notifications :
	Enter the number of notifications to display in the notifications pop-up box.

6. Notifications logo :
	Upload the desired notifications logo image. 

7. Custom messages :
	Admin can send custom messages for every group of customers. In the message, he can send an image, URL link or enable/disable that particular message for customers. 


Features available for customers:

After placing the order, customers can see the notifications about the order in the notifications pop-up box.

1. Order Notifications :
	For every changed order status (processing, completed, closed and generated invoice, shipment, credit memo), the customers get the notifications in the pop-up box.

2. Wishlist notifications :
	When a customer adds the product in their wishlist, he gets the notifications in the pop-up box.

3. Admin notifications :
	If the admin sends custom notifications like informing about the new product collections or providing the offers for a particular group of customers, then that group of customers can get notifications in the pop up box.

4. Latest notifications
	Customers will get the latest specific number of notifications in the pop-up box which are selected by admin at configurations setting of modules.

5. Notifications history
	Customers can see all notifications history after clicking on the ‘’View all” button on the pop-up box. 

6. No-notifications
	If there are no notifications for the customer then “There are no notifications yet” message pops up.

7. Custom notifications
	Customers can see the notifications related to orders, wishlist and custom messages which are enabled by admin at configuration settings of the module.



# Installation Instruction

* Copy the content of the repo to the Magento 2 app/code/Ksolves/Notifications 
* Run command:
<b>php bin/magento setup:upgrade</b>
* Run Command:
<b>php bin/magento setup:di:compile</b>
* Run Command:
<b>php bin/magento setup:static-content:deploy</b>
* Now Flush Cache: <b>php bin/magento cache:flush</b>



