<?php
/*
  Plugin Name: CyberSEO Pro
  Loader: 02032024
  Author: CyberSEO.net
  Author URI: https://www.cyberseo.net/
  Plugin URI: https://www.cyberseo.net/cyberseo-plugin/
  Description: A professional autoblogging and content curation plugin for WordPress.
 */

if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
    status_header(404);
    nocache_headers();
    include(get_404_template());
    exit();
}

define('CXXX_REG_NAME', 'cxxx_reg_name');
define('CXXX_REG_EMAIL', 'cxxx_reg_email');
define('CXXX_XCD', 'cxxx_xcd');
define('CXXX_CORE_VERSION', 'cxxx_core_version');
define('CSEO_CAP_GZ', true);

function cseo_file_get_contents_np($url, $as_array = false, $args = ['sslverify' => false]) {
    if (stream_is_local($url)) {
        return @file_get_contents($url);
    } else {
        $response = @file_get_contents($url);  
        if ($response !== false) {
            return $response;
        } else {
            $response = wp_remote_get($url, $args);
            if (is_array($response) && !is_wp_error($response)) {
                if ($as_array) {
                    return explode("\n", wp_remote_retrieve_body($response));
                }
                return wp_remote_retrieve_body($response);
            }
        }
    }
    return false;
}

//  IMPORTANT!
//
//  Please don't try to modify this code in attempt to cheat the statistics system.
//  The code is not encrypted, but if you decide to hack it, keep in mind that 
//  the side-server license protecting system always sees the ORIGINAL site URL, 
//  its IP and the host server name. So if you do it, your license will be revoked. 
//  Please read https://www.cyberseo.net/tos/ and use the plugin fairly.
//

$cseo_message = '';
$cxxx_xcd = cseo_get_xcd();
if (!empty(get_option(CXXX_REG_NAME)) && !empty(get_option(CXXX_REG_EMAIL)) && !function_exists('cseo_main_menu') && strpos($cxxx_xcd, 'CORE BEGIN') !== false && strpos($cxxx_xcd, 'CORE END') !== false) {
    try {
        eval($cxxx_xcd);
    } catch (Throwable $e) {
        if (is_admin()) {
            cseo_update_xcd();
        }
    }
}
unset($cxxx_xcd);

function cseo_get_xcd() {
    $xcd = get_option(CXXX_XCD);
    if (!empty($xcd) && strpos($xcd, 'CORE BEGIN') === false) {    
        return @gzuncompress(base64_decode($xcd));
    }
    return $xcd;
}

function cseo_update_xcd() {
    global $cseo_message;

    $name = stripslashes(get_option(CXXX_REG_NAME));
    $email = get_option(CXXX_REG_EMAIL);
    $ver = cseo_file_get_contents_np('https://www.cyberseo.net/versioncontrol/?item=cyberseo&name=' . urlencode($name) . '&email=' . urlencode($email) . '&site=' . urlencode(site_url()) . '&action=getver');
    $corever = cseo_file_get_contents_np('https://www.cyberseo.net/versioncontrol/?item=cyberseo&name=' . urlencode($name) . '&email=' . urlencode($email) . '&site=' . urlencode(site_url()) . '&action=getcorever');
    $xcd = base64_decode(cseo_file_get_contents_np('https://www.cyberseo.net/versioncontrol/?item=cyberseo&name=' . urlencode($name) . '&email=' . urlencode($email) . '&site=' . urlencode(site_url()) . '&action=getxcd'));

    if (strpos($xcd, "\x78\x01") === 0 || strpos($xcd, "\x78\x9c") === 0 || strpos($xcd, "\x78\xda") === 0) {
        $xcd = @gzuncompress($xcd);
    }

    if (strpos($xcd, 'CORE BEGIN') !== false && strpos($xcd, 'CORE END') !== false) {
        update_option(CXXX_XCD, base64_encode(gzcompress($xcd)));
        if (!empty(cseo_get_xcd())) {
            if (floatval($corever)) {
                update_option(CXXX_CORE_VERSION, $corever);
            }
        }
    }

    if (strtoupper($ver) === 'WRONG CREDENTIALS') {
        $cseo_message .= '<div id="message" class="error"><p>Your registration info is invalid. Please enter exact the same name and email that you were using to purchase the plugin.</p></div>';
        delete_option(CXXX_REG_NAME);
        delete_option(CXXX_REG_EMAIL);
        delete_option(CXXX_XCD);
        return;
    } elseif (stripos($ver, 'SITE LIMIT EXCEEDED') !== false) {
        $cseo_message .= '<div id="message" class="error"><p>Your site limit has exceeded. Please consider <a href="https://www.cyberseo.net/upgrade/" target="_blank">upgrading</a> your CyberSEO Pro license for more sites.</p></div>';
        delete_option(CXXX_REG_NAME);
        delete_option(CXXX_REG_EMAIL);
        delete_option(CXXX_XCD);
        return;
    } elseif (strtoupper($ver) === 'INVALID') {
        $cseo_message .= '<div id="message" class="error"><p>This license has been revoked. Please refer to <a target="_blank" href="https://www.cyberseo.net/tos/">TOS</a>.</p></div>';
        delete_option(CXXX_REG_NAME);
        delete_option(CXXX_REG_EMAIL);
        return;
    } elseif (strtoupper($ver) === 'SUSPENDED') {
        $cseo_message = '<div id="message" class="error"><p>Your license has been suspended. Please refer to <a target="_blank" href="https://www.cyberseo.net/tos/">TOS</a>.</p></div>';
        return;
    } elseif (strpos(cseo_get_xcd(), 'CORE BEGIN') !== false && strpos(cseo_get_xcd(), 'CORE END') !== false && $ver !== false && $corever !== false) {
        if (isset($_POST['cseo_register'])) {
            $cseo_message .= '<div id="message" class="notice updated"><h3>Congratulations!</h3><p>Your copy of CyberSEO Pro has been successfully registered and it\'s active now. Thank you for choosing our plugin!</p></div>';
            $cseo_message .= '<p style="display: block; text-align: center; margin: 0; font-size: 250pt;">&#x1F91D;</p>';
            $cseo_message .= '<h1 style="text-align: center;"><a href="admin.php?page=cyberseo" style="text-decoration: none;">Continue...</a></h1>';
        } elseif (isset($_POST['cseo_update'])) {
            if (get_option(CXXX_CORE_VERSION) === $ver) {
                $cseo_message .= '<div id="message" class="notice updated"><p>Your version of CyberSEO Pro is up to date.</p></div>';
            } else {
                $cseo_message .= '<div id="message" class="notice updated"><p>The plugin has been updated to version ' . $corever . '.</p></div>';
            }
            if ($corever < $ver) {
                $cseo_message .= '<div id="message" class="notice notice-warning"><p>The latest released version is ' . $ver . '.</p></div>';
                $cseo_message .= '<div id="message" class="notice notice-warning"><p>Please make sure to <a href="https://www.cyberseo.net/upgrade/" target="_blank">prolong</a> your CyberSEO Pro lincense for one more year to keep the plugin updated.</p></div>';
            }
        }
        return;
    }

    if (!empty(get_option(CXXX_REG_NAME)) && !empty(get_option(CXXX_REG_EMAIL))) {
        $cseo_message = '<div id="message" class="error"><p>Something went wrong. Perhaps your host can\'t connect to cyberseo.net. Please try again later.</p></div>';
    }
}

if (!function_exists('cseo_main_menu')) {

    function cseo_main_menu() {
        $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dy'
                . 'YXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIg'
                . 'eD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyMHB4IiBoZWlnaHQ9IjIwcHgiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjAgMjAiIHhtbDpzcGFjZT0icHJlc2VydmUiPiAgPGltYWdlIGlkPSJpbWFnZTAiIHdp'
                . 'ZHRoPSIyMCIgaGVpZ2h0PSIyMCIgeD0iMCIgeT0iMCIKICAgIGhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQlFBQUFBVUNBTUFBQUM2ViswL0FBQUFCR2RCVFVFQUFMR1BDL3hoQlFBQUFDQmpTRkpO'
                . 'CkFBQjZKZ0FBZ0lRQUFQb0FBQUNBNkFBQWRUQUFBT3BnQUFBNm1BQUFGM0NjdWxFOEFBQUIrMUJNVkVYLy8vLzE5UFB5OHZERHY3YSsKdWJYeDhPL3o4L0w3K3ZxN3Nxdkl3cm4vL3Y2dnFabll5N0w0OU8zczZ1YWNsSURaMWRLcG9ZN0h3Ny9Jdzc5'
                . 'N2RXMmNsSC9OdnAzZgp6ck9xbzVIRXdMVGkyY2Z0NDlQTnliNi91YkQyOVBQT3lzYnU3ZTN3OE8zYTFNcjYrUGZCdkxqVHpzank4ZkRmM05uaTROdnk4T3paCjF0RC8vLy8vLy8vNit2bng4TzcvL3Y3cDUrUDE4L0w5L1B2dzcrejUrZmYvLy8vLy8v'
                . 'Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8KLy8vLy92Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLwovLy8wOC9QKy9mMy8vdjcxOVBQLy8vLy8vLy9Qd0tMLy8vLy8v'
                . 'Ly8zOS9hNnQ3VEx5TVgwOC9QMDlQVFF6Y3ZlMmRhNnRyUDI5dlgvCi8vL2V6N1N3cXBucDV0L3o4L0oyY0dpdnFxWHY3Kzd4OFBDdnFxWHk4ZkgvLy8vLy8vL3Q2dVc5dDZqMDlQTC8vLy8vLy8vLy8vLzcKKy92UXpzdnA1K2ZGd3IvNStmbi8vLy82'
                . 'K3ZyQ3dMem01T1BMeWNmNit2ci8vLy8yOXZYbjV1TC8vLy8vLy8vLy8vLysvZjMvLy8vLwovLy85L1B6Ly8vL3M2K2VtbjQzZjJzNy8vLy8vLy8vLy8vLy8vLy8vLy8vbTRkYXpyWjMvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vCi8vLy8vLy8vLy8v'
                . 'Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLzRjWTlkQUFBQXFIUlNUbE1BQUFBQUFBQUEKQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFCZTlKYUZZRldFUGJxK0RRVmM2RnpRd1Z0aGdMVzB1R0JvT1Ridkt4OFBGWW1W'
                . 'awpaVjhyS2RiUjJOTE16MmhtYVd4clc5Q2l4bVZUYlRZT1o5ZkxTa3ZJWlNzREpXVTdCekNQdlFFRHRWd0ZXMjQzUlIrV3FsblYwRjBYClg4M09aekFMS21ZelRZV3RXdFREMDJwZlVFY1pjTTFFSXpBNU54SklKREl0eXh4WVl6RUhVbmgxZERvOExB'
                . 'VWluRkZ4QUFBQUFXSkwKUjBRQWlBVWRTQUFBQUFsd1NGbHpBQUFMRXdBQUN4TUJBSnFjR0FBQUFBZDBTVTFGQitZRkhRVXFOVmxKbU8wQUFBRW1TVVJCVkJqVApZMkFnR2pBQ0FST3pySnk4Z3FLU3Nnb0xLeHNRUUdUWU9WVFYxRFUwdFRpNUVLcTFk'
                . 'WFQxOUEwTWpZeE5UTTFnWXVZV2xsYldsamEyCmRyYjJEbzVPRURGbkt4ZFhOM2NQVHk5dkgxOGZQMjlUb0JBM2o3OUxnRlZna0tlSHQxZHdpTDFYYUJndkh3Ti9lRVJrVkxSQVRLeG4KUkZ4OFFtSlNhSEtLSUVOcW1tMWtlb1pRWnBhclQzYU9zRWh1'
                . 'WG41QklVTlJjVWxwV1hsRlpWVjFSRTF0WFgxRGFLTjVFME56UzJ0YgpkVUI3UUllN1Q2ZFhWMFJFYUdOM0QwTnZuM2RidjBkMWdNK0VpWk84dlNLOGZTZFBtY29nS21icjB1L2g0Ums2YmZvTWh3aHY3OURKCjRoSU1rbEl6WjdWVlYzdTJnYlJIUkhS'
                . 'NnpaYVdBYmwranMwc1MwdkxXZDRPRGc3ZWMrZEJ2VGwvd2NKRml4Y3ZYZ0lFUzVjdEp6NkEKY1FFQWlIcFU2aEQ2eHNjQUFBQWxkRVZZZEdSaGRHVTZZM0psWVhSbEFESXdNakl0TURVdE1qbFVNREk2TkRJNk5UTXJNRE02TURDcQp6QkV5QUFBQUpY'
                . 'UkZXSFJrWVhSbE9tMXZaR2xtZVFBeU1ESXlMVEExTFRJNVZEQXlPalF5T2pVekt6QXpPakF3MjVHcGpnQUFBQUJKClJVNUVya0pnZ2c9PSIgLz4KPC9zdmc+Cg==';

        $ver = cseo_file_get_contents_np('https://www.cyberseo.net/versioncontrol/?item=cyberseo&name=' . urlencode(get_option(CXXX_REG_NAME)) . '&email=' . urlencode(get_option(CXXX_REG_EMAIL)) . '&site=' . urlencode(site_url()) . '&action=getver');
        if (function_exists('cseo_xml_syndicator_menu') && floatval($ver) !== 0) {
            // for a back compatibility with CyberSEO Pro versions prior version 10
            add_menu_page('Feed Syndicator', 'CyberSEO Pro', 'manage_options', 'cyberseo', 'cseo_xml_syndicator_menu', $icon);
            add_submenu_page('cyberseo', 'General Settings', 'General Settings', 'manage_options', 'cyberseo_general_settings', 'cseo_options_menu');
            add_submenu_page('cyberseo', 'Post Modification Tools', 'Modification Tools', 'manage_options', 'cyberseo_tools', 'cseo_tools_menu');
            add_submenu_page('cyberseo', 'Synonymizer/Rewriter', 'Synonymizer/Rewriter', 'manage_options', 'cyberseo_synonymizer', 'cseo_synonymizer_menu');
            add_submenu_page('cyberseo', 'Duplicate Post Finder', 'Duplicate Post Finder', 'manage_options', 'cyberseo_duplicate_post_finder', 'cseo_duplicate_post_finder_menu');
            add_submenu_page('cyberseo', 'Auto-comments', 'Auto-comments', 'manage_options', 'cyberseo_auto_comments', 'cseo_auto_comments_menu');
        } else {
            add_menu_page('Registration', 'CyberSEO', 'manage_options', 'cyberseo', 'cseo_registration', $icon);
        }
    }

}

if (!function_exists('cseo_registration')) {

    function cseo_registration() {
        global $cseo_message;
        if (version_compare(phpversion(), '7', '<')) {
            echo '<div id="message" class="error"><h3>Activation failed!</h3><p>PHP ' . phpversion() . ' is not supported by CyberSEO Pro. PHP 7 or greater is required.</p></div>';
            echo '<div style="margin: auto; display: block; text-align: center; margin: 24pt 0pt 24pt 0pt; font-size: 250pt;">&#x26D4;</div>';
            echo '<a href="index.php" style="display: block; text-align:center; text-decoration: none;"><h1 style="text-align:center;">Return to WordPress Dashboard</h1></a>';
            return;
        }
        echo '<h1>CyberSEO Pro Registration</h1>';
        echo $cseo_message;
        if (!get_option(CXXX_REG_NAME) || !get_option(CXXX_REG_EMAIL) || !isset($_POST['cseo_register'])) {
            ?>            
            <div style="margin: auto; display: block; width: 320pt; margin-top: 3em; background: #e5e5e5; box-shadow: 1px 1px #aaa; color: black; border-radius: 1em;">
                <p style="display: block; text-align: center; margin: 0; font-size: 250pt;">&#x1F4CB;</p>
                <form method="post" name="registration">
                    <table class="form-table">
                        <tr>
                            <td colspan="2">
                                Activation name
                                <br>
                                <input style="width:100%" type="text" name="reg_name" value="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Email
                                <br>
                                <input style="width:100%" type="text" name="reg_email" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &#x1F441; <a href="https://www.cyberseo.net/installation-and-activation/#activation" target="_blank">Instruction</a>
                            </td>
                            <td style="text-align:right;">
                                <input type="submit" name="cseo_register" class="button-primary" value="Click to register" />
                            </td>
                        </tr>                            
                    </table>
                    <?php wp_nonce_field('cseo_update'); ?>
                </form>
            </div>
            <?php
        }
    }

}

function cseo_init() {
    if (isset($_POST['cseo_register']) && check_admin_referer('cseo_update')) {
        update_option(CXXX_REG_NAME, trim($_POST['reg_name']));
        update_option(CXXX_REG_EMAIL, trim($_POST['reg_email']));
        cseo_update_xcd();
    } else {
        if (isset($_POST['cseo_update']) && check_admin_referer('cseo_update')) {
            cseo_update_xcd();
            cseo_download_default_presets();
        }
    }
}

if (is_admin()) {
    add_action('admin_menu', 'cseo_main_menu');
    add_action('init', 'cseo_init');
}
?>