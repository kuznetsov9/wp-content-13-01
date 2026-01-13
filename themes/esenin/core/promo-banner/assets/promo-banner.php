<?php
/**
 * Esenin Theme License Checker
 *
 * Handles license validation and updates using direct EDD API calls.
 * Product ID: 6350 (configurable)
 * Store URL: https://devster.ru
 *
 * @package Esenin
 * Text Domain: esenin
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * EDD SL Integration for Theme License Management (Simplified - no SDK).
 *
 * Validates license, handles activations/deactivations/resets, saves product ID, and checks for theme updates directly via EDD API.
 */
if ( ! class_exists( 'Esenin_License_Manager' ) ) {

    class Esenin_License_Manager {

        /**
         * The single instance of the class.
         *
         * @var Esenin_License_Manager
         */
        protected static $instance = null;

        /**
         * Product ID from EDD store (dynamic).
         *
         * @var int
         */
        private $item_id = 6350;

        /**
         * Current theme version (dynamic from style.css).
         *
         * @var string
         */
        private $version = '';

        /**
         * License key stored in theme mods or options.
         *
         * @var string
         */
        private $license_key = '';

        /**
         * Store URL for EDD API.
         *
         * @var string
         */
        private $store_url = 'https://devster.ru';

        /**
         * Author for updates.
         *
         * @var string
         */
        private $author = 'Devster';

        /**
         * Theme slug (dynamic).
         *
         * @var string
         */
        private $slug = '';

        /**
         * Transient key for update info cache.
         *
         * @var string
         */
        private $update_cache_key = '';

        /**
         * Transient key for license status cache.
         *
         * @var string
         */
        private $status_cache_key = 'esenin_license_status_cache';

        /**
         * Constructor.
         */
        public function __construct() {
            $this->slug = get_template(); // e.g., 'esenin'
            $this->license_key = get_option( 'esenin_license_key', '' );
            $this->item_id = get_option( 'esenin_item_id', 6350 ); // Dynamic from option, default 6350
            $this->version = wp_get_theme()->get( 'Version' ) ?: '1.6.0'; // Dynamic from style.css
            $this->update_cache_key = 'esenin_update_info_' . md5( $this->license_key . $this->version . $this->item_id );

            // Hooks
            add_action( 'admin_init', array( $this, 'init_license' ) );
            add_action( 'admin_menu', array( $this, 'add_license_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_ajax_esenin_activate_license', array( $this, 'ajax_activate_license' ) );
            add_action( 'wp_ajax_esenin_deactivate_license', array( $this, 'ajax_deactivate_license' ) );
            add_action( 'wp_ajax_esenin_reset_license', array( $this, 'ajax_reset_license' ) );
            add_action( 'wp_ajax_esenin_check_license', array( $this, 'ajax_check_license' ) );
            add_action( 'wp_ajax_esenin_save_item_id', array( $this, 'ajax_save_item_id' ) );
            add_action( 'wp_ajax_esenin_clear_cache', array( $this, 'ajax_clear_cache' ) );

            // Update check hook (fixed for WP theme slugs - array only, with casts).
            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_theme_update' ), 10, 1 );

            add_action( 'admin_init', array( $this, 'on_theme_activation_check' ) );
        }

        /**
         * Initialize license management.
         */
        public function init_license() {
            if ( ! $this->license_key ) {
                return;
            }
            $this->validate_license( true ); // Cached validation.
        }

        /**
         * Clear WP update transients (for debugging cache issues).
         */
        private function clear_update_transients() {
            delete_site_transient( 'update_themes' );
            delete_transient( $this->update_cache_key );
            delete_transient( $this->status_cache_key );
        }

        /**
         * Add admin menu for license management.
         */
        public function add_license_menu() {
            add_theme_page(
                __( 'Esenin License', 'esenin' ),
                __( 'Theme License', 'esenin' ),
                'manage_options',
                'esenin-license',
                array( $this, 'license_page' )
            );
        }

        /**
         * Enqueue scripts for AJAX on license page.
         */
        public function enqueue_scripts( $hook ) {
            if ( 'themes_page_esenin-license' !== get_current_screen()->id ) {
                return;
            }
            // Inline JS is in license_page; no need for separate enqueue.
        }

        /**
         * License management page with AJAX integration (added Clear Cache button).
         */
        public function license_page() {
            $license_status = $this->get_license_status();
            $nonce_activate = wp_create_nonce( 'esenin_activate_license' );
            $nonce_deactivate = wp_create_nonce( 'esenin_deactivate_license' );
            $nonce_reset = wp_create_nonce( 'esenin_reset_license' );
            $nonce_check = wp_create_nonce( 'esenin_check_license' );
            $nonce_save_id = wp_create_nonce( 'esenin_save_item_id' );
            $nonce_clear = wp_create_nonce( 'esenin_clear_cache' );
            $current_item_id = $this->item_id;
            ?>
            <div class="wrap">
                <h1><?php printf( esc_html__( 'Esenin Theme License (v%s)', 'esenin' ), esc_html( $this->version ) ); ?></h1>
                <p><?php esc_html_e( 'Enter your license key from devster.ru for updates and support.', 'esenin' ); ?></p>
                <p><strong><?php esc_html_e( 'Product ID:', 'esenin' ); ?></strong> <?php echo esc_html( $current_item_id ); ?> (<?php esc_html_e( 'Default: 6350. Change if product is recreated.', 'esenin' ); ?>)<br>
                <input type="number" id="item_id_input" value="<?php echo esc_attr( $current_item_id ); ?>" min="1" max="99999" size="6" placeholder="<?php esc_attr_e( 'e.g., 6350', 'esenin' ); ?>" />
                <button type="button" id="save-id-btn" class="button" data-nonce="<?php echo esc_attr( $nonce_save_id ); ?>"><?php esc_html_e( 'Save Product ID', 'esenin' ); ?></button>
                <button type="button" id="clear-cache-btn" class="button-secondary" data-nonce="<?php echo esc_attr( $nonce_clear ); ?>"><?php esc_html_e( 'Clear Update Cache', 'esenin' ); ?></button> <?php esc_html_e( '(Fixes update display issues)', 'esenin' ); ?>
                <br><br>

                <div id="esenin-license-form">
                    <label for="esenin_license_key"><?php esc_html_e( 'License Key:', 'esenin' ); ?></label><br>
                    <input type="password" id="esenin_license_key" name="esenin_license_key" value="<?php echo esc_attr( $this->license_key ? '***hidden***' : '' ); ?>" size="40" placeholder="<?php esc_html_e( 'Enter license key', 'esenin' ); ?>" />
                    <br><br>

                    <?php if ( ! $this->license_key || 'invalid' === $license_status ) : ?>
                        <button type="button" id="activate-btn" class="button-primary" data-nonce="<?php echo esc_attr( $nonce_activate ); ?>"><?php esc_html_e( 'Activate License', 'esenin' ); ?></button>
                    <?php endif; ?>

                    <?php if ( $this->license_key ) : ?>
                        <p><strong><?php esc_html_e( 'Status:', 'esenin' ); ?> </strong><span id="license-status"><?php echo esc_html( ucfirst( $license_status ) ); ?></span></p>
                        <button type="button" id="check-btn" class="button" data-nonce="<?php echo esc_attr( $nonce_check ); ?>"><?php esc_html_e( 'Check Status', 'esenin' ); ?></button>
                        <button type="button" id="deactivate-btn" class="button-secondary" data-nonce="<?php echo esc_attr( $nonce_deactivate ); ?>"><?php esc_html_e( 'Deactivate License', 'esenin' ); ?></button>
                        <button type="button" id="reset-btn" class="button" style="color: #a00;" data-nonce="<?php echo esc_attr( $nonce_reset ); ?>"><?php esc_html_e( 'Reset (Local Only)', 'esenin' ); ?></button>
                    <?php endif; ?>

                    <div id="license-message" style="margin-top: 10px;"></div>
                </div>

                <?php if ( $this->license_key && 'valid' === $license_status ) : ?>
                    <h3><?php esc_html_e( 'Updates', 'esenin' ); ?></h3>
                    <p><?php esc_html_e( 'Your license is active. Check for updates in WP Admin → Updates. New versions will appear if available.', 'esenin' ); ?></p>
                    <p><em><?php esc_html_e( 'Note: ZIP updates must have root structure like "esenin/style.css" for proper installation.', 'esenin' ); ?></em></p>
                <?php endif; ?>

                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var $form = $('#esenin-license-form');
                    var $keyInput = $('#esenin_license_key');
                    var $itemIdInput = $('#item_id_input');
                    var $statusSpan = $('#license-status');
                    var $messageDiv = $('#license-message');
                    var $activateBtn = $('#activate-btn');
                    var $deactivateBtn = $('#deactivate-btn');
                    var $resetBtn = $('#reset-btn');
                    var $checkBtn = $('#check-btn');
                    var $saveIdBtn = $('#save-id-btn');
                    var $clearCacheBtn = $('#clear-cache-btn');

                    // Toggle input visibility
                    $keyInput.on('focus', function() {
                        if ($(this).val() === '***hidden***') {
                            $(this).val('');
                        }
                    });

                    // AJAX helpers
                    function showMessage(msg, isError) {
                        $messageDiv.html('<div class="notice notice-' + (isError ? 'error' : 'success') + ' is-dismissible"><p>' + msg + '</p></div>');
                        if (isError) {
                            console.error('Esenin License Error:', msg);
                        } else {
                            console.log('Esenin License Success:', msg);
                        }
                    }

                    function setLoading(btn, loading) {
                        if (loading) {
                            btn.prop('disabled', true).append('<span class="spinner is-active"></span>');
                        } else {
                            btn.prop('disabled', false).find('.spinner').remove();
                        }
                    }

                    // Clear cache inline (no UI click, no confirm, no reload)
                    function clearCacheInline(nonce) {
                        console.log('Inline clear cache triggered');
                        $.post(ajaxurl, {
                            action: 'esenin_clear_cache',
                            nonce: nonce
                        }, function(response) {
                            if (response.success) {
                                console.log('Cache cleared inline');
                            } else {
                                console.warn('Inline clear failed:', response.data);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            console.error('Inline clear fail:', textStatus);
                        });
                    }

                    function updateUI(lKey, status) {
                        if (lKey) {
                            $keyInput.val('***hidden***');
                            $statusSpan.text(ucfirst(status));
                            if (status === 'valid') {
                                $activateBtn.hide();
                                $checkBtn.show();
                                $deactivateBtn.show();
                                $resetBtn.show();
                                showMessage('<?php esc_js_e( "License active. Updates enabled.", "esenin" ); ?>', false);
                            } else {
                                $activateBtn.show();
                                $checkBtn.show();
                                $deactivateBtn.hide();
                                $resetBtn.show();
                                showMessage('<?php esc_js_e( "License invalid. Please check key.", "esenin" ); ?>', true);
                            }
                        } else {
                            $keyInput.val('');
                            $statusSpan.text('<?php esc_js_e( "inactive", "esenin" ); ?>');
                            $activateBtn.show();
                            $checkBtn.hide();
                            $deactivateBtn.hide();
                            $resetBtn.hide();
                            showMessage('<?php esc_js_e( "No license key. Enter to activate.", "esenin" ); ?>', false);
                        }
                    }

                    // Clear Cache (with confirm and reload)
                    $clearCacheBtn.on('click', function(e) {
                        e.preventDefault();
                        if (!confirm('<?php esc_js_e( "Clear all update caches? This will refresh license and update checks.", "esenin" ); ?>')) return;
                        setLoading($(this), true);
                        console.log('AJAX clear cache sent');
                        $.post(ajaxurl, {
                            action: 'esenin_clear_cache',
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($clearCacheBtn, false);
                            if (response.success) {
                                showMessage('<?php esc_js_e( "Caches cleared. Reload page or check updates again.", "esenin" ); ?>', false);
                                location.reload(); // Reload only for manual clear
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Clear failed.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($clearCacheBtn, false);
                            console.error('AJAX clear fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Connection error. Try again.", "esenin" ); ?>', true);
                        });
                    });

                    // Save Product ID (with clear inline, no reload chain)
                    $saveIdBtn.on('click', function(e) {
                        e.preventDefault();
                        var id = parseInt( $itemIdInput.val().trim() );
                        if (isNaN(id) || id < 1 || id > 99999) {
                            showMessage('<?php esc_js_e( "Invalid Product ID (must be 1-99999).", "esenin" ); ?>', true);
                            return;
                        }
                        setLoading($(this), true);
                        console.log('AJAX save ID sent:', id);
                        $.post(ajaxurl, {
                            action: 'esenin_save_item_id',
                            item_id: id,
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($saveIdBtn, false);
                            if (response.success) {
                                showMessage('<?php esc_js_e( "Product ID saved. Clearing cache..." ); ?>', false);
                                clearCacheInline( '<?php echo esc_js( $nonce_clear ); ?>' ); // Inline clear
                                location.reload(); // Reload only here (ID changed)
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Failed to save ID.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($saveIdBtn, false);
                            console.error('AJAX save ID fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Connection error. Try again.", "esenin" ); ?>', true);
                        });
                    });

                    // Activate (with inline clear on success, no reload)
                    $activateBtn.on('click', function(e) {
                        e.preventDefault();
                        var key = $keyInput.val().trim();
                        if (!key) {
                            showMessage('<?php esc_js_e( "Please enter a license key.", "esenin" ); ?>', true);
                            return;
                        }
                        setLoading($(this), true);
                        console.log('AJAX activate sent:', {license_key: key.length});
                        $.post(ajaxurl, {
                            action: 'esenin_activate_license',
                            license_key: key,
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($activateBtn, false);
                            if (response.success) {
                                updateUI(key, 'valid');
                                clearCacheInline( '<?php echo esc_js( $nonce_clear ); ?>' ); // Inline clear after success
                                showMessage( response.data || '<?php esc_js_e( "Activation successful. Cache cleared.", "esenin" ); ?>', false );
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Activation failed.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($activateBtn, false);
                            console.error('AJAX activate fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Connection error. Try again.", "esenin" ); ?>', true);
                        });
                    });

                    // Deactivate (with inline clear on success)
                    $deactivateBtn.on('click', function(e) {
                        e.preventDefault();
                        if (!confirm('<?php esc_js__( "Deactivate license on server? This will disable updates.", "esenin" ); ?>')) return;
                        setLoading($(this), true);
                        console.log('AJAX deactivate sent');
                        $.post(ajaxurl, {
                            action: 'esenin_deactivate_license',
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($deactivateBtn, false);
                            if (response.success) {
                                updateUI('', 'inactive');
                                clearCacheInline( '<?php echo esc_js( $nonce_clear ); ?>' ); // Inline clear
                                showMessage( response.data || '<?php esc_js_e( "Deactivation successful. Cache cleared.", "esenin" ); ?>', false );
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Deactivation failed.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($deactivateBtn, false);
                            console.error('AJAX deactivate fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Connection error. Try again.", "esenin" ); ?>', true);
                        });
                    });

                    // Reset (with inline clear on success)
                    $resetBtn.on('click', function(e) {
                        e.preventDefault();
                        if (!confirm('<?php esc_js_e( "Reset local license? This won't affect server activation.", "esenin" ); ?>')) return;
                        setLoading($(this), true);
                        console.log('AJAX reset sent');
                        $.post(ajaxurl, {
                            action: 'esenin_reset_license',
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($resetBtn, false);
                            if (response.success) {
                                updateUI('', 'inactive');
                                clearCacheInline( '<?php echo esc_js( $nonce_clear ); ?>' ); // Inline clear
                                showMessage( response.data || '<?php esc_js_e( "Reset successful. Cache cleared.", "esenin" ); ?>', false );
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Reset failed.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($resetBtn, false);
                            console.error('AJAX reset fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Error. Try again.", "esenin" ); ?>', true);
                        });
                    });

                    // Check Status (no clear)
                    $checkBtn.on('click', function(e) {
                        e.preventDefault();
                        setLoading($(this), true);
                        console.log('AJAX check sent');
                        $.post(ajaxurl, {
                            action: 'esenin_check_license',
                            nonce: $(this).data('nonce')
                        }, function(response) {
                            setLoading($checkBtn, false);
                            if (response.success) {
                                updateUI(<?php echo json_encode( $this->license_key ); ?>, response.data);
                            } else {
                                showMessage(response.data || '<?php esc_js_e( "Check failed.", "esenin" ); ?>', true);
                            }
                        }).fail(function(jqXHR, textStatus) {
                            setLoading($checkBtn, false);
                            console.error('AJAX check fail:', textStatus, jqXHR.responseText);
                            showMessage('<?php esc_js_e( "Connection error.", "esenin" ); ?>', true);
                        });
                    });

                    // Auto-check on load if key exists (with delay to avoid race)
                    <?php if ( $this->license_key ) : ?>
                    setTimeout(function() {
                        $checkBtn.trigger('click');
                    }, 100);
                    <?php endif; ?>

                    function ucfirst(str) {
                        return str.charAt(0).toUpperCase() + str.slice(1);
                    }
                });
                </script>
            </div>
            <?php
        }

        /**
         * AJAX: Clear Cache (no reload, just clear).
         */
        public function ajax_clear_cache() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_clear_cache', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Clear Cache called' );
            $this->clear_update_transients();
            wp_send_json_success( esc_html__( 'Update caches cleared successfully.', 'esenin' ) );
        }

        /**
         * AJAX: Save Product ID (with clear).
         */
        public function ajax_save_item_id() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_save_item_id', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Save ID: ' . $_POST['item_id'] );

            $new_id = intval( $_POST['item_id'] ?? 0 );
            if ( $new_id < 1 || $new_id > 99999 ) {
                wp_send_json_error( esc_html__( 'Invalid Product ID (must be 1-99999).', 'esenin' ) );
            }

            if ( update_option( 'esenin_item_id', $new_id ) ) {
                $this->item_id = $new_id;
                $this->clear_update_transients(); // Clear all
                $this->update_cache_key = 'esenin_update_info_' . md5( $this->license_key . $this->version . $this->item_id ); // Recompute key
                wp_send_json_success( esc_html__( 'Product ID saved and caches cleared.', 'esenin' ) );
            } else {
                wp_send_json_error( esc_html__( 'Failed to save Product ID.', 'esenin' ) );
            }
        }

        /**
         * AJAX: Activate License (with clear if valid).
         */
        public function ajax_activate_license() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_activate_license', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Activate: License key length ' . strlen( $_POST['license_key'] ?? '' ) );

            $license_key = sanitize_text_field( $_POST['license_key'] ?? '' );
            if ( empty( $license_key ) ) {
                wp_send_json_error( esc_html__( 'Empty license key.', 'esenin' ) );
            }

            $status = $this->api_license_action( 'activate_license', $license_key, home_url() );
            if ( 'valid' === $status ) {
                update_option( 'esenin_license_key', $license_key );
                $this->license_key = $license_key;
                set_transient( $this->status_cache_key, 'valid', HOUR_IN_SECONDS );
                $this->clear_update_transients(); // Clear all
                wp_send_json_success( esc_html__( 'License activated successfully.', 'esenin' ) );
            } else {
                wp_send_json_error( $this->get_api_error( $status ) );
            }
        }

        /**
         * AJAX: Deactivate License (with clear).
         */
        public function ajax_deactivate_license() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_deactivate_license', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Deactivate called' );

            $status = $this->api_license_action( 'deactivate_license', $this->license_key );
            if ( 'deactivated' === $status ) {
                delete_option( 'esenin_license_key' );
                delete_option( 'esenin_license_status' );
                $this->license_key = '';
                $this->clear_update_transients();
                wp_send_json_success( esc_html__( 'License deactivated successfully.', 'esenin' ) );
            } else {
                wp_send_json_error( $this->get_api_error( $status ) );
            }
        }

        /**
         * AJAX: Reset License (with clear).
         */
        public function ajax_reset_license() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_reset_license', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Reset called' );

            delete_option( 'esenin_license_key' );
            delete_option( 'esenin_license_status' );
            $this->license_key = '';
            $this->clear_update_transients();
            wp_send_json_success( esc_html__( 'License reset locally.', 'esenin' ) );
        }

        /**
         * AJAX: Check License Status.
         */
        public function ajax_check_license() {
            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                return;
            }
            check_ajax_referer( 'esenin_check_license', 'nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( esc_html__( 'Insufficient permissions.', 'esenin' ) );
            }

            error_log( 'Esenin AJAX Check called' );

            $status = $this->validate_license( false ); // Force fresh check.
            if ( 'error' === $status ) {
                wp_send_json_error( esc_html__( 'Connection error to license server.', 'esenin' ) );
            } else {
                wp_send_json_success( $status );
            }
        }

        // ... (остальные методы без изменений: api_license_action, get_api_error, validate_license, get_license_status, check_theme_update, ensure_array, get_remote_update_info, on_theme_activation_check, show_license_notice, get_instance)
        // (Чтобы не удлинять, копируйте их из предыдущего ответа; они идентичны)

        /**
         * Core API action for license (activate/deactivate/check).
         *
         * @param string $action EDD action (activate_license, etc.)
         * @param string $license_key
         * @param string $url Optional site URL for activation.
         * @return string Status or error.
         */
        private function api_license_action( $action, $license_key, $url = '' ) {
            $api_params = array(
                'edd_action' => $action,
                'license'    => $license_key,
                'item_id'    => $this->item_id, // Uses dynamic ID
            );

            if ( 'activate_license' === $action && $url ) {
                $api_params['url'] = $url;
                $api_params['wp_user'] = get_bloginfo( 'name' );
            }

            $response = wp_remote_post( esc_url_raw( add_query_arg( $api_params, $this->store_url ) ), array( 'timeout' => 15 ) );

            if ( is_wp_error( $response ) ) {
                return 'error';
            }

            $body = wp_remote_retrieve_body( $response );
            $license_data = json_decode( $body );

            if ( ! $license_data ) {
                error_log( esc_html__( 'Esenin License API Error: Invalid response - ', 'esenin' ) . $body );
                return 'error';
            }

            return $license_data->license ?? 'invalid';
        }

        /**
         * Get user-friendly error from status (localized).
         *
         * @param string $status
         * @return string
         */
        private function get_api_error( $status ) {
            $errors = array(
                'invalid'     => esc_html__( 'Invalid license key.', 'esenin' ),
                'expired'     => esc_html__( 'License expired.', 'esenin' ),
                'revoked'     => esc_html__( 'License revoked.', 'esenin' ),
                'site_inactive' => esc_html__( 'License not active for this site.', 'esenin' ),
                'no_activations_left' => esc_html__( 'No activations left.', 'esenin' ),
                'error'       => esc_html__( 'Connection error to server.', 'esenin' ),
            );
            return $errors[ $status ] ?? sprintf( esc_html__( 'Unknown error: %s', 'esenin' ), $status );
        }

        /**
         * Validate license status (with cache).
         *
         * @param bool $use_cache Use transient cache.
         * @return string Status.
         */
        public function validate_license( $use_cache = true ) {
            if ( ! $this->license_key ) {
                return 'inactive';
            }

            if ( $use_cache ) {
                $cached = get_transient( $this->status_cache_key );
                if ( false !== $cached ) {
                    return $cached;
                }
            }

            $status = $this->api_license_action( 'check_license', $this->license_key, home_url() );
            if ( 'error' !== $status ) {
                set_transient( $this->status_cache_key, $status, HOUR_IN_SECONDS ); // Cache 1 hour.
                update_option( 'esenin_license_status', $status );
            }

            return $status;
        }

        /**
         * Get current license status.
         *
         * @return string
         */
        public function get_license_status() {
            $status = get_option( 'esenin_license_status', '' );
            if ( empty( $status ) && $this->license_key ) {
                $status = $this->validate_license();
            }
            return $status ?: ( $this->license_key ? 'unknown' : 'inactive' );
        }

        /**
         * Check for theme updates (FIXED: strict array handling, casts, only for active theme).
         *
         * Hooks into 'pre_set_site_transient_update_themes'. Only runs if license is valid.
         * WP expects $transient->response[slug] = array(...) for themes.
         *
         * @param object $transient The update transient.
         * @return object Modified transient.
         */
        public function check_theme_update( $transient ) {
            // Only for active theme and valid license.
            if ( 'valid' !== $this->get_license_status() || empty( $this->license_key ) || $this->slug !== get_template() ) {
                return $transient;
            }

            if ( empty( $transient->checked ) ) {
                return $transient;
            }

            // Ensure response is array (cast if object from stale cache).
            if ( ! is_array( $transient->response ) ) {
                $transient->response = (array) $transient->response;
            }

            // If already have our update and it's array, skip (avoid conflicts).
            if ( isset( $transient->response[ $this->slug ] ) && is_array( $transient->response[ $this->slug ] ) ) {
                return $transient;
            }

            // Get update data.
            $update_data = get_transient( $this->update_cache_key );
            if ( false === $update_data ) {
                $update_data = $this->get_remote_update_info();
                if ( is_wp_error( $update_data ) || empty( $update_data->new_version ) ) {
                    return $transient; // No update or error.
                }
                set_transient( $this->update_cache_key, $update_data, 12 * HOUR_IN_SECONDS );
            }

            if ( version_compare( $this->version, $update_data->new_version, '>=' ) ) {
                return $transient; // No update.
            }

            // Build STRICT ARRAY update (all strings for safety).
            $update = array(
                'theme'       => $this->slug, // string slug.
                'new_version' => (string) $update_data->new_version,
                'url'         => isset( $update_data->homepage ) ? (string) $update_data->homepage : (string) $this->store_url,
                'package'     => isset( $update_data->package ) ? (string) $update_data->package : '',
            );

            // Sections and banners: Ensure array (recursive cast if stdClass).
            if ( ! empty( $update_data->sections ) ) {
                $update['sections'] = $this->ensure_array( $update_data->sections );
            }
            if ( ! empty( $update_data->banners ) ) {
                $update['banners'] = $this->ensure_array( $update_data->banners );
            }

            // Log for debug.
            error_log( 'Esenin Update Added: ' . $this->slug . ' - Type: ' . gettype( $update ) . ', New Version: ' . $update['new_version'] );

            // Set as ARRAY (no object cast).
            $transient->response[ $this->slug ] = $update;

            return $transient;
        }

        /**
         * Ensure value is array (recursive cast for sections/banners).
         *
         * @param mixed $value
         * @return array
         */
        private function ensure_array( $value ) {
            if ( is_array( $value ) ) {
                return $value;
            }
            if ( is_object( $value ) ) {
                $value = (array) $value;
                // Recursive for nested objects.
                foreach ( $value as $k => $v ) {
                    if ( is_object( $v ) ) {
                        $value[ $k ] = $this->ensure_array( $v );
                    }
                }
            } else {
                $value = array( $value ); // Fallback.
            }
            return $value;
        }

        /**
         * Retrieve remote update information from EDD API (improved type casts).
         *
         * @return object|WP_Error Update data or error.
         */
        private function get_remote_update_info() {
            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => $this->license_key,
                'name'       => __( 'Esenin', 'esenin' ), // Theme name.
                'slug'       => $this->slug, // Directory name.
                'version'    => $this->version,
                'beta'       => 'no', // Change to 'yes' for beta.
                'item_id'    => $this->item_id, // Dynamic ID.
            );

            $request_url = add_query_arg( $api_params, $this->store_url );
            $response = wp_remote_get( esc_url_raw( $request_url ), array(
                'timeout'     => 15,
                'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . __( 'Esenin Theme Checker', 'esenin' ),
            ) );

            if ( is_wp_error( $response ) ) {
                return $response;
            }

            $body = wp_remote_retrieve_body( $response );
            if ( wp_remote_retrieve_response_code( $response ) !== 200 || empty( $body ) ) {
                return new WP_Error( 'api_error', esc_html__( 'Invalid API response.', 'esenin' ) );
            }

            $update_data = json_decode( $body );
            if ( ! $update_data ) {
                error_log( sprintf( esc_html__( 'Esenin Update API Error: Invalid JSON - %s', 'esenin' ), $body ) );
                return new WP_Error( 'json_error', esc_html__( 'Invalid JSON response.', 'esenin' ) );
            }

            // If API indicates no update or error (e.g., nested errors), return null (no cache, no update).
            if ( ! empty( $update_data->sections ) && is_array( $update_data->sections ) ) { // EDD error format.
                $update_data->new_version = '0.0.0';
                return $update_data;
            }

            // Require new_version and package (string).
            if ( empty( $update_data->new_version ) || empty( $update_data->package ) ) {
                $update_data->new_version = $this->version; // Prevent false positive.
                return $update_data;
            }

            // Unserialize if needed (though usually array from EDD).
            if ( ! empty( $update_data->sections ) && is_string( $update_data->sections ) ) {
                $update_data->sections = maybe_unserialize( $update_data->sections );
                if ( ! is_array( $update_data->sections ) ) {
                    $update_data->sections = array(); // Ensure array.
                }
            }
            if ( ! empty( $update_data->banners ) && is_string( $update_data->banners ) ) {
                $update_data->banners = maybe_unserialize( $update_data->banners );
                if ( ! is_array( $update_data->banners ) ) {
                    $update_data->banners = array(); // Ensure array.
                }
            }

            // IMPORTANT FOR ZIP: Ensure 'package' points to a ZIP file with root structure 'esenin/style.css'
            // (not wrapped in 'esenin-wp__1.7/'). Configure EDD to export clean theme ZIP without extra folders.
            // If wrapped, WP install may fail ("Theme not found").

            return $update_data;
        }

        /**
         * Check on theme activation and show notice if no license.
         */
        public function on_theme_activation_check() {
            if ( ! get_option( 'esenin_license_key' ) && get_transient( 'esenin_show_license_notice' ) ) {
                add_action( 'admin_notices', array( $this, 'show_license_notice' ) );
                delete_transient( 'esenin_show_license_notice' );
            }
        }

        /**
         * Admin notice for license prompt (localized).
         */
        public function show_license_notice() {
            if ( ! current_user_can( 'manage_options' ) ) return;
            ?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <?php printf( esc_html__( 'Esenin theme activated! For full features and updates, %senter your license key%s from devster.ru.', 'esenin' ), '<a href="' . admin_url( 'themes.php?page=esenin-license' ) . '">', '</a>' ); ?>
                </p>
            </div>
            <?php
        }

        /**
         * Get instance of the class.
         *
         * @return Esenin_License_Manager
         */
        public static function get_instance() {
            if ( null === static::$instance ) {
                static::$instance = new static();
            }
            return static::$instance;
        }
    }

    Esenin_License_Manager::get_instance();
}