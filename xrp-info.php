<?php
/**
 * Plugin Name: XRP Info
 * Plugin URI: http://github.com/jetwes/wp-xrp-info
 * Description: Generates a shortcodes to display balance of a <a href="https://ripple.com/xrp">XRP</a> account or the transactions of an account.
 * Version: 1.0.2
 * Author: Jens Twesmann
 * Author URI: https://github.com/jetwes
 * Developer: Jens Twesmann
 * Developer URI: https://github.com/jetwes
 * Text Domain: wp-xrp-info
 * Domain Path: /languages/
 *
 *
 * Copyright: © 2019 Jens Twesmann.
 * License: ISC license
 */

/* If this file is called directly, abort. */
if (!defined('WPINC')) {
    die;
}

/* define constants */
define('XRPINFO_VERSION', '1.0.1');
define('XRPINFO_TEXTDOMAIN', 'wp-xrp-info');
define('XRPINFO_NAME', 'XRP Info');
define('XRPINFO_PLUGIN_ROOT', plugin_dir_path(__FILE__));
define('XRPINFO_PLUGIN_ABSOLUTE', __FILE__);

if (!function_exists('wp_xrp_info')) {
    /**
     * Unique access to instance of WP_XRP_Info class
     *
     * @return WP_XRP_Info
     */
    function wp_xrp_info()
    {
        // Load required classes and functions
        include_once dirname(__FILE__) . '/includes/class-wpxrpinfo-base.php';
        return WP_XRP_Info::get_instance();
    }
}
//load the instance class once
wp_xrp_info();
load_plugin_textdomain(
    'wp-xrp-info',
    false,
    dirname(plugin_basename(__FILE__)) . '/languages/'
);

/**
 * Returns the balance of an XRP account or an error message
 * @param array $atts
 * @return string
 */
function get_xrp_account($atts = [])
{
    //do nothing if there is not account
    if (!isset($atts['account'])) return '';

    //get the Ledger Class
    //check if to use the proxy or not
    if (!isset($atts['proxy']) || $atts['proxy'] != 'yes')
        $ledger = WP_XRP_Info::get_instance()->ledger;
    else $ledger = WP_XRP_Info::get_instance()->proxyLedger;

    //if the account does not exist show an error message
    if ($ledger->account_info($atts['account'])->status === 'error') {
        return __('XRP Account does not exist');
    }

    $balance = $ledger->account_info($atts['account'])->account_data->Balance/1000000;

    return $balance.' XRP';
}

/**
 * Returns the X last transactions as a html table - newest first
 * @param array $atts
 * @return string
 */
function get_xrp_transactions($atts = [])
{
    //do nothing if there is not account
    if (!isset($atts['account'])) return '';

    //get the ledger class
    //check if to use the proxy or not
    if (!isset($atts['proxy']) || $atts['proxy'] != 'yes')
        $ledger = WP_XRP_Info::get_instance()->ledger;
    else $ledger = WP_XRP_Info::get_instance()->proxyLedger;

    //if the account does not exist show an error message
    if ($ledger->account_info($atts['account'])->status === 'error') {
        return 'XRP Account does not exist';
    }
    //if no limit is passed limit it to 5 as default
    if (isset($atts['limit']))
        $limit = $atts['limit'];
    else $limit = 5;
    //get the transactions
    $transactions = $ledger->account_tx($atts['account'],$limit);
    //generate output
    $output = '<div><table class="wp_xrp_info"><thead><tr><th>'.__('Account').'</th><th>'.__('Amount').'</th><th>Hash</th><th>'.__('Date').'</th></tr></thead><tbody>';
    foreach($transactions as $transaction) {
        //show destination tag if there is one
        if ($transaction->tx->DestinationTag)
            $dtag = " (".$transaction->tx->DestinationTag.")";
        else $dtag = '';
        //show received in green, send in red
        if ($transaction->tx->Account != $atts['account'])
            $color = 'green';
        else $color = 'red';
        $output .= "<tr><td style='color: ".$color."'>".$transaction->tx->Account.$dtag."</td><td>".$transaction->tx->Amount/1000000 ." XRP</td><td><a href='https://bithomp.com/explorer/".$transaction->tx->hash."' target='_blank'>".__('Show transaction')."</a></td><td>".date('Y-m-d H:i:s',$transaction->tx->date+946684800)."</td></tr>";
    }
    $output .= '</tbody></table></div>';
    return $output;
}

/**
 * Returns an QR-Code
 * @param array $atts
 * @return string
 */
function get_xrp_qrcode($atts = [])
{
    //do nothing if there is not account
    if (!isset($atts['account'])) return '';

    //get the ledger class
    //check if to use the proxy or not
    if (!isset($atts['proxy']) || $atts['proxy'] != 'yes')
        $ledger = WP_XRP_Info::get_instance()->ledger;
    else $ledger = WP_XRP_Info::get_instance()->proxyLedger;

    //if the account does not exist show an error message
    if ($ledger->account_info($atts['account'])->status === 'error') {
        return 'XRP Account does not exist';
    }
    return '<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$atts['account'].'">';
}

add_shortcode('xrp_account', 'get_xrp_account');

add_shortcode('xrp_transactions','get_xrp_transactions');

add_shortcode('xrp_qrcode','get_xrp_qrcode');
