<?php
namespace GlossyMM;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [$this, 'register'], 5 );
        } else {
            add_action( 'wp_enqueue_scripts', [$this, 'register'], 5 );
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version = isset( $script['version'] ) ? $script['version'] : GLOSSYMM_VERSION;
            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, GLOSSYMM_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'glossymm-nav-menu' => [
                'src'       => GLOSSYMM_FRONTEND_ASSETS . '/js/nav-menu.js',
                'deps'      => ['jquery'],
                'version'   => time(),
                'in_footer' => true,
            ],
            'glossymm-vertical-nav-menu' => [
                'src'       => GLOSSYMM_FRONTEND_ASSETS . '/js/vertical-nav-menu.js',
                'deps'      => ['jquery'],
                'version'   => time(),
                'in_footer' => true,
            ],
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'glossymm-nav-menu'            => [
                'src' => GLOSSYMM_FRONTEND_ASSETS . '/css/nav-menu.css',
            ],
            'glossymm-vertical-nav-menu'            => [
                'src' => GLOSSYMM_FRONTEND_ASSETS . '/css/vertical-nav-menu.css',
            ],
            'glossymm-responsive-nav-menu' => [
                'src' => GLOSSYMM_FRONTEND_ASSETS . '/css/responsive-nav-menu.css',
            ],
        ];

        return $styles;
    }

}
