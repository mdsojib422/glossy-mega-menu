<?php
/**
 * Plugin Name:       Glossy Mega Menu
 * Description:       Glossy Mega Menu is a WordPress plugin that allows you to create stunning mega menus using Elementor. It includes a   built-in header and footer builder, giving you complete design flexibility for your website's navigation.
 * Version:           1.2.3
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Glossy It
 * Author URI:        https://www.glossyit.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       glossy-mega-menu
 * Domain Path:       /languages
 * Requires Plugins:  elementor
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !function_exists( 'glossymm_auto_loader' ) ) {
    function glossymm_auto_loader( $class_name ) {
        // Not loading a class from our plugin.
        if ( !is_int( strpos( $class_name, 'GlossyMM' ) ) ) {
            return;
        }
        // Remove root namespace as we don't have that as a folder.
        $class_name = str_replace( 'GlossyMM\\', '', $class_name );
        $class_name = str_replace( '\\', '/', strtolower( $class_name ) ) . '.php';
        // Get only the file name.
        $pos = strrpos( $class_name, '/' );
        $file_name = is_int( $pos ) ? substr( $class_name, $pos + 1 ) : $class_name;
        // Get only the path.
        $path = str_replace( $file_name, '', $class_name );
        $new_file_name = 'class-' . str_replace( '_', '-', $file_name );
        $new_file_name = 'includes\\classes\\' . $new_file_name;
        // Construct file path.
        $file_path = plugin_dir_path( __FILE__ ) . str_replace( '\\', DIRECTORY_SEPARATOR, $path . strtolower( $new_file_name ) );
        if ( file_exists( $file_path ) ) {
            require_once $file_path;
        }
    }
    spl_autoload_register( 'glossymm_auto_loader' );
}

if ( !function_exists( 'glossymm_init' ) ) {
    function glossymm_init() {
        // globals
        global $glossymm;
        // initialize
        if ( !isset( $glossymm ) ) {
            require_once "init-glossymm.php";
            $glossymm = new \GlossyMM();

        }
        return $glossymm;

    }
}

// initialize
glossymm_init();
