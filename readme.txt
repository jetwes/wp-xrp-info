=== WooCommerce XRP ===
Contributors: jetwes
Donate link: r9JU6RToZGX78XFF9tdkqBogwBv7yEWcR
Tags: xrp, wordpress
Requires at least: 5.1
Tested up to: 5.1.1
Requires PHP: 7.0
Stable tag: trunk
License: ISC License

This plugin provides some shortcodes for simple displaying XRP accounts or transactions in Wordpress

== Description ==

This plugin provides some shortcodes for simple displaying XRP accounts or transactions in Wordpress

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/wp-xrp-info` directory folder, or install the plugin through the WordPress plugin screen directly.
1. Activate the plugin through the `Plugins` screen in Wordpress.

== Usage ==
This plugins activates 3 shortcodes
1. [xrp_account account=PLACE_THE_XRP_ACCOUNT_NUMBER]
1. [xrp_transaction account=PLACE_THE_XRP_ACCOUNT_NUMBER limit=HOW_MANY_TRANSACTIONS]
1. [xrp_qrcode account=PLACE_THE_XRP_ACCOUNT_NUMBER]

Just use the shortcodes on any place in your wordpress content.

== FAQ ==

= Which XRP server (rippled) is used by default? =

The node **s2.ripple.com** is being used to talk to the XRP network. This can easily be changed under *Advanced* and you can use any public XRP server.

= What does the bypass firewall feature do? =

By default, we speak [JSON-RPC](https://en.wikipedia.org/wiki/JSON#JSON-RPC) on port 51234 with the XRP server. Some webservers are behind a firewall that doesn't allow outgoing traffic on non-standard ports. By enabling this feature, we talk to [cors-anywhere.herokuapp.com](https://cors-anywhere.herokuapp.com/), using TLS on port 443, which then acts as a proxy and relays the traffic to the XRP server.

== Changelog ==
= 1.0.0 =
* Initial release!

== Acknowledgments ==

* A huge thank you to both [Ripple](https://ripple.com/) and [XRPL Labs](https://xrpl-labs.com/) for being awesome.
* A huge thank you to [Jesper Wallin](https://twitter.com/empatogen) for his WooCommerceXRP - which helped
* Thanks to **everyone** who tests new unreleased code to help us nail bugs.
