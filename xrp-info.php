<?php
/**
 * Plugin Name: XRP Info
 * Plugin URI: http://github.com/jetwes/wp-xrp-info
 * Description: Generates a shortcodes to display balance of a <a href="https://ripple.com/xrp">XRP</a> account or the transactions of an account.
 * Version: 1.0.0
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
define('XRPINFO_VERSION', '1.1.0');
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
    $ledger = WP_XRP_Info::get_instance()->ledger;

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
    $ledger = WP_XRP_Info::get_instance()->ledger;
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
    $output = '<div><table><thead><tr><th>Sender</th><th>Amount</th><th>Hash</th><th>Date</th></tr></thead><tbody>';
    foreach($transactions as $transaction) {
        $output .= "<tr><td>".$transaction->tx->Account."</td><td>".$transaction->tx->Amount/1000000 ." XRP</td><td><a href='https://bithomp.com/explorer/".$transaction->tx->hash."' target='_blank'>".__('Show transaction')."</a></td><td>".date('Y-m-d H:i:s',$transaction->tx->date+946684800)."</td></tr>";
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
    $ledger = WP_XRP_Info::get_instance()->ledger;
    //if the account does not exist show an error message
    if ($ledger->account_info($atts['account'])->status === 'error') {
        return 'XRP Account does not exist';
    }
    return '<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$atts['account'].'">';
}

add_shortcode('xrp_account', 'get_xrp_account');

add_shortcode('xrp_transactions','get_xrp_transactions');

add_shortcode('xrp_qrcode','get_xrp_qrcode');

add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
    add_options_page('WP XRP Info Settings', 'WP XRP Info Menu', 'manage_options', 'plugin', 'plugin_options_page');
}
function plugin_options_page() {
    ?>
    <div>
        <h2>WP XRP Info Plugin Settings</h2>
        Options for the XRP Info plugin.
        <form action="options.php" method="post">
            <?php settings_fields('plugin_options'); ?>
            <?php do_settings_sections('plugin'); ?>

            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form></div>

    <?php
}

// add the admin settings and such
add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init()
{
    register_setting('plugin_options', 'plugin_options', 'plugin_options_validate');
    add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'plugin');
    add_settings_field('plugin_use_proxy', 'Use Proxy', 'plugin_setting_string', 'plugin', 'plugin_main');
} ?>

<?php function plugin_section_text() {
    echo '<p></p>';
} ?>

<?php function plugin_setting_string() {
    $options = get_option('plugin_options');
    echo "<label for='use_proxy'>yes / no (default)</label><input id='use_proxy' name='plugin_options[use_proxy]' size='40' type='text' value='{$options['use_proxy']}' />";
} ?>

