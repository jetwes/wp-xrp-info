# WP XRP Info

This plugin provides some shortcodes for simple displaying XRP accounts or transactions in Wordpress

## Requirements

* [PHP](https://php.net) 5.6 or greater (PHP 7.2 or higher is recommended)
* [WordPress](https://wordpress.org/) 5.1 or greater

## Installing

1. Upload the plugin to the `/wp-content/plugins/wp-xrp-info` directory folder, or install the plugin through the WordPress plugin screen directly.
1. Activate the plugin through the `Plugins` screen in Wordpress.

## Usage
This plugins activates 3 shortcodes
1. [xrp_account account=PLACE_THE_XRP_ACCOUNT_NUMBER proxy=no]
1. [xrp_transactions account=PLACE_THE_XRP_ACCOUNT_NUMBER limit=HOW_MANY_TRANSACTIONS proxy=no]
1. [xrp_qrcode account=PLACE_THE_XRP_ACCOUNT_NUMBER proxy=no]

Just use the shortcodes on any place in your wordpress content. The transactions are shown as a table with the class "wp_xrp_info" so you can
easily adjust the output with css. Received transaction of this account are in green - sent transactions in red.

## FAQ ##

### Which XRP server (rippled) is used by default?

The node **s2.ripple.com** is being used to talk to the XRP network. This can easily be changed under *Advanced* and you can use any public XRP server.

### What does the bypass firewall feature do?

By default, we speak [JSON-RPC](https://en.wikipedia.org/wiki/JSON#JSON-RPC) on port 51234 with the XRP server. 
Some webservers are behind a firewall that doesn't allow outgoing traffic on non-standard ports. 
By enabling this feature, we talk to [cors-anywhere.herokuapp.com](https://cors-anywhere.herokuapp.com/), using TLS on port 443, which then acts as a proxy and relays the traffic to the XRP server.
To enable it just add proxy=yes in your shortcode.

## Changelog

### 1.0.1
* fixed error message on activation
* added proxy-option in shortcode

### 1.0.0
* Initial release!

## Acknowledgments

* A huge thank you to both [Ripple](https://ripple.com/) and [XRPL Labs](https://xrpl-labs.com/) for being awesome.
* A huge thank you to [Jesper Wallin](https://twitter.com/empatogen) for his WooCommerceXRP - which helped
* Thanks to **everyone** who tests new unreleased code to help us nail bugs.

## Donate

If you like this plugin and wish to donate, feel free to send some [XRP](https://ripple.com/xrp) to **r9JU6RToZGX78XFF9tdkqBogwBv7yEWcR**.
