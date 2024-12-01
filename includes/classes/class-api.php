<?php
namespace GlossyMM;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use function GuzzleHttp\json_encode;
use GlossyMM\Utils;
use WP_REST_Response;

class Api {

    public $prefix = '';
    public $param = '/(?P<key>\w+(|[-]\w+))/';
    public $request = null;

    public $settings_prefix = 'options';

    public function __construct() {
        $this->config();
        $this->init();
    }

    public function config() {
        $this->prefix = 'megamenu';
    }

    public function init() {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route( untrailingslashit( 'glossymm/v1/' . $this->prefix ),
                    '/(?P<action>\w+)/' . ltrim( $this->param, '/' ),
                    [
                        'methods'             => \WP_REST_Server::ALLMETHODS,
                        'callback'            => [$this, 'callback'],
                        'permission_callback' => '__return_true',
                    ]
                );
            }
        );

        add_action( "rest_api_init", function () {
            register_rest_route( untrailingslashit( 'glossymm/v2/' . $this->settings_prefix ), '/(?P<action>\w+)/',
                [
                    'methods'             => \WP_REST_Server::ALLMETHODS,
                    'callback'            => [$this, 'option_callback'],
                    'permission_callback' => "__return_true",
                ]
            );

        });
    }

    public function option_callback( $request ) {
        $this->request = $request;
        $action = $request['action'];
        // Call the appropriate method based on the action
        if ( method_exists( $this, 'get_' . $action ) ) {
            $method = 'get_' . $action;
            return $this->$method();
        } else {
            return new \WP_Error( 'no_action', __( 'No such action found', 'glossy-mega-menu' ), ['status' => 404] );
        }

    }

    public function callback( $request ) {
        $this->request = $request;
        $action = $request['action'];
        // Call the appropriate method based on the action
        if ( method_exists( $this, 'get_' . $action ) ) {
            $method = 'get_' . $action;
            return $this->$method();
        } else {
            return new \WP_Error( 'no_action', __( 'No such action found', 'glossy-mega-menu' ), ['status' => 404] );
        }
    }

    public function get_glossymm_save_options() {
        /*   if ( !current_user_can( 'manage_options' ) ) {
        return new \WP_Error( 'permission_denied', __( 'You do not have permission to perform this action','glossy-mega-menu' ), ['status' => 403] );
        } */

        $options = $this->request->get_json_params();
        if ( is_array( $options ) && count( $options ) > 0 ) {
            Utils::save_settings( $options );
            return rest_ensure_response( ['msg' => 'Data saved successfully!'] );
        } else {
            return new WP_REST_Response( json_encode( ['msg' => "Something went wrong!"] ), 400 );
        }
    }

    public function get_get_glossymm_options() {
        $all_settings = Utils::get_all_settings();
        return rest_ensure_response( $all_settings );
    }

    public function get_save_menuitem_settings() {
        if ( !current_user_can( 'manage_options' ) ) {
            return new \WP_Error( 'permission_denied', __( 'You do not have permission to perform this action', 'glossy-mega-menu' ), ['status' => 403] );
        }
        $menu_item_id = $this->request['settings']['menu_id'];
        $menu_item_settings = wp_json_encode( $this->request['settings'], JSON_UNESCAPED_UNICODE );
        //update_post_meta($menu_item_id, Init::$menuitem_settings_key, $menu_item_settings);

        return [
            'saved'   => 1,
            'message' => esc_html__( 'Saved', 'glossy-mega-menu' ),
        ];
    }

    public function get_get_menuitem_settings() {
        if ( !current_user_can( 'manage_options' ) ) {
            return new \WP_Error( 'permission_denied', __( 'You do not have permission to perform this action', 'glossy-mega-menu' ), ['status' => 403] );
        }
        $menu_item_id = $this->request['menu_id'];
        $data = ''; //get_post_meta($menu_item_id, Init::$menuitem_settings_key, true);
        return (array) json_decode( $data );
    }

    public function get_content_editor() {
        $content_key = $this->request['key'];
        $builder_post_title = 'glossymm-content-' . $content_key;
        $builder_post_id = Utils::get_page_by_title( $builder_post_title, 'glossymm_content' );
        if ( is_null( $builder_post_id ) ) {
            $defaults = [
                'post_content' => '',
                'post_title'   => $builder_post_title,
                'post_status'  => 'publish',
                'post_type'    => 'glossymm_content',
            ];
            $builder_post_id = wp_insert_post( $defaults );
            update_post_meta( $builder_post_id, '_wp_page_template', 'elementor_canvas' );
        } else {
            $builder_post_id = $builder_post_id->ID;
        }
        $url = admin_url( 'post.php?post=' . $builder_post_id . '&action=elementor' );

        wp_safe_redirect( $url );
        exit;
    }

    public function get_megamenu_content() {
        $content_key = $this->request['key'];
        $builder_post_title = 'glossymm-content-' . $content_key;
        $builder_post_id = Utils::get_page_by_title( $builder_post_title, 'glossymm_content' );
        if ( !get_post_status( $builder_post_id ) || post_password_required( $builder_post_id ) ) {
            return new \WP_Error( 'invalid_id', __( 'Invalid menu item ID', 'glossy-mega-menu' ), ['status' => 404] );
        }
        $elementor = \Elementor\Plugin::instance();
        $output = $elementor->frontend->get_builder_content_for_display( $builder_post_id );
        return $output;
    }

    public function get_nav_menus() {
        $menus = wp_get_nav_menus();
        return $menus;
    }

    public function get_import_vertical_header() {
        $vertical_navmenu_file = GLOSSYMM_PATH . 'sample-data/vertical-navmenu.json';
        $json_data = json_decode( file_get_contents( $vertical_navmenu_file ) );
        $post_arr = [
            'post_title'   => $json_data->post_title,
            'post_content' => $json_data->post_content,
            'post_status'  => 'publish',
            'post_type'    => 'glossymm_hf',
            'post_author'  => get_current_user_id(),
            'meta_input'   => [
                '_elementor_data'          => $json_data->elementor_data,
                '_elementor_edit_mode'     => "builder",
                '_wp_page_template'        => "default",
                '_elementor_template_type' => "wp-post",

            ],
        ];
        $post_id = wp_insert_post( $post_arr );
        if ( $post_id ) {
            return ['sucess' => 1, 'msg' => "Demo vertical header created successfully!"];
        } else {
            return ['sucess' => 0, 'msg' => "Something went wrong!"];
        }
    }
}

new Api();
