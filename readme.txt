=== TG Send SMS Notifications for Wordpress and WooCommerce Twilio ===
Contributors: burfbari
Donate link: 
Tags: notifications, wordpress, woocommerce, sms, twilio
Requires at least: 4.7
Tested up to: 5.5.1
Stable tag: 2.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Send SMS notifications on new Post publish, New Product addition, price changes and Order status change to Processing and Completed. 

== Description ==

TG SMS WP Notify will generate SMS for the Manager's mobile number and/or Customer's mobile number. 

If enabled, the SMS are generated for the Manager's number on the following actions:-

1. New post publish
2. New WooCommerce product addition
3. WooCommerce product Regular price change
4. WooCommerce product Sale price change
5. Whenever a WooCommerce product quality reaches a predefined threshold or whenever it goes out of stock.

If enabled, the SMS are generated for the Customer's number on the following actions:-

1. Order status changed to Processing
2. Order status changed to Completed

Currently, TG SMS Notify provides for sending with Twilio.

Please create an account for yourself on [Twilio](www.twilio.com/referral/KGwFUw). They can send SMS all over the world and give out a few hundred SMS for free. I believe that would be more than you'd require for a small store. 

We plan to release more features including adding more providers such as Twilio. If you have any particular provider that you'd prefer be integrated, drop us an email at hello@karmadaily.com

*Settings*

Navigate to Settings > TG SMS Notify Settings

Fill the following Twilio details (these are essential for delivery)
* Twilio Account SId
* Twilio Auth Token
* Twilio From number
* Enter the Recipient Manager Number.
* The customer modules would want a valid incoming SMS-enabled numbers (Check your Twilio account dashboard for delivery enablement and pricing if you deliver orders worldwide and want the SMS to too).

All other fields are optional. The SMS would be generated for those enabled (checked). These are:-

1. Send SMS for New Post
2. Send SMS for New Product
3. Send SMS for Product Price Change
4. Send SMS for Quantity Alert

5. Notify Customer on Ouder Processing
6. Notify Customer on Ouder Completed

Except for the message related to Quantity Alert, all other messages could be customized here. 

== Frequently Asked Questions ==

= How much does each SMS cost? =

That depends on the destination country and Twilio. Destination country is the country number to which the SMS notifications will be delivered. If you have consumed your free-tier on Twilio, please check the rates there.

= Can mail notifications be enabled instead? =

When we started building the plugin, we realised there were other plugins doing the job of sending emails. Rather WordPress or WooCommerce core were sending emails pretty fine. 

= We use *XYZ* SMS provider as they provide better rates. Can it be integrated? =

We won't say no if they have a free plan on which we can do the integration testing. 

= We want the notifications to be generated for an event not covered by the plugin, yet. =

Please drop us an email at info@karmadaily.com and we'd get in touch if that may be possible.

== Screenshots ==

1. Set your Twilio core settings here. 
2. Set your Manager settings here. 
3. Set your Customer settings here. 

== Changelog ==

= 2.0 = 
Introduced 2 new features related to Customer notifications on Order Processing and Order Completed
	- Changed the Settings page. Now the Twilio, Manager and Customer settings page have their respective tabs
	- The admin can enable notifications to be sent on Order status changed to Processing
	- The admin can enable notifications to be sent on Order status changed to Completed

Resolved bug related to a function not found error
Resolved bug related to Sale Price and Regular Price in Price Change notificaton message
(2020-09-06)

= 1.0 =
* Initial release
