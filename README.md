# woo-completed-orders
Basic Wordpress plugin to pass WooCommerce completed orders to a 3rd party API

Simply install and activate this plugin. Then in your Wordpress admin dashboard,
Select "Woo Completed Orders Hook API". All you have to do is enter in the full
URL to your 3rd party API. It needs to accept POST data.

This will send 3 items. `billingEmail`, `items`, and `order_details`. The order
details are the entire order data from WooCommerce, just in case you need more
than the billing email or the items they purchased.
