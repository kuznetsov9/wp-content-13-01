( function( $ ) {
	"use strict";

	/** ----------------------------------------------------------------------------
	 * Theme Dashboard */

	var esnThemeDashboard = {};

	( function() {
		var $this;

		esnThemeDashboard = {

			/** Initialize */
			init: function( e ) {

				$this = esnThemeDashboard;

				// Init events.
				$this.events( e );
			},

			/** Events */
			events: function( e ) {

				$( document ).on( 'click', '.es-panel-tabs .es-panel-tab a', function( e ) {
					$this.activePanel( e, this );
				});
			},

			/** Active Panel */
			activePanel: function( e, object ) {
				let $index = $( object ).closest( '.es-panel-tab' ).index();

				// Set location.
				window.history.replaceState( '', '', $( object ).attr( 'href' ) );

				// Nav Tabs.
				$( object ).closest( '.es-panel-tab' ).addClass( 'es-panel-tab-active' ).siblings().removeClass( 'es-panel-tab-active' );

				// Content Tabs.
				$( '.es-panel-content-tabs .es-panel-tab' ).eq( $index ).addClass( 'es-panel-tab-active' ).siblings().removeClass( 'es-panel-tab-active' );

				e.preventDefault();
			},
		};

	} )();

	// Initialize.
	esnThemeDashboard.init();

} )( jQuery );
