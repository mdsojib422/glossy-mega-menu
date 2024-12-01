<?php
namespace GlossyMM;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Cpt {

    public function __construct() {
        add_action( "init", [$this, "post_type"] );
        register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		register_activation_hook( __FILE__, array( $this, 'flush_rewrites' ) ); 
        add_filter( "elementor/frontend/admin_bar/settings", [$this, 'removed_post_title_admin_bar'] );
    }

    public function removed_post_title_admin_bar( $settings ) {
        if ( isset( $settings['elementor_edit_page']['children'] ) ) {
            foreach ( $settings['elementor_edit_page']['children'] as $key => $child ) {
                $post_id = str_replace( 'elementor_edit_doc_', '', $child['id'] );
                $post = get_post( $post_id );
                if ( $post && $post->post_type === 'glossymm_content' ) {
                    unset( $settings['elementor_edit_page']['children'][$key] );
                }
            }
        }
        return $settings;
    }

    public function post_type() {
        $labels = [
            'name'          => _x( 'GlossyMM items', 'Post Type General Name', 'glossy-mega-menu' ),
            'singular_name' => _x( 'GlossyMM item', 'Post Type Singular Name', 'glossy-mega-menu' ),

        ];
        $rewrite = [
            'slug'       => 'glossymm-content',
            'with_front' => true,
            'pages'      => false,
            'feeds'      => false,
        ];
        $args = [
            'label'               => esc_html__( 'GlossyMM item', 'glossy-mega-menu' ),
            'description'         => esc_html__( 'glossymm_content', 'glossy-mega-menu' ),
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'elementor', 'permalink' ],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => false,
            'capability_type'     => 'page',
            'show_in_rest'        => true,
            'rest_base'           => 'glossymm-content',
        ];
        register_post_type( 'glossymm_content', $args );
    }

    public function flush_rewrites() {
        $this->post_type();
        flush_rewrite_rules();
    }
}

new Cpt();
