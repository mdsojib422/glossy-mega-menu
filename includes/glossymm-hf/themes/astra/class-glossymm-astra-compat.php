<?php
/**
 * Glossymm_Astra_Compat setup
 *
 * @package header-footer-elementor
 */

/**
 * Astra theme compatibility.
 */
class Glossymm_Astra_Compat {

	/**
	 * Instance of Glossymm_Astra_Compat.
	 *
	 * @var Glossymm_Astra_Compat
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Glossymm_Astra_Compat();
			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}
		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		if ( glossymm_header_enabled() ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_header' ], 10 );
			add_action( 'astra_header', 'glossymm_render_header' );
		}

		if ( glossymm_footer_enabled() ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_footer' ], 10 );
			add_action( 'astra_footer', 'glossymm_render_footer' );
		}	
	}

	/**
	 * Disable header from the theme.
	 */
	public function astra_setup_header() {
		remove_action( 'astra_header', 'astra_header_markup' );

		// Remove the new header builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_header', [ Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ] );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function astra_setup_footer() {
		remove_action( 'astra_footer', 'astra_footer_markup' );

		// Remove the new footer builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_footer', [ Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
		}
	}
}

Glossymm_Astra_Compat::instance();
