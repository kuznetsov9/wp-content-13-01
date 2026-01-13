<?php
/**
 * Menu Customization.
 *
 * @package Esenin
 */

/**
 * -------------------------------------------------------------------------
 * [ Menu Item Customization for All Menus ]
 * -------------------------------------------------------------------------
 */

global $menu_icons;
$menu_icons = array(
	'file'      => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path d="M17.5002 6.6665V17.4942C17.5002 17.9583 17.1296 18.3332 16.6724 18.3332H3.32808C2.87104 18.3332 2.50024 17.9632 2.50024 17.5067V2.493C2.50024 2.04593 2.87416 1.6665 3.33542 1.6665H12.4976L17.5002 6.6665ZM15.8336 7.49984H11.6669V3.33317H4.16691V16.6665H15.8336V7.49984ZM6.66691 5.83317H9.16691V7.49984H6.66691V5.83317ZM6.66691 9.1665H13.3336V10.8332H6.66691V9.1665ZM6.66691 12.4998H13.3336V14.1665H6.66691V12.4998Z"/></svg>',
	'briefcase' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path d="M5.83354 4.16646V1.66646C5.83354 1.20623 6.20664 0.83313 6.66687 0.83313H13.3335C13.7938 0.83313 14.1669 1.20623 14.1669 1.66646V4.16646H17.5002C17.9605 4.16646 18.3335 4.53956 18.3335 4.9998V16.6665C18.3335 17.1267 17.9605 17.4998 17.5002 17.4998H2.5002C2.03997 17.4998 1.66687 17.1267 1.66687 16.6665V4.9998C1.66687 4.53956 2.03997 4.16646 2.5002 4.16646H5.83354ZM3.33354 13.3331V15.8331H16.6669V13.3331H3.33354ZM3.33354 11.6665H16.6669V5.83313H3.33354V11.6665ZM7.5002 2.4998V4.16646H12.5002V2.4998H7.5002ZM9.16687 9.16646H10.8335V10.8331H9.16687V9.16646Z"/></svg>',
	'marker'    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path d="M14.8743 9.74865L14.2851 9.1594L6.03553 17.409H2.5V13.8734L11.9281 4.44534L16.6422 9.1594C16.9676 9.48482 16.9676 10.0125 16.6422 10.3379L10.7496 16.2305L9.57108 15.0519L14.8743 9.74865ZM13.1066 7.98087L11.9281 6.80235L4.16667 14.5638V15.7423H5.34518L13.1066 7.98087ZM15.4636 2.08831L17.8207 4.44534C18.1461 4.77077 18.1461 5.29841 17.8207 5.62385L16.6422 6.80235L13.1066 3.26682L14.2851 2.08831C14.6106 1.76288 15.1382 1.76288 15.4636 2.08831Z"/></svg>',

    'clock'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12,24C5.383,24,0,18.617,0,12S5.383,0,12,0s12,5.383,12,12-5.383,12-12,12Zm0-22C6.486,2,2,6.486,2,12s4.486,10,10,10,10-4.486,10-10S17.514,2,12,2Zm5,10c0-.553-.447-1-1-1h-3V6c0-.553-.448-1-1-1s-1,.447-1,1v6c0,.553,.448,1,1,1h4c.553,0,1-.447,1-1Z"/></svg>',
    'flame'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M18.044,5.529c-1.098-1.426-2.233-2.9-3.036-4.495C14.669,.359,13.995-.048,13.256-.008c-.713,.041-1.314,.496-1.569,1.188-.342,.933-.687,2.638-.687,4.345,0,2.209,.688,3.701,1.24,4.901,.469,1.017,.839,1.82,.746,2.847-.09,.979-.946,1.735-2.027,1.727-2.585-.029-3.243-3.303-3.338-6.043-.032-.897-.637-1.655-1.505-1.885-.868-.229-1.77,.132-2.241,.896-1.208,1.961-1.874,4.103-1.874,6.031,0,5.514,4.523,10,10.006,10,5.511-.033,9.994-4.52,9.994-9.983,.056-3.276-1.983-5.925-3.956-8.487Zm-6.05,16.471c-4.38,0-7.994-3.589-7.994-8,0-1.54,.575-3.356,1.622-4.973,.179,5.104,2.066,7.936,5.315,7.973h.047c2.061,0,3.811-1.548,3.994-3.544,.142-1.56-.398-2.732-.921-3.866-.52-1.128-1.057-2.294-1.057-4.064,0-1.153,.196-2.384,.423-3.206,.863,1.606,1.967,3.04,3.037,4.431,1.843,2.395,3.584,4.656,3.541,7.25,0,4.385-3.591,7.974-8.006,8Z"/></svg>',
    'my-feed'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="m10,23c0,.553-.448,1-1,1h-4c-2.757,0-5-2.243-5-5v-9.276c0-1.665.824-3.214,2.204-4.145L9.203.855c1.699-1.146,3.895-1.146,5.594,0l7.203,4.893c.991.75,1.692,1.846,1.917,3.071.1.543-.259,1.064-.803,1.164-.544.104-1.064-.26-1.164-.803-.145-.787-.608-1.495-1.272-1.943l-7-4.724c-1.019-.689-2.336-.689-3.355,0L3.322,7.237c-.828.559-1.322,1.487-1.322,2.486v9.276c0,1.654,1.346,3,3,3h4c.552,0,1,.447,1,1Zm14-6c0,3.859-3.14,7-7,7s-7-3.141-7-7,3.14-7,7-7,7,3.141,7,7Zm-2,0c0-2.757-2.243-5-5-5s-5,2.243-5,5,2.243,5,5,5,5-2.243,5-5Zm-3.192-1.241l-2.223,2.134c-.143.143-.378.142-.522,0l-1.132-1.108c-.394-.385-1.027-.379-1.414.016-.387.395-.38,1.027.015,1.414l1.132,1.108c.459.449,1.062.674,1.664.674s1.201-.225,1.653-.671l2.212-2.124c.398-.383.412-1.016.029-1.414-.382-.398-1.016-.411-1.414-.029Z"/></svg>',
    'users'    => '<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m7.5 13a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5zm7.5 17v-.5a7.5 7.5 0 0 0 -15 0v.5a1 1 0 0 0 2 0v-.5a5.5 5.5 0 0 1 11 0v.5a1 1 0 0 0 2 0zm9-5a7 7 0 0 0 -11.667-5.217 1 1 0 1 0 1.334 1.49 5 5 0 0 1 8.333 3.727 1 1 0 0 0 2 0zm-6.5-9a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5z"/></svg>',
    'compass'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M13.987,6.108c-.039.011-7.228,2.864-7.228,2.864a2.76,2.76,0,0,0,.2,5.212l2.346.587.773,2.524A2.739,2.739,0,0,0,12.617,19h.044a2.738,2.738,0,0,0,2.532-1.786s2.693-7.165,2.7-7.2a3.2,3.2,0,0,0-3.908-3.907ZM15.97,9.467,13.322,16.51a.738.738,0,0,1-.692.49c-.1-.012-.525-.026-.675-.378l-.908-2.976a1,1,0,0,0-.713-.679l-2.818-.7a.762.762,0,0,1-.027-1.433l7.06-2.8a1.149,1.149,0,0,1,1.094.32A1.19,1.19,0,0,1,15.97,9.467ZM12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/></svg>',
    'info'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M12,10H11a1,1,0,0,0,0,2h1v6a1,1,0,0,0,2,0V12A2,2,0,0,0,12,10Z"/><circle cx="12" cy="6.5" r="1.5"/></svg>',
    'feedback'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M21,12.424V11A9,9,0,0,0,3,11v1.424A5,5,0,0,0,5,22a2,2,0,0,0,2-2V14a2,2,0,0,0-2-2V11a7,7,0,0,1,14,0v1a2,2,0,0,0-2,2v6H14a1,1,0,0,0,0,2h5a5,5,0,0,0,2-9.576ZM5,20H5a3,3,0,0,1,0-6Zm14,0V14a3,3,0,0,1,0,6Z"/></svg>',
    'messages'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M23.119.882a2.966,2.966,0,0,0-2.8-.8l-16,3.37a4.995,4.995,0,0,0-2.853,8.481L3.184,13.65a1,1,0,0,1,.293.708v3.168a2.965,2.965,0,0,0,.3,1.285l-.008.007.026.026A3,3,0,0,0,5.157,20.2l.026.026.007-.008a2.965,2.965,0,0,0,1.285.3H9.643a1,1,0,0,1,.707.292l1.717,1.717A4.963,4.963,0,0,0,15.587,24a5.049,5.049,0,0,0,1.605-.264,4.933,4.933,0,0,0,3.344-3.986L23.911,3.715A2.975,2.975,0,0,0,23.119.882ZM4.6,12.238,2.881,10.521a2.94,2.94,0,0,1-.722-3.074,2.978,2.978,0,0,1,2.5-2.026L20.5,2.086,5.475,17.113V14.358A2.978,2.978,0,0,0,4.6,12.238Zm13.971,7.17a3,3,0,0,1-5.089,1.712L11.762,19.4a2.978,2.978,0,0,0-2.119-.878H6.888L21.915,3.5Z"/></svg>',
	'table'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
<path d="M15.091,16C21.661,15.964,24,12.484,24,9.5a3.5,3.5,0,0,0-2.764-3.419c.136-.387.254-.742.333-1.011a3.887,3.887,0,0,0-.626-3.458A3.979,3.979,0,0,0,17.729,0H6.271A3.979,3.979,0,0,0,3.057,1.612,3.887,3.887,0,0,0,2.431,5.07c.079.269.2.624.333,1.011A3.5,3.5,0,0,0,0,9.5c0,2.984,2.339,6.464,8.909,6.5A5.06,5.06,0,0,1,9,16.921V20a1.883,1.883,0,0,1-2,2H6a1,1,0,0,0,0,2H18a1,1,0,0,0,0-2h-.992A1.885,1.885,0,0,1,15,20V16.92A5.058,5.058,0,0,1,15.091,16ZM20.5,8A1.5,1.5,0,0,1,22,9.5c0,2.034-1.609,4.2-6.036,4.47a4.847,4.847,0,0,1,.762-.821A15.132,15.132,0,0,0,20.453,7.99C20.469,7.991,20.483,8,20.5,8ZM2,9.5A1.5,1.5,0,0,1,3.5,8c.017,0,.031-.009.047-.01a15.132,15.132,0,0,0,3.727,5.159,4.847,4.847,0,0,1,.762.821C3.609,13.7,2,11.534,2,9.5ZM10.513,22A4.08,4.08,0,0,0,11,20V16.921a6.93,6.93,0,0,0-2.431-5.295A15.338,15.338,0,0,1,4.349,4.5a1.9,1.9,0,0,1,.31-1.694A1.994,1.994,0,0,1,6.271,2H17.729a1.994,1.994,0,0,1,1.612.81,1.9,1.9,0,0,1,.31,1.694,15.338,15.338,0,0,1-4.22,7.122A6.928,6.928,0,0,0,13,16.92V20a4.08,4.08,0,0,0,.487,2Z"/></svg>',
);

if ( ! function_exists( 'esn_menu_item_args' ) ) {
	/**
	 * Filters the arguments for a single nav menu item.
	 *
	 * @param object $args  An object of wp_nav_menu() arguments.
	 * @param object $item  (WP_Post) Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 */
	function esn_menu_item_args( $args, $item, $depth ) {
		$args->link_before = '';
		$args->link_after  = '';

		// Only apply to top-level items (depth 0).
		if ( 0 === $depth ) {
			$args->link_before = '<span>';
			$args->link_after  = '</span>';
									
			// Retrieve the image source type.
			$image_source = get_post_meta( $item->ID, '_esn_menu_item_image_source', true );

			if ( 'custom' === $image_source ) {
				// Retrieve the image for the menu item.
				$item_image = get_post_meta( $item->ID, '_esn_menu_item_image', true );

				if ( $item_image ) {
					$args->link_before = '<span><span class="esn-menu-item-image">' . esn_get_retina_image(
						$item_image,
						array( 'alt' => esc_attr( $item->title ) ),
						'img',
						false
					) . '</span>';
				}
			} elseif ( 'preset' === $image_source ) {
				// Display the icon from the library.
				$item_icon = get_post_meta( $item->ID, '_esn_menu_item_icon', true );

				if ( $item_icon && isset( $GLOBALS['menu_icons'] ) && $GLOBALS['menu_icons'][ $item_icon ] ) {
					$args->link_before = '<span><span class="esn-menu-item-icon"  style="fill: var(--es-color-secondary) !important;">' . call_user_func( 'sprintf', '%s', $GLOBALS['menu_icons'][ $item_icon ] ) . '</span>';
				}
			}
		}

		return $args;
	}
	add_filter( 'nav_menu_item_args', 'esn_menu_item_args', 10, 3 );
}

if ( version_compare( get_bloginfo( 'version' ), '5.4', '>=' ) ) {
	/**
	 * Add style custom fields to all menu items
	 *
	 * @param int $id object id.
	 */
	function esn_menu_item_style_fields( $id ) {
		// Get menu locations and IDs.
		$locations = get_nav_menu_locations();

		// Check if the menu item belongs to the specific locations.
		$primary_menu_id        = $locations['primary'] ?? 0;
		$footer_menu_id = $locations['footer'] ?? 0;
		$mobile_primary_menu_id         = $locations['mobile_primary'] ?? 0;
		$mobile_footer_menu_id         = $locations['mobile_footer'] ?? 0;

		$is_primary_location        = $primary_menu_id && has_term( $primary_menu_id, 'nav_menu', $id );
		$is_footer_location = $footer_menu_id && has_term( $footer_menu_id, 'nav_menu', $id );
		$is_mobile_primary_location         = $mobile_primary_menu_id && has_term( $mobile_primary_menu_id, 'nav_menu', $id );
		$is_mobile_footer_location         = $mobile_footer_menu_id && has_term( $mobile_footer_menu_id, 'nav_menu', $id );

		// Only show custom fields for primary, mobile, and footer menu locations.
		if ( ! ( $is_primary_location || $is_footer_location || $is_mobile_primary_location || $is_mobile_footer_location ) ) {
			return;
		}

		// Enqueue the media library.
		if ( is_admin() ) {
			wp_enqueue_media();
		}

		wp_nonce_field( 'esn_menu_meta_nonce', 'esn_menu_meta_nonce_name' );
		$menu_item = wp_setup_nav_menu_item( get_post( $id ) );

		if ( ! $menu_item ) {
			return;
		}

		// Apply to all top-level menu items.
		if ( '0' === $menu_item->menu_item_parent ) {
			$item_style  = get_post_meta( $id, '_esn_menu_item_style', true );
			$item_styles = array(
				'none' => esc_html__( 'None', 'esenin' ),
				'icon' => esc_html__( 'Flash Icon', 'esenin' ),
			);

			// Add the new image source selection dropdown.
			$image_source = get_post_meta( $id, '_esn_menu_item_image_source', true );
			$source_types = array(
				'none'   => esc_html__( 'None', 'esenin' ),
				'preset' => esc_html__( 'Preset', 'esenin' ),
				/* 'custom' => esc_html__( 'Custom', 'esenin' ), */
			);

			?>
			<p class="description description-thin">
				<label for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Icon "Pulse"', 'esenin' ); ?></label>
				<select class="widefat" name="esn_menu_item_style[<?php echo esc_attr( $id ); ?>]">
					<?php foreach ( $item_styles as $value => $label ) { ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $item_style, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p class="description description-thin">
				<label for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Menu Icon', 'esenin' ); ?></label>
				<select class="widefat esn-menu-item-image-source" name="esn_menu_item_image_source[<?php echo esc_attr( $id ); ?>]" id="esn_menu_item_image_source<?php echo esc_attr( $id ); ?>">
					<?php foreach ( $source_types as $value => $label ) { ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $image_source, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<div class="esn-menu-item-preset <?php echo 'preset' !== $image_source ? 'hidden' : ''; ?>">
				<p class="description description-thin">
					<label><?php esc_html_e( 'Select Icon', 'esenin' ); ?></label>
					<select class="widefat esn-icon-select" name="esn_menu_item_icon[<?php echo esc_attr( $id ); ?>]" id="esn_menu_item_icon<?php echo esc_attr( $id ); ?>">
						<option value="none"><?php esc_html_e( 'None', 'esenin' ); ?></option>
						<?php
						$item_icon = get_post_meta( $id, '_esn_menu_item_icon', true );
						foreach ( $GLOBALS['menu_icons'] as $key => $icon ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $item_icon, $key ); ?>><?php echo esc_html( ucfirst( $key ) ); ?></option>
						<?php } ?>
					</select>
				</p>
				<p id="esn-icon-preview-<?php echo esc_attr( $id ); ?>" class="description description-thin">
					<span style="display:flex;height:30px;align-items:center;margin-top:21.5px;"></span>
				</p>
			</div>

			<div class="esn-menu-item-custom-image <?php echo 'custom' !== $image_source ? 'hidden' : ''; ?>">
				<?php
				// Get the image URL for the current menu item.
				$item_image = get_post_meta( $id, '_esn_menu_item_image', true );
				?>
				<p class="description description-wide">
					<label for="edit-menu-item-image-<?php echo esc_attr( $id ); ?>">
						<?php esc_html_e( 'Menu Item Image', 'esenin' ); ?>
					</label>
					<br />

					<?php if ( $item_image ) : ?>
						<img src="<?php echo esc_url( wp_get_attachment_url( $item_image ) ); ?>" class="esn-menu-item-image-preview" style="max-width: 100%; height: 30px; margin-right: 5px;" alt="">
					<?php endif; ?>

					<input type="hidden" id="edit-menu-item-image-<?php echo esc_attr( $id ); ?>" name="esn_menu_item_image[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $item_image ); ?>" />

					<button type="button" class="button esn-upload-image" style="vertical-align: top;">
						<?php echo wp_kses( $item_image ? esc_html__( 'Change Image', 'esenin' ) : esc_html__( 'Upload Image', 'esenin' ), 'post' ); ?>
					</button>

					<?php if ( $item_image ) : ?>
						<button type="button" class="button esn-remove-image" style="vertical-align: top;margin-left: 5px;"><?php esc_html_e( 'Remove Image', 'esenin' ); ?></button>
					<?php endif; ?>
				</p>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function($) {
					// Toggle visibility based on selected image source.
					$('#esn_menu_item_image_source<?php echo esc_attr( $id ); ?>').on('change', function() {
						var value = $(this).val();
						$(this).closest('.menu-item-settings').find('.esn-menu-item-preset').toggleClass('hidden', value !== 'preset');
						$(this).closest('.menu-item-settings').find('.esn-menu-item-custom-image').toggleClass('hidden', value !== 'custom');
					});


					$('.esn-upload-image').off('click').on('click', function(e) {
						e.preventDefault();

						// Initialize a new instance of the media uploader each time.
						const button = $(this);
						const idField = button.prev('input');

						const custom_uploader = wp.media({
							title: '<?php esc_html_e( 'Select Image', 'esenin' ); ?>',
							button: { text: '<?php esc_html_e( 'Use this image', 'esenin' ); ?>' },
							multiple: false
						});

						custom_uploader.on('select', function() {
							const attachment = custom_uploader.state().get('selection').first().toJSON();
							idField.val(attachment.id);

							// Remove the previous preview image if it exists and add the new one.
							button.prevAll('.esn-menu-item-image-preview').remove();
							button.before('<img src="' + attachment.url + '" class="esn-menu-item-image-preview" style="max-width: 100%; height: 30px; margin-right: 5px;">');

							// Update button text and add remove button if not already present.
							button.text('<?php esc_html_e( 'Change Image', 'esenin' ); ?>');
							if (!button.next('.esn-remove-image').length) {
								button.after('<button type="button" class="button esn-remove-image" style="vertical-align: top; margin-left: 5px;"><?php esc_html_e( 'Remove Image', 'esenin' ); ?></button>');
							}
						});

						// Open the media uploader.
						custom_uploader.open();
					});

					// Remove the image.
					$(document).on('click', '.esn-remove-image', function(e) {
						e.preventDefault();
						const button = $(this).prev('.esn-upload-image');
						const idField = button.prev('input');

						// Clear the hidden field and remove the preview image.
						idField.val('');
						button.prevAll('.esn-menu-item-image-preview').remove();

						// Reset the button text and remove the "Remove Image" button.
						button.text('<?php esc_html_e( 'Upload Image', 'esenin' ); ?>');
						$(this).remove();
					});

					var iconSVGs = <?php echo wp_json_encode( $GLOBALS['menu_icons'] ); ?>;

					function updateIconPreview() {
						var selectedIcon = $('#esn_menu_item_icon<?php echo esc_attr( $id ); ?>').val();
						var previewContainer = $('#esn-icon-preview-<?php echo esc_attr( $id ); ?> span');

						// Update the preview container with the selected SVG, or clear if "None" is selected
						if (iconSVGs[selectedIcon]) {
							previewContainer.html(iconSVGs[selectedIcon]);
						} else {
							previewContainer.empty();
						}
					}

					// Initial preview on page load
					updateIconPreview();

					// Update preview on dropdown change
					$('#esn_menu_item_icon<?php echo esc_attr( $id ); ?>').on('change', updateIconPreview);
				});
			</script>
			<?php
		}
	}
	add_action( 'wp_nav_menu_item_custom_fields', 'esn_menu_item_style_fields' );

	/**
	 * Save the style menu item meta for all menus
	 *
	 * @param int $menu_id menu id.
	 * @param int $menu_item_db_id menu item db id.
	 */
	function esn_menu_item_style_fields_update( $menu_id, $menu_item_db_id ) {

		// Check ajax.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Security.
		check_admin_referer( 'esn_menu_meta_nonce', 'esn_menu_meta_nonce_name' );

		// Save style.
		if ( isset( $_POST['esn_menu_item_style'][ $menu_item_db_id ] ) ) {
			$sanitized_data = sanitize_text_field( $_POST['esn_menu_item_style'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, '_esn_menu_item_style', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, '_esn_menu_item_style' );
		}

		// Save image source.
		if ( isset( $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] ) ) {
			$image_source = sanitize_text_field( $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, '_esn_menu_item_image_source', $image_source );
		} else {
			delete_post_meta( $menu_item_db_id, '_esn_menu_item_image_source' );
		}

		// Save selected icon if the source is "preset".
		if ( isset( $_POST['esn_menu_item_icon'][ $menu_item_db_id ] ) && isset( $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] ) && $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] === 'preset' ) {
			$icon = sanitize_text_field( $_POST['esn_menu_item_icon'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, '_esn_menu_item_icon', $icon );
		} else {
			delete_post_meta( $menu_item_db_id, '_esn_menu_item_icon' );
		}

		// Save custom image URL if the source is "custom".
		if ( isset( $_POST['esn_menu_item_image'][ $menu_item_db_id ] ) && isset( $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] ) && $_POST['esn_menu_item_image_source'][ $menu_item_db_id ] === 'custom' ) {
			$image_url = sanitize_text_field( $_POST['esn_menu_item_image'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, '_esn_menu_item_image', $image_url );
		} else {
			delete_post_meta( $menu_item_db_id, '_esn_menu_item_image' );
		}
	}
	add_action( 'wp_update_nav_menu_item', 'esn_menu_item_style_fields_update', 10, 2 );

	/**
	 * Filters the CSS class(es) applied to a menu item's list item element for all menus.
	 *
	 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $item    The current menu item.
	 * @param stdClass $args    An object of wp_nav_menu() arguments.
	 * @param int      $depth   Depth of menu item. Used for padding.
	 * @return array (Maybe) modified CSS classes.
	 */
	function esn_menu_item_classes( $classes, $item, $args, $depth ) {
		$item_style = get_post_meta( $item->ID, '_esn_menu_item_style', true );
		$item_image = get_post_meta( $item->ID, '_esn_menu_item_image', true );
		$item_icon  = get_post_meta( $item->ID, '_esn_menu_item_icon', true );

		if ( $item_style ) {
			$classes[] = 'esn-menu-item-style-' . $item_style;
		}

		if ( $item_image ) {
			$classes[] = 'esn-menu-item-has-image';
		}

		if ( $item_icon ) {
			$classes[] = 'esn-menu-item-has-icon';
		}

		return $classes;
	}
	add_filter( 'nav_menu_css_class', 'esn_menu_item_classes', 10, 4 );
}