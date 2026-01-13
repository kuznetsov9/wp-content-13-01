<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit;
}

if ( ! class_exists( 'TPLM' ) ) {

    class TPLM {

        protected static $instance = null;

        private $item_id = 16;

        private $version = '';

        private $token = '';

        private $store_url = 'https://devster.ru';

        private $author = 'Devster';

        private $slug = '';

        private $update_cache_key = '';

        private $status_cache_key = 'tplm_status_cache';

        private $site_url = '';

        public function __construct() {
            $this->slug = get_template();
            $this->token = get_option( $this->decode_key( 'dGVtcGxhdGUtYWN0aXZhdGlvbg==' ), '' );
            $this->version = wp_get_theme()->get( 'Version' ) ?: '1.6.0';
            $this->site_url = home_url();
            $this->update_cache_key = $this->generate_cache_key( $this->token, $this->version, $this->item_id );

            add_action( 'admin_init', array( $this, 'init_system' ) );
            add_action( 'admin_menu', array( $this, 'add_protection_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_ajax_tplm_activate', array( $this, 'ajax_activate' ) );
            add_action( 'wp_ajax_tplm_reset', array( $this, 'ajax_reset' ) );
            add_action( 'wp_ajax_tplm_check', array( $this, 'ajax_check' ) );
            add_action( 'wp_ajax_tplm_clear_cache', array( $this, 'ajax_clear_cache' ) );

            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_theme_update' ), 10, 1 );

            add_action( 'admin_init', array( $this, 'on_theme_activation_check' ) );
            add_action( 'after_switch_theme', array( $this, 'set_notice_transient' ) );

            add_action( 'admin_notices', array( $this, 'invalid_notice' ) );

            add_action( 'template_redirect', array( $this, 'check_system_for_frontend' ) );
        }

        private function decode_key( $encoded ) {
            return base64_decode( $encoded );
        }

        private function generate_cache_key( $token, $version, $item_id ) {
            return 'tplm_update_info_' . md5( $token . $version . $item_id );
        }

        public function set_notice_transient( $theme ) {
            if ( $this->slug === $theme ) {
                set_transient( $this->decode_key( 'dHBsbV9zaG93X25vdGljZQ==' ), true, DAY_IN_SECONDS );
            }
        }

        public function init_system() {
            if ( ! $this->token ) {
                return;
            }
            $this->validate_system( true );
        }

        private function clear_transients() {
            delete_site_transient( 'update_themes' );
            delete_transient( $this->update_cache_key );
            delete_transient( $this->status_cache_key );
            delete_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ) );
        }

        public function add_protection_menu() {
            add_theme_page(
                __( 'Esenin License', 'esenin' ),
                __( 'Theme License', 'esenin' ),
                'manage_options',
                'theme-protection',
                array( $this, 'protection_page' )
            );
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();
            if ( ! $screen || 'appearance_page_theme-protection' !== $screen->id ) {
                return;
            }

            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'dashicons' );

            $custom_styles = '
            .tplm-container {
                display: flex;
                gap: 20px;
                margin-top: 20px;
                position: relative;
            }
            .tplm-main {
                flex: 1;
                max-width: 100%;
            }
            .tplm-form {
                background: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .tplm-form label {
                font-weight: bold;
                margin-bottom: 5px;
                display: block;
            }
            #tplm_token {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 10px;
                box-sizing: border-box;
                position: relative;
            }
            .tplm-toggle {
                position: relative;
                display: inline-block;
                width: 100%;
            }
            .tplm-toggle button {
                position: absolute;
                right: 5px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #666;
                cursor: pointer;
                padding: 0 5px;
                line-height: 1;
                height: 20px;
                width: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }
            .tplm-form button {
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 6px 12px;
                min-height: 28px;
                border-radius: 10px;
            }
            .tplm-form .button-primary,
            .tplm-form .button-secondary {
                padding: 6px 12px;
                min-height: 28px;
                border-radius: 10px;
            }
            #tplm-status {
                font-weight: bold;
                color: #d63638;
                padding: 4px 8px;
                border-radius: 10px;
                background: rgba(214, 54, 56, 0.1);
                display: inline-block;
            }
            #tplm-status.valid {
                color: #00a32a;
                background: rgba(0, 163, 42, 0.1);
            }
            #tplm-status.inactive {
                color: #666;
                background: rgba(102, 102, 102, 0.1);
            }
            #tplm-status.invalid {
                color: #d63638;
                background: rgba(214, 54, 56, 0.1);
            }
            #status-display {
                margin: 15px 0;
                font-size: 16px;
                display: none;
            }
            .tplm-updates {
                background: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                margin-top: 20px;
            }
            .tplm-sidebar {
                width: 220px;
                background: #f8f9fa;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                border: 1px solid #e1e5e9;
                position: sticky;
                top: 20px;
                align-self: flex-start;
                height: fit-content;
                max-height: 80vh;
                overflow-y: auto;
                flex-shrink: 0;
            }
            .tplm-sidebar h3 {
                margin-top: 0;
                font-size: 16px;
                color: #23282d;
                border-bottom: 1px solid #ddd;
                padding-bottom: 10px;
            }
            .tplm-sidebar ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .tplm-sidebar li {
                margin-bottom: 10px;
            }
            .tplm-sidebar a {
                display: flex;
                align-items: center;
                text-decoration: none;
                color: #0073aa;
                padding: 10px;
                border-radius: 10px;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .tplm-sidebar a:hover {
                background: #e3f2fd;
                color: #005a87;
                transform: translateX(2px);
            }
            .tplm-sidebar .dashicons {
                margin-right: 8px;
                width: 16px;
                height: 16px;
                color: #0073aa;
            }
            .notice {
                margin: 10px 0;
            }
            .spinner {
                float: none;
            }
            .tplm-button-group {
                margin-top: 15px;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .tplm-buy-button {
                background: #dc3232;
                border-color: #dc3232;
                color: #fff;
                text-decoration: none;
                padding: 6px 12px;
                border-radius: 10px;
                display: inline-block;
                font-size: 13px;
                line-height: 2.15384615;
                min-height: 28px;
                margin: 0;
                vertical-align: top;
                cursor: pointer;
                transition: background 0.3s;
            }
            .tplm-buy-button:hover {
                background: #b32d2e;
                border-color: #b32d2e;
                color: #fff;
            }
            .tplm-buy-button.hidden {
                display: none;
            }
            .tplm-license-notice {
                display: none !important;
            }
            @media (max-width: 782px) {
                .tplm-container {
                    flex-direction: column;
                    gap: 15px;
                }
                .tplm-sidebar {
                    width: 100%;
                    position: static;
                    order: 2;
                    max-height: none;
                }
                .tplm-main {
                    order: 1;
                }
                .tplm-toggle button {
                    right: 8px;
                    font-size: 16px;
                }
                #status-display {
                    text-align: center;
                    margin: 10px 0;
                }
            }
            @media (max-width: 600px) {
                .tplm-button-group {
                    flex-direction: column;
                }
                .tplm-form button,
                .tplm-buy-button {
                    width: 100%;
                    margin-right: 0;
                    text-align: center;
                }
            }
            ';

            wp_add_inline_style( 'wp-admin', $custom_styles );

            $status_labels = array(
                'valid' => esc_js( $this->get_status_label( 'valid' ) ),
                'invalid' => esc_js( $this->get_status_label( 'invalid' ) ),
                'inactive' => esc_js( $this->get_status_label( 'inactive' ) ),
                'error' => esc_js( $this->get_status_label( 'error' ) ),
                'unknown' => esc_js( $this->get_status_label( 'unknown' ) ),
            );

            $localized_data = array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonces' => array(
                    'activate' => wp_create_nonce( 'tplm_activate' ),
                    'reset' => wp_create_nonce( 'tplm_reset' ),
                    'check' => wp_create_nonce( 'tplm_check' ),
                    'clear_cache' => wp_create_nonce( 'tplm_clear_cache' ),
                ),
                'strings' => array(
                    'please_enter_key' => esc_js( __( 'Please enter a license key.', 'esenin' ) ),
                    'activation_failed' => esc_js( __( 'Activation failed.', 'esenin' ) ),
                    'reset_failed' => esc_js( __( 'Reset failed.', 'esenin' ) ),
                    'check_failed' => esc_js( __( 'Check failed.', 'esenin' ) ),
                    'clear_failed' => esc_js( __( 'Clear failed.', 'esenin' ) ),
                    'connection_error' => esc_js( __( 'Connection error. Try again.', 'esenin' ) ),
                    'inactive' => esc_js( __( 'inactive', 'esenin' ) ),
                    'no_key' => esc_js( __( 'No license key. Enter to activate.', 'esenin' ) ),
                    'invalid_key' => esc_js( sprintf( __( 'License invalid. Troubleshooting: Check error logs (/wp-content/debug.log) or click "Check Status". Verify domain matches license on devster.ru.', 'esenin' ) ) ),
                    'active' => esc_js( __( 'License active. Updates enabled.', 'esenin' ) ),
                    'confirm_reset' => esc_js( __( 'Reset local license? This won\'t affect server activation.', 'esenin' ) ),
                    'confirm_clear' => esc_js( __( 'Clear all update caches? This will refresh license and update checks.', 'esenin' ) ),
                    'clear_success' => esc_js( __( 'Caches cleared. Reload page or check updates again.', 'esenin' ) ),
                    'reset_success' => esc_js( __( 'Reset successful. Cache cleared.', 'esenin' ) ),
                    'activation_success' => esc_js( __( 'Activation successful. Cache cleared.', 'esenin' ) ),
                    'verification_warning' => esc_js( __( 'License key saved, but verification failed. Status: %s. Check server settings or contact support.', 'esenin' ) ),
                ),
                'status_labels' => $status_labels,
                'current_key_hidden' => $this->token ? '***hidden***' : '',
                'token_status' => $this->get_system_status(),
            );

            wp_add_inline_script( 'jquery', 'var tplmData = ' . wp_json_encode( $localized_data ) . ';' );
            wp_add_inline_script( 'jquery', $this->get_inline_js() );
        }

        private function get_status_label( $status ) {
            $labels = array(
                'valid' => __( 'Valid', 'esenin' ),
                'invalid' => __( 'Invalid', 'esenin' ),
                'inactive' => __( 'Inactive', 'esenin' ),
                'error' => __( 'Error', 'esenin' ),
                'unknown' => __( 'Unknown', 'esenin' ),
                'expired' => __( 'Expired', 'esenin' ),
                'revoked' => __( 'Revoked', 'esenin' ),
                'deactivated' => __( 'Deactivated', 'esenin' ),
            );
            return isset( $labels[ $status ] ) ? $labels[ $status ] : ucfirst( $status );
        }

        private function get_inline_js() {
            return '
            (function($) {
                $(document).ready(function() {
                    var $keyInput = $("#tplm_token");
                    var $status = $("#tplm-status");
                    var $statusDisplay = $("#status-display");
                    var $messageDiv = $("#tplm-message");
                    var $activateBtn = $("#activate-btn");
                    var $resetBtn = $("#reset-btn");
                    var $checkBtn = $("#check-btn");
                    var $clearCacheBtn = $("#clear-cache-btn");
                    var $buyBtn = $(".tplm-buy-button");
                    var $toggleBtn = $("#tplm-key-toggle");

                    $toggleBtn.on("click", function(e) {
                        e.preventDefault();
                        var type = $keyInput.attr("type") === "password" ? "text" : "password";
                        $keyInput.attr("type", type);
                        $(this).toggleClass("dashicons-visibility dashicons-hidden");
                    });

                    $keyInput.on("focus", function() {
                        if ($(this).val() === "***hidden***") {
                            $(this).val("");
                        }
                    });

                    function showMessage(msg, isError) {
                        var noticeClass = isError ? "notice-error" : "notice-success";
                        $messageDiv.html(\'<div class="notice \' + noticeClass + \' is-dismissible"><p>\' + msg + \'</p></div>\');
                        console[isError ? "error" : "log"]("TPLM: " + msg);
                    }

                    function setLoading(btn, loading) {
                        if (loading) {
                            btn.prop("disabled", true).append(\'<span class="spinner is-active"></span>\');
                        } else {
                            btn.prop("disabled", false).find(".spinner").remove();
                        }
                    }

                    function getStatusText(status) {
                        return tplmData.status_labels[status] || (status.charAt(0).toUpperCase() + status.slice(1));
                    }

                    function updateUI(hasKey, status) {
                        $keyInput.val(tplmData.current_key_hidden);
                        $status.text(getStatusText(status)).removeClass("valid inactive invalid").addClass(status);

                        if (hasKey) {
                            $statusDisplay.show();
                        } else {
                            $statusDisplay.hide();
                        }

                        if (hasKey && status === "valid") {
                            $activateBtn.hide();
                            $checkBtn.show();
                            $resetBtn.show();
                            $buyBtn.addClass("hidden");
                        } else if (hasKey && status !== "valid") {
                            $activateBtn.show();
                            $checkBtn.show();
                            $resetBtn.show();
                            $buyBtn.removeClass("hidden");
                            showMessage(tplmData.strings.invalid_key, true);
                        } else {
                            $keyInput.val("");
                            $status.text(tplmData.strings.inactive);
                            $activateBtn.show();
                            $checkBtn.hide();
                            $resetBtn.hide();
                            $buyBtn.removeClass("hidden");
                            showMessage(tplmData.strings.no_key, false);
                            $statusDisplay.hide();
                        }
                    }

                    function clearCacheInline(nonce) {
                        $.post(tplmData.ajaxurl, {
                            action: "tplm_clear_cache",
                            nonce: nonce
                        }).done(function(response) {
                            if (response.success) {
                                console.log("Cache cleared inline");
                            } else {
                                console.warn("Inline clear failed:", response.data);
                            }
                        }).fail(function() {
                            console.error("Inline clear fail");
                        });
                    }

                    $activateBtn.on("click", function(e) {
                        e.preventDefault();
                        var key = $keyInput.val().trim();
                        if (!key) {
                            showMessage(tplmData.strings.please_enter_key, true);
                            return;
                        }
                        setLoading($(this), true);
                        $.post(tplmData.ajaxurl, {
                            action: "tplm_activate",
                            activation_token: key,
                            nonce: tplmData.nonces.activate
                        }).done(function(response) {
                            setLoading($activateBtn, false);
                            if (response.success) {
                                if (response.data && response.data.indexOf("Status:") > -1) {
                                    showMessage(response.data, true);
                                    updateUI(true, "invalid");
                                } else {
                                    updateUI(true, "valid");
                                    clearCacheInline(tplmData.nonces.clear_cache);
                                    showMessage(response.data || tplmData.strings.activation_success, false);
                                }
                            } else {
                                showMessage(response.data || tplmData.strings.activation_failed, true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($activateBtn, false);
                            console.error("Activate fail:", textStatus);
                            showMessage(tplmData.strings.connection_error, true);
                        });
                    });

                    $resetBtn.on("click", function(e) {
                        e.preventDefault();
                        if (!confirm(tplmData.strings.confirm_reset)) return;
                        setLoading($(this), true);
                        $.post(tplmData.ajaxurl, {
                            action: "tplm_reset",
                            nonce: tplmData.nonces.reset
                        }).done(function(response) {
                            setLoading($resetBtn, false);
                            if (response.success) {
                                updateUI(false, "inactive");
                                clearCacheInline(tplmData.nonces.clear_cache);
                                showMessage(response.data || tplmData.strings.reset_success, false);
                            } else {
                                showMessage(response.data || tplmData.strings.reset_failed, true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($resetBtn, false);
                            console.error("Reset fail:", textStatus);
                            showMessage(tplmData.strings.connection_error, true);
                        });
                    });

                    $checkBtn.on("click", function(e) {
                        e.preventDefault();
                        setLoading($(this), true);
                        $.post(tplmData.ajaxurl, {
                            action: "tplm_check",
                            nonce: tplmData.nonces.check
                        }).done(function(response) {
                            setLoading($checkBtn, false);
                            if (response.success) {
                                updateUI(!!tplmData.current_key_hidden, response.data);
                            } else {
                                showMessage(response.data || tplmData.strings.check_failed, true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($checkBtn, false);
                            console.error("Check fail:", textStatus);
                            showMessage(tplmData.strings.connection_error, true);
                        });
                    });

                    $clearCacheBtn.on("click", function(e) {
                        e.preventDefault();
                        if (!confirm(tplmData.strings.confirm_clear)) return;
                        setLoading($(this), true);
                        $.post(tplmData.ajaxurl, {
                            action: "tplm_clear_cache",
                            nonce: tplmData.nonces.clear_cache
                        }).done(function(response) {
                            setLoading($clearCacheBtn, false);
                            if (response.success) {
                                showMessage(tplmData.strings.clear_success, false);
                                location.reload();
                            } else {
                                showMessage(response.data || tplmData.strings.clear_failed, true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($clearCacheBtn, false);
                            console.error("Clear fail:", textStatus);
                            showMessage(tplmData.strings.connection_error, true);
                        });
                    });

                    updateUI(!!tplmData.current_key_hidden, tplmData.token_status);

                    if (tplmData.current_key_hidden) {
                        setTimeout(function() {
                            $checkBtn.trigger("click");
                        }, 100);
                    }
                });
            })(jQuery);
            ';
        }

        private function check_update_available() {
            $has_update = false;
            $new_version = '';

            $update_data = get_transient( $this->update_cache_key );
            if ( false === $update_data ) {
                $update_data = $this->get_remote_update_info();
                if ( ! is_wp_error( $update_data ) && ! empty( $update_data->new_version ) ) {
                    set_transient( $this->update_cache_key, $update_data, 12 * HOUR_IN_SECONDS );
                } else {
                    $update_data = null;
                }
            }

            if ( $update_data && ! empty( $update_data->new_version ) ) {
                if ( version_compare( $this->version, $update_data->new_version, '<' ) ) {
                    $has_update = true;
                    $new_version = $update_data->new_version;
                }
            }

            return array(
                'has_update' => $has_update,
                'new_version' => $new_version,
            );
        }

        public function protection_page() {
            $status = $this->get_system_status();
            $update_info = array();

            if ( $this->token && 'valid' === $status ) {
                $update_info = $this->check_update_available();
            }
            ?>
            <div class="wrap">
                <h1><?php printf( esc_html__( 'Esenin Theme License (v%s)', 'esenin' ), esc_html( $this->version ) ); ?></h1>

                <?php if ( $this->token ) : ?>
                    <p id="status-display">
                        <strong><?php esc_html_e( 'Status:', 'esenin' ); ?> </strong>
                        <span id="tplm-status" class="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $this->get_status_label( $status ) ); ?></span>
                    </p>
                <?php endif; ?>

                <div class="tplm-container">
                    <div class="tplm-main">
                        <div class="tplm-form">
                            <label for="tplm_token"><?php esc_html_e( 'License Key:', 'esenin' ); ?></label>
                            <div class="tplm-toggle">
                                <input type="password" id="tplm_token" name="tplm_token" value="<?php echo esc_attr( $this->token ? '***hidden***' : '' ); ?>" size="40" placeholder="<?php esc_html_e( 'Enter license key', 'esenin' ); ?>" />
                                <button type="button" id="tplm-key-toggle" class="dashicons dashicons-visibility" title="<?php esc_attr_e( 'Toggle visibility', 'esenin' ); ?>"></button>
                            </div>

                            <div class="tplm-button-group">
                                <?php if ( ! $this->token || 'invalid' === $status ) : ?>
                                    <button type="button" id="activate-btn" class="button-primary"><?php esc_html_e( 'Activate License', 'esenin' ); ?></button>
                                <?php endif; ?>

                                <?php if ( $this->token ) : ?>
                                    <button type="button" id="check-btn" class="button"><?php esc_html_e( 'Check Status', 'esenin' ); ?></button>
                                    <button type="button" id="reset-btn" class="button" style="color: #a00;"><?php esc_html_e( 'Reset (Local Only)', 'esenin' ); ?></button>
                                <?php endif; ?>

                                <a href="https://devster.ru/themes/esenin" target="_blank" class="tplm-buy-button<?php echo ( 'valid' === $status ? ' hidden' : '' ); ?>"><?php esc_html_e( 'Buy', 'esenin' ); ?></a>

                                <button type="button" id="clear-cache-btn" class="button-secondary"><?php esc_html_e( 'Clear Update Cache', 'esenin' ); ?></button>
                            </div>

                            <div id="tplm-message" style="margin-top: 10px;"></div>
                        </div>

                        <?php if ( $this->token && 'valid' === $status ) : ?>
                            <div class="tplm-updates">
                                <h3><?php esc_html_e( 'Updates', 'esenin' ); ?></h3>
                                <?php if ( $update_info['has_update'] ) : ?>
                                    <p><?php 
                                        $update_link = sprintf( 
                                            '<a href="%s">%s</a>', 
                                            esc_url( admin_url( 'update-core.php' ) ), 
                                            esc_html__( 'Go to updates', 'esenin' ) 
                                        );
                                        printf( 
                                            __( 'A new update is available. Version number <strong>%s</strong>. %s', 'esenin' ), 
                                            esc_html( $update_info['new_version'] ), 
                                            $update_link 
                                        ); 
                                    ?></p>
                                <?php else : ?>
                                    <p><?php esc_html_e( 'Your license is active. Check for updates in WP Admin → Updates. New versions will appear if available.', 'esenin' ); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tplm-sidebar">
                        <h3><?php esc_html_e( 'Resources', 'esenin' ); ?></h3>
                        <ul>
                            <li><a href="https://sopoin.ru/docs" target="_blank"><span class="dashicons dashicons-editor-help"></span> <?php esc_html_e( 'Documentation', 'esenin' ); ?></a></li>
                            <li><a href="https://devster.ru/themes/esenin" target="_blank"><span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'Sales Page', 'esenin' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }

        public function invalid_notice() {
            if ( is_admin() && ( empty( $this->token ) || 'valid' !== $this->get_system_status() ) ) {
                $activate_link = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'themes.php?page=theme-protection' ) ), esc_html__( 'Activate License', 'esenin' ) );
                printf(
                    '<div class="notice notice-error is-dismissible"><p>%s %s</p></div>',
                    esc_html__( 'License is missing or invalid. Some features (like plugin recommendations) are disabled.', 'esenin' ),
                    $activate_link
                );
            }
        }

        public function check_system_for_frontend() {
            if ( is_admin() ) {
                return;
            }

            if ( $this->slug !== get_template() ) {
                return;
            }

            if ( empty( $this->token ) || 'valid' !== $this->get_system_status() ) {
                status_header( 503 );
                nocache_headers();
                ?>
                <!DOCTYPE html>
                <html <?php language_attributes(); ?>>
                <head>
                    <meta charset="<?php bloginfo( 'charset' ); ?>">
                    <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
                    <style>
                        body {
                            font-family: \'Roboto\', Arial, sans-serif;
                            text-align: center;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: #333;
                            margin: 0;
                            min-height: 100vh;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }
                        .placeholder {
                            max-width: 600px;
                            margin: 0 auto;
                            background: white;
                            padding: 40px;
                            border-radius: 10px;
                            box-shadow: 0 0 20px rgba(0,0,0,0.1);
                            opacity: 0;
                            transform: scale(0.9);
                            animation: fadeIn 0.8s ease-out forwards;
                            margin-top: -50px;
                        }
                        @keyframes fadeIn {
                            to {
                                opacity: 1;
                                transform: scale(1);
                            }
                        }
                        h1 {
                            color: #d63638;
                            margin-top: 0;
                        }
                        p {
                            font-size: 16px;
                            line-height: 1.5;
                        }
                        a {
                            color: #000;
                            text-decoration: none;
                            font-weight: bold;
                        }
                        a:hover {
                            text-decoration: underline;
                            color: #333;
                        }
                        .divider {
                            border-top: 1px solid #ddd;
                            margin: 30px 0;
                            padding-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class="placeholder">
                        <h1><?php esc_html_e( 'License not activated..', 'esenin' ); ?></h1>
                        <p><?php printf( __( 'To continue, enter your license key on the %sverification page%s.', 'esenin' ), '<a href="' . esc_url( admin_url( 'themes.php?page=theme-protection' ) ) . '">', '</a>' ); ?></p>
                        <div class="divider"></div>
                        <p><?php printf( __( 'You can get the license key on the %sofficial website%s.', 'esenin' ), '<a href="https://devster.ru/themes/esenin" target="_blank">', '</a>' ); ?></p>
                    </div>
                </body>
                </html>
                <?php
                exit;
            }
        }

        public function is_valid() {
            return 'valid' === $this->get_system_status();
        }

        public function ajax_clear_cache() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                wp_die();
            }
            check_ajax_referer( 'tplm_clear_cache', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            $this->clear_transients();
            wp_send_json_success( esc_html__( 'Update caches cleared successfully.', 'esenin' ) );
        }

        public function ajax_activate() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                wp_die();
            }
            check_ajax_referer( 'tplm_activate', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            $activation_token = sanitize_text_field( $_POST['activation_token'] ?? '' );
            if ( empty( $activation_token ) ) {
                wp_send_json_error( esc_html__( 'Empty license key.', 'esenin' ) );
            }

            $activate_status = $this->api_action( 'activate_license', $activation_token, $this->site_url );
            error_log( 'TPLM Activate Response: ' . $activate_status );

            if ( in_array( $activate_status, array( 'valid', 'activated' ) ) ) {
                update_option( $this->decode_key( 'dGVtcGxhdGUtYWN0aXZhdGlvbg==' ), $activation_token );

                $this->token = $activation_token;
                $check_raw_status = $this->api_action( 'check_license', $this->token, $this->site_url );
                $check_status = $this->normalize_status( $check_raw_status );
                error_log( 'TPLM Post-Activate Check Raw: ' . $check_raw_status . ' | Normalized: ' . $check_status );

                $this->clear_transients();
                $this->update_cache_key = $this->generate_cache_key( $this->token, $this->version, $this->item_id );

                update_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ), $check_status );
                set_transient( $this->status_cache_key, $check_status, HOUR_IN_SECONDS );

                if ( 'valid' === $check_status ) {
                    wp_send_json_success( esc_html__( 'License activated and verified successfully.', 'esenin' ) );
                } else {
                    $detailed_error = $this->get_api_error( $check_raw_status );
                    $warning_msg = sprintf(
                        esc_html__( 'License key saved, but verification failed. Raw status: %s. Error: %s Check server settings (domain mismatch?) or contact devster.ru support.', 'esenin' ),
                        $check_raw_status,
                        $detailed_error
                    );
                    wp_send_json_success( $warning_msg );
                }
            } else {
                wp_send_json_error( $this->get_api_error( $activate_status ) );
            }
        }

        public function ajax_reset() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                wp_die();
            }
            check_ajax_referer( 'tplm_reset', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            delete_option( $this->decode_key( 'dGVtcGxhdGUtYWN0aXZhdGlvbg==' ) );
            delete_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ) );
            delete_transient( $this->status_cache_key );
            $this->token = '';
            $this->clear_transients();
            wp_send_json_success( esc_html__( 'License reset locally.', 'esenin' ) );
        }

        public function ajax_check() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                wp_die();
            }
            check_ajax_referer( 'tplm_check', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            if ( empty( $this->token ) ) {
                wp_send_json_success( 'inactive' );
            }

            $raw_status = $this->api_action( 'check_license', $this->token, $this->site_url );
            $status = $this->normalize_status( $raw_status );
            error_log( 'TPLM Check Raw: ' . $raw_status . ' | Normalized: ' . $status );

            if ( 'error' === $status ) {
                wp_send_json_error( esc_html__( 'Connection error to license server. Check logs.', 'esenin' ) );
            } else {
                update_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ), $status );
                set_transient( $this->status_cache_key, $status, HOUR_IN_SECONDS );
                wp_send_json_success( $status );
            }
        }

        private function api_action( $action, $token_key, $url = '' ) {
            if ( 'get_version' === $action ) {
                return $this->get_version_api( $token_key );
            }

            $api_params = array(
                'edd_action' => $action,
                'license'    => $token_key,
                'item_id'    => $this->item_id,
                'url'        => $url ?: $this->site_url,
                'wp_user'    => get_bloginfo( 'name' ),
            );

            $response = wp_remote_post( esc_url_raw( $this->store_url ), array( 
                'body' => $api_params, 
                'timeout' => 15,
                'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
                'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; Theme Protection'
            ) );

            if ( is_wp_error( $response ) ) {
                error_log( 'TPLM API Error (' . $action . '): ' . $response->get_error_message() );
                return 'error';
            }

            $body = wp_remote_retrieve_body( $response );
            $http_code = wp_remote_retrieve_response_code( $response );
            if ( $http_code !== 200 ) {
                error_log( 'TPLM HTTP Error (' . $action . '): ' . $http_code . ' - ' . $body );
                return 'error';
            }

            $data = json_decode( $body );

            if ( ! $data ) {
                error_log( 'TPLM API Error (' . $action . '): Invalid JSON - ' . $body );
                return 'error';
            }

            error_log( 'TPLM Raw Response (' . $action . '): ' . wp_json_encode( $data ) );

            if ( isset( $data->license ) ) {
                return $data->license;
            } elseif ( isset( $data->error ) ) {
                return $data->error;
            } else {
                return 'invalid';
            }
        }

        private function normalize_status( $raw_status ) {
            if ( in_array( $raw_status, array( 'valid', 'activated' ) ) ) {
                return 'valid';
            } elseif ( in_array( $raw_status, array( 'invalid', 'expired', 'revoked', 'site_inactive', 'no_activations_left', 'deactivated', 'missing', 'key_mismatch' ) ) ) {
                return 'invalid';
            } else {
                return $raw_status;
            }
        }

        private function get_version_api( $token_key ) {
            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => $token_key,
                'name'       => __( 'Esenin', 'esenin' ),
                'slug'       => $this->slug,
                'version'    => $this->version,
                'beta'       => 'no',
                'item_id'    => $this->item_id,
                'url'        => $this->site_url,
            );

            $request_url = add_query_arg( $api_params, $this->store_url );
            $response = wp_remote_get( esc_url_raw( $request_url ), array(
                'timeout'     => 15,
                'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; Theme Checker',
            ) );

            if ( is_wp_error( $response ) ) {
                error_log( 'TPLM Get-Version Error: ' . $response->get_error_message() );
                return new WP_Error( 'api_error', 'Connection failed' );
            }

            $body = wp_remote_retrieve_body( $response );
            if ( wp_remote_retrieve_response_code( $response ) !== 200 || empty( $body ) ) {
                error_log( 'TPLM Get-Version HTTP Error: ' . wp_remote_retrieve_response_code( $response ) . ' - ' . $body );
                return new WP_Error( 'api_error', 'Invalid response' );
            }

            $update_data = json_decode( $body );
            if ( ! $update_data ) {
                error_log( 'TPLM Get-Version JSON Error: ' . $body );
                return new WP_Error( 'json_error', 'Invalid JSON' );
            }

            return $update_data;
        }

        private function get_api_error( $status ) {
            $errors = array(
                'invalid'          => esc_html__( 'Invalid license key. It may be incorrect or already used.', 'esenin' ),
                'expired'          => esc_html__( 'License expired. Renew on devster.ru.', 'esenin' ),
                'revoked'          => esc_html__( 'License revoked. Contact support.', 'esenin' ),
                'site_inactive'    => esc_html__( 'License not active for this site/domain. Check activation URL.', 'esenin' ),
                'no_activations_left' => esc_html__( 'No activations left. Deactivate elsewhere or upgrade license.', 'esenin' ),
                'deactivated'      => esc_html__( 'License deactivated.', 'esenin' ),
                'error'            => esc_html__( 'Connection error to server. Check internet/ firewall.', 'esenin' ),
                'missing'          => esc_html__( 'License key missing or not found.', 'esenin' ),
                'key_mismatch'     => esc_html__( 'License key mismatch with product.', 'esenin' ),
                'inactive'         => esc_html__( 'License inactive on server.', 'esenin' ),
            );
            return $errors[ $status ] ?? sprintf( esc_html__( 'Unknown error: %s. Check devster.ru logs.', 'esenin' ), $status );
        }

        public function validate_system( $use_cache = true ) {
            if ( ! $this->token ) {
                return 'inactive';
            }

            if ( $use_cache ) {
                $cached = get_transient( $this->status_cache_key );
                if ( false !== $cached ) {
                    return $cached;
                }
            }

            $raw_status = $this->api_action( 'check_license', $this->token, $this->site_url );
            $status = $this->normalize_status( $raw_status );
            error_log( 'TPLM Validate Raw: ' . $raw_status . ' | Normalized: ' . $status );

            if ( 'error' !== $status ) {
                set_transient( $this->status_cache_key, $status, HOUR_IN_SECONDS );
                update_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ), $status );
                return $status;
            }

            return $status;
        }

        public function get_system_status() {
            $status = get_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ), '' );
            if ( empty( $status ) && $this->token ) {
                $status = $this->validate_system( true );
                update_option( $this->decode_key( 'dHBsbV9zdGF0dXM=' ), $status );
            }
            return $status ?: ( $this->token ? 'unknown' : 'inactive' );
        }

        public function check_theme_update( $transient ) {
            if ( 'valid' !== $this->get_system_status() || empty( $this->token ) || $this->slug !== get_template() ) {
                return $transient;
            }

            if ( empty( $transient->checked ) ) {
                return $transient;
            }

            if ( ! is_array( $transient->response ) ) {
                $transient->response = (array) $transient->response;
            }

            if ( isset( $transient->response[ $this->slug ] ) && is_array( $transient->response[ $this->slug ] ) ) {
                return $transient;
            }

            $update_data = get_transient( $this->update_cache_key );
            if ( false === $update_data ) {
                $update_data = $this->get_remote_update_info();
                if ( is_wp_error( $update_data ) || empty( $update_data->new_version ) ) {
                    return $transient;
                }
                set_transient( $this->update_cache_key, $update_data, 12 * HOUR_IN_SECONDS );
            }

            if ( version_compare( $this->version, $update_data->new_version, '>=' ) ) {
                return $transient;
            }

            $update = array(
                'theme'       => $this->slug,
                'new_version' => (string) ( $update_data->new_version ?? '' ),
                'url'         => isset( $update_data->homepage ) ? (string) $update_data->homepage : (string) $this->store_url,
                'package'     => isset( $update_data->package ) ? (string) $update_data->package : '',
            );

            if ( ! empty( $update_data->sections ) ) {
                $update['sections'] = $this->ensure_array( $update_data->sections );
            }
            if ( ! empty( $update_data->banners ) ) {
                $update['banners'] = $this->ensure_array( $update_data->banners );
            }

            $transient->response[ $this->slug ] = $update;

            return $transient;
        }

        private function ensure_array( $value ) {
            if ( is_array( $value ) ) {
                return $value;
            }
            if ( is_object( $value ) ) {
                $value = (array) $value;
                foreach ( $value as $k => $v ) {
                    if ( is_object( $v ) || is_array( $v ) ) {
                        $value[ $k ] = $this->ensure_array( $v );
                    }
                }
            } else {
                $value = array( $value );
            }
            return $value;
        }

        private function get_remote_update_info() {
            $update_data = $this->get_version_api( $this->token );

            if ( is_wp_error( $update_data ) ) {
                return $update_data;
            }

            if ( isset( $update_data->error ) || ( empty( $update_data->new_version ) && empty( $update_data->package ) ) ) {
                error_log( 'TPLM Update Error: ' . wp_json_encode( $update_data ) );
                return new WP_Error( 'api_error', isset( $update_data->error ) ? $update_data->error : esc_html__( 'Invalid license or no update available.', 'esenin' ) );
            }

            if ( empty( $update_data->new_version ) || empty( $update_data->package ) ) {
                $update_data->new_version = $this->version;
                return $update_data;
            }

            if ( ! empty( $update_data->sections ) && ! is_array( $update_data->sections ) && is_string( $update_data->sections ) ) {
                $update_data->sections = json_decode( $update_data->sections, true );
                if ( ! is_array( $update_data->sections ) ) {
                    $update_data->sections = array();
                }
            }
            if ( ! empty( $update_data->banners ) && ! is_array( $update_data->banners ) && is_string( $update_data->banners ) ) {
                $update_data->banners = json_decode( $update_data->banners, true );
                if ( ! is_array( $update_data->banners ) ) {
                    $update_data->banners = array();
                }
            }

            return $update_data;
        }

        public function on_theme_activation_check() {
            if ( ! get_option( $this->decode_key( 'dGVtcGxhdGUtYWN0aXZhdGlvbg==' ) ) && get_transient( $this->decode_key( 'dHBsbV9zaG93X25vdGljZQ==' ) ) ) {
                delete_transient( $this->decode_key( 'dHBsbV9zaG93X25vdGljZQ==' ) );
            }
        }

        public static function get_instance() {
            if ( null === static::$instance ) {
                static::$instance = new static();
            }
            return static::$instance;
        }
    }

    TPLM::get_instance();
}

if ( class_exists( 'TPLM' ) && TPLM::get_instance()->is_valid() ) {
    require_once get_theme_file_path( '/core/theme-dashboard/class-tgm-plugin-activation.php' );
}