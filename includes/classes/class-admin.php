<?php
namespace GlossyMM;
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Admin {
    public static $instance = '';

    /**
     * Singleton Instance
     * @return $instance
     */
    public static function Instance() {
        if ( self::$instance == null ) {
            self::$instance = new Admin();
        }
        return self::$instance;
    }
    public function __construct() {
        add_action( 'admin_menu', [$this, 'glossymm_options_page'] );
        add_action( 'admin_enqueue_scripts', [$this, 'glossymm_options_page_assets'] );
    }

    public function glossymm_options_page_assets( $screen ) {
        if ( $screen !== 'toplevel_page_glossymm-options' ) {
            return;
        }
        wp_enqueue_style( 'glossymm-options-page', GLOSSYMM_ADMIN_ASSETS . '/options/css/options-panel.css' ); 
        wp_enqueue_script( 'glossymm-options-page', GLOSSYMM_ADMIN_ASSETS . '/options/js/options-panel.js', [], time(), true );

    }

    public function glossymm_options_page() {
        add_menu_page(
            __( "GlossyMM Options", "glossy-mega-menu" ),
            __( "GlossyMM", "glossy-mega-menu" ),
            'manage_options', // Capability
            'glossymm-options', // Menu slug
            [$this, 'glossymm_options_page_display'],
            'dashicons-admin-generic', // Icon
            20
        );

    }

    public function glossymm_options_page_display() {
        echo "<div id='glossymm-options'></div>";
    }

}
