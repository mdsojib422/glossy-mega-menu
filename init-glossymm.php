<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !class_exists( "GlossyMM" ) ) {

    final class GlossyMM {

        /**
         * Instance Of GlossyMM
         *
         * @var [instance]
         */
        private static $instance = null;

        /**
         * Container Of Classes
         *
         * @var array
         */
        public $controllers = [];

        /**
         * Construct of GlossyMM
         */
        function __construct() {
            add_action( "plugin_loaded", [$this, "init"] );
            if ( is_admin() ) {
                add_action( "admin_enqueue_scripts", [$this, "glossymm_admin_assets"] );
            }
        }

        /**
         * Singleton Instance
         * @return $instance
         */
        public static function Instance() {
            if ( self::$instance == null ) {
                self::$instance = new GlossyMM();
            }
            return self::$instance;
        }

        /**
         * Initialization
         * @return void
         */
        public function init() {
            if ( !function_exists( 'is_plugin_active' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
            }
            // Fire on plugins load and ready the textdomain for the plugin.
            $this->glossymm_load_textdomain();
            //define constants for plugin
            $this->defineConstants();
            // Included Required Files
            $this->includeFiles();
            // Instantiate Classes
            $this->instantiateClasses();
            /* Glossymm Elementor Header Footer */
            if ( !is_plugin_active( "elementor-pro/elementor-pro.php" ) ) {
                require_once GLOSSYMM_PATH . "/includes/glossymm-hf/glossymm-hf.php";
            }          
        }

        /**
         * Define Constant
         * @return void
         */
        public function defineConstants() {
            if ( !defined( "GLOSSYMM_URL" ) ) {
                define( "GLOSSYMM_URL", plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( "GLOSSYMM_VERSION" ) ) {
                define( "GLOSSYMM_VERSION", '1.0.0' );
            }
            if ( !defined( "GLOSSYMM_PATH" ) ) {
                define( "GLOSSYMM_PATH", plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( "GLOSSYMM_HF_PATH" ) ) {
                define( "GLOSSYMM_HF_PATH", plugin_dir_path( __FILE__ ) . 'includes/glossymm-hf' );
            }
            if ( !defined( "GLOSSYMM_ADMIN_ASSETS" ) ) {
                define( "GLOSSYMM_ADMIN_ASSETS", plugin_dir_url( __FILE__ ) . "assets/admin" );
            }
            if ( !defined( "GLOSSYMM_FRONTEND_ASSETS" ) ) {
                define( "GLOSSYMM_FRONTEND_ASSETS", plugin_dir_url( __FILE__ ) . "assets/frontend" );
            }

        }

        /**
         * Included Required Files
         */
        public function includeFiles() {
            require_once GLOSSYMM_PATH . "/includes/helper-functions.php";
            require_once GLOSSYMM_PATH . "/includes/elementor/elementor.php";
        }

        /**
         * Instantiate Classes
         *
         * @return void
         */
        public function instantiateClasses() { 
            if(is_admin()){
                $this->controllers['assets'] = new GlossyMM\Admin();
            }
            $this->controllers['assets'] = new GlossyMM\Assets();
            $this->controllers['api'] = new GlossyMM\Api();
            $this->controllers['cpt'] = new GlossyMM\Cpt();
            $this->controllers['utils'] = new GlossyMM\Utils();
            $this->controllers['options'] = new GlossyMM\Options();
            $this->controllers['ajax'] = new GlossyMM\Ajax();
        }

        /**
         * Plugin Language file
         * @return void
         */
        public function glossymm_load_textdomain() {
            load_plugin_textdomain( "glossy-mega-menu", false, dirname( __FILE__ ) . "/languages" );
        }

        /**
         * Admin Assets Enquque
         * @param $screen
         * @return void
         */
        public function glossymm_admin_assets( $screen ) {
            if ( "nav-menus.php" === $screen ) { // Only for nav-menus.php page
                //admin style enqueue
                wp_enqueue_style( "glossymm-admin-style", GLOSSYMM_ADMIN_ASSETS . "/css/glossymm-style.css", [], GLOSSYMM_VERSION );
                //admin script enqueue
                wp_enqueue_script( "glossymm-admin-scripts", GLOSSYMM_ADMIN_ASSETS . "/js/admin-scripts.js", ['jquery'], GLOSSYMM_VERSION, true );
                $ajaxurl = admin_url( "admin-ajax.php" );
                $security_nonce = wp_create_nonce( "security_nonce" );
                $enable_option = $this->megamenu_options_infooter();
                $menuitem_edit_popup = $this->glossymm_admin_popup_content();
                $datas = [
                    'ajaxurl'                           => $ajaxurl,
                    "security_nonce"                    => $security_nonce,
                    "glossymm_enabled_options_template" => $enable_option,
                    "menuitem_edit_popup_template"      => $menuitem_edit_popup,
                    "resturl"                           => rest_url( "glossymm/v1/" ),
                    'ajax_loader'                       => admin_url( "images/spinner.gif" ),
                ];
                wp_localize_script( "glossymm-admin-scripts", "obj", $datas );
            }
        }

        public function megamenu_options_infooter() {
            $screen = get_current_screen();
            if ( $screen->base != 'nav-menus' ) {
                return;
            }
            $options = $this->controllers['options'];
            $menu_id = $options->current_menu_id();
            $data = GlossyMM\Utils::get_option( "megamenu_settings" );
            $data = ( isset( $data['menu_location_' . $menu_id] ) ) ? $data['menu_location_' . $menu_id] : [];
            $data['menu_id'] = $menu_id;

            if(is_vertical_menu_enabled()){
                $data['is_enabled'] = 0;
            }
            ob_start();
            glossymm_get_view( "enable-option", $data );
            return ob_get_clean();
        }

        /**
         * Menu Settings Popup
         *
         * @return void
         */
        private function glossymm_admin_popup_content() {
            ob_start();
            glossymm_get_view( "admin-pupup" );
            return ob_get_clean();
        }

    }

}