<?php
namespace GlossyMM;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
use GlossyMM\Utils;

class Ajax {

    public function __construct() {
        add_action( "wp_ajax_glossymm_saving_item_settings", [$this, "func_glossymm_saving_item_settings"] );
        add_action( "wp_ajax_glossymm_get_item_settings", [$this, "func_glossymm_get_item_settings"] );
        add_action( "wp_ajax_glossymm_save_the_menuid", [$this, "func_glossymm_save_the_menuid"] );
        // Saving Enabled Button Action
        add_action( "wp_ajax_glossymm_enabled_template", [$this, "func_glossymm_enabled_template"] );

    }

    /**
     * Ajax action of enabling glossymm template in metabox
     */
    public function func_glossymm_enabled_template() {
        check_ajax_referer( 'security_nonce', 'security' );
        $template_id = isset( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : 0;
        $current_template_type = get_post_meta( $template_id, '_glossymm_hf_template_type', true );
        $current_glossymm_location = get_post_meta( $template_id, '_glossymm_hf_target_location', true );
        if ( empty( $current_template_type ) || empty( $current_glossymm_location ) ) {
            wp_send_json_error( ['msg' => "The $current_template_type location not set yet!"] );
        }
        $enabled = isset( $_POST['enabled'] ) ? boolval( $_POST['enabled'] ) : 0;
        update_post_meta( $template_id, 'glossymm_enabled_template', $enabled );
        wp_send_json_success( $template_id );
    }

    /**
     * Ajax saving menuid of enabled menu 
     *
     * @return void
     */
    public function func_glossymm_save_the_menuid() {
        check_ajax_referer( 'security_nonce', 'security' );      
        $enabled = isset( $_POST['enabled'] ) ? intval( $_POST['enabled'] ) : "";
        $menuId = isset( $_POST['menuId'] ) ? intval( $_POST['menuId'] ) : "";
        if ( empty( $menuId ) ) {
            wp_send_json_error( ['msg' => __("Semething is wrong, please check the menuid", "glossy-mega-menu")] );
        } 
        if(is_vertical_menu_enabled()){
            wp_send_json_error( ['msg' => __("To enable the mega menu, please disable the vertical menu.", "glossy-mega-menu")] );
        }
        $data = Utils::get_option( "megamenu_settings", [] );
        $data['menu_location_' . $menuId] = ['is_enabled' => $enabled];
        Utils::save_option( "megamenu_settings", $data );
        wp_send_json_success( ['msg' => __("Menu Updated","glossy-mega-menu")] );

    }

    /**
     * Saving Menu Item Settings
     */
    public function func_glossymm_saving_item_settings() {
        $res = [];
        check_ajax_referer( 'security_nonce', 'security' );
        $item_id = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : "";
        // Initialize an array to store the sanitized data
        $sanitized_data = [];
        if ( isset( $_POST['formData'] ) && is_array( $_POST['formData'] ) ) {
            $sanitized_data['item_is_enabled'] = isset( $_POST['formData']['item_is_enabled'] ) ? absint( $_POST['formData']['item_is_enabled'] ) : 0;
            $sanitized_data['glossymm_custom_width'] = isset( $_POST['formData']['glossymm_custom_width'] ) ? sanitize_text_field( wp_unslash( $_POST['formData']['glossymm_custom_width'] ) ) : '';
            $sanitized_data['glossymm_mmwidth'] = isset( $_POST['formData']['glossymm_mmwidth'] ) ? sanitize_text_field( wp_unslash( $_POST['formData']['glossymm_mmwidth'] ) ) : '';
            $sanitized_data['glossymm_mmposition'] = isset( $_POST['formData']['glossymm_mmposition'] ) ? sanitize_text_field( wp_unslash( $_POST['formData']['glossymm_mmposition'] ) ) : '';
            $sanitized_data['glossymm_fontawesome_class'] = isset( $_POST['formData']['glossymm_fontawesome_class'] ) ? sanitize_text_field( wp_unslash( $_POST['formData']['glossymm_fontawesome_class'] ) ) : '';
        }
        $res['formdata'] = $sanitized_data;
        $res['item_id'] = $item_id;
        glossymm_save_item_settings($item_id, $sanitized_data);        
        wp_send_json( $res );
    }

    /**
     * Geting popup content of menu item settings
     *
     * @return void
     */
    public function func_glossymm_get_item_settings() {
        $res = [];
        check_ajax_referer( 'security_nonce', 'security' );
        $item_id = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : "";
        $data = glossymm_get_save_item_settings($item_id);
        ob_start();
        echo '<div class="glossymm-tabpanel active" id="glossymm-pupup-content">';
        glossymm_get_view( "popup-content/content", [$data, $item_id] );
        echo '</div>';
        echo '<div class="glossymm-tabpanel" id="glossymm-pupup-icon">';
        glossymm_get_view( "popup-content/icon", $data );
        echo '</div>';
        echo '<div class="glossymm-tabpanel" id="glossymm-pupup-settings">';
        glossymm_get_view( "popup-content/settings", $data );
        echo '</div>';
        $res['item_settings_withhtml'] = ob_get_clean();

        $res['data'] = [$data, $item_id];
        wp_send_json( $res );

    }
    

}
