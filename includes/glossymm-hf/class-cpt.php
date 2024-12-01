<?php
namespace GlossyMM\Glossymm_HF {
    if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    class Cpt {

        /**
         * Instance of Cpt
         *
         * @var Cpt
         */
        private static $_instance = null;

        /**
         * Instance of Cpt
         *
         * @return Cpt Instance of Cpt
         */
        public static function instance() {
            if ( !isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            add_action( "init", [$this, 'custom_post_type'] );           
            register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
            register_activation_hook( __FILE__, [ $this, 'flush_rewrites' ] );

            if ( is_admin() && current_user_can( 'manage_options' ) ) {                
                add_action( "admin_enqueue_scripts", [$this, "glossymm_hf_assets"] );
            }

            add_action( 'add_meta_boxes', [$this, 'glossymm_hf_register_metabox'] );
            add_action( 'save_post_glossymm_hf', [$this, 'glossymm_hf_save_meta'] );

            add_action( 'template_redirect', [$this, 'block_template_frontend'] );
            add_filter( 'single_template', [$this, 'load_canvas_template'] );

            add_filter( 'manage_glossymm_hf_posts_columns', [$this, 'set_shortcode_columns'] );
            add_action( 'manage_glossymm_hf_posts_custom_column', [$this, 'render_shortcode_column'], 10, 2 );
            add_action( 'admin_notices', [$this, 'glossy_display_validation_error_notice'] );
           
        }        

        public function glossymm_hf_assets( $hook ) {
            $screen = get_current_screen();
            if ( ( "glossymm_hf" == $screen->post_type && "post" == $screen->base ) || "edit" == $screen->base && "glossymm_hf" == $screen->post_type ) {
                wp_enqueue_style( "glossymm-hf", GLOSSYMM_ADMIN_ASSETS . '/css/glossymm-hf.css', [], GLOSSYMM_VERSION );
                wp_enqueue_script( "glossymm-hf", GLOSSYMM_ADMIN_ASSETS . '/js/glossymm-hf.js', ['jquery'], GLOSSYMM_VERSION, true );
                wp_localize_script( "glossymm-hf", "obj", ["ajax_url" => admin_url( "admin-ajax.php" )] );
            }
        }

        public function custom_post_type() {

            if ( !current_user_can( 'manage_options' ) ) {
                return;
            }

            $labels = [
                'name'               => esc_html__( 'Header & Footer Builder', 'glossy-mega-menu' ),
                'singular_name'      => esc_html__( 'Header & Footer Builder', 'glossy-mega-menu' ),
                'menu_name'          => esc_html__( 'Header & Footer Builder', 'glossy-mega-menu' ),
                'name_admin_bar'     => esc_html__( 'Header & Footer Builder', 'glossy-mega-menu' ),
                'add_new'            => esc_html__( 'Add New', 'glossy-mega-menu' ),
                'add_new_item'       => esc_html__( 'Add New Header or Footer', 'glossy-mega-menu' ),
                'new_item'           => esc_html__( 'New Template', 'glossy-mega-menu' ),
                'edit_item'          => esc_html__( 'Edit Template', 'glossy-mega-menu' ),
                'view_item'          => esc_html__( 'View Header/Footer', 'glossy-mega-menu' ),
                //'all_items'          => esc_html__( 'All Templates', 'glossy-mega-menu' ),
                'search_items'       => esc_html__( 'Search Templates', 'glossy-mega-menu' ),
                'parent_item_colon'  => esc_html__( 'Parent Templates:', 'glossy-mega-menu' ),
                'not_found'          => esc_html__( 'No Templates found.', 'glossy-mega-menu' ),
                'not_found_in_trash' => esc_html__( 'No Templates found in Trash.', 'glossy-mega-menu' ),
            ];

            $args = [
                'labels'              => $labels,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'themes.php',
                'show_in_nav_menus'   => false,
                'exclude_from_search' => true,
                'capability_type'     => 'post',
                'hierarchical'        => false,
                'menu_icon'           => 'dashicons-editor-kitchensink',
                'supports'            => ['title', 'elementor'],
            ];
            register_post_type( 'glossymm_hf', $args );
        }      

        /**
         * Register meta box(es).
         */
        public function glossymm_hf_register_metabox() {
            add_meta_box(
                'glossymm-hf-meta-box',
                __( 'Header & Footer Builder Options', 'glossy-mega-menu' ),
                [
                    $this,
                    'glossymm_hf_metabox_render',
                ],
                'glossymm_hf',
                'normal',
                'high'
            );
        }

        /**
         * Render Meta field.
         *
         * @param  POST $post Currennt post object which is being displayed.
         */
        public function glossymm_hf_metabox_render( $post ) {
            $values = get_post_custom( $post->ID );
            $template_type = isset( $values['_glossymm_hf_template_type'] ) ? esc_attr( sanitize_text_field( $values['_glossymm_hf_template_type'][0] ) ) : '';
            $target_locations = isset( $values['_glossymm_hf_target_location'] ) ? esc_attr( sanitize_text_field( $values['_glossymm_hf_target_location'][0] ) ) : '';
            $target_roles = isset( $values['_glossymm_hf_target_roles'] ) ? esc_attr( sanitize_text_field( $values['_glossymm_hf_target_roles'][0] ) ) : '';
            $enable_vertical_header = isset( $values['_glossymm_vertical_header'] ) ? esc_attr( sanitize_text_field( $values['_glossymm_vertical_header'][0] ) ) : '';
            // We'll use this nonce field later on when saving.
            wp_nonce_field( 'glossymm_hf_meta_nounce', 'glossymm_hf_meta_nounce' );
            ?>
                <div class="glossymm_hf_post_options">
                    <div class="glossymm-template-select">
                        <?php
                            Glossymm_Conditions_Fields::settings_select_field(
                                'glossymm-template-select',
                                [
                                    'title' => __( 'Type of Template', 'glossy-mega-menu' ),
                                    'type'  => 'template_type',
                                ],
                                $template_type
                            );

                            Glossymm_Conditions_Fields::settings_select_field(
                                'glossymm-target-location-select',
                                [
                                    'title' => __( 'Display On', 'glossy-mega-menu' ),
                                    'type'  => 'target_location',
                                ],
                                $target_locations
                            );

                            Glossymm_Conditions_Fields::settings_select_field(
                                'glossymm-target-user-select',
                                [
                                    'title' => __( 'User Roles', 'glossy-mega-menu' ),
                                    'type'  => 'target_user',
                                ],
                                $target_roles
                            );
                        ?>
                    </div>

                    <?php if(is_vertical_menu_enabled()): ?>
                    <div class="vertical_header_option">
                        <?php 
                        Glossymm_Conditions_Fields::settings_fields(
                            'glossymm-enable-vertical-header',[
                                'title' => __("Enable Vertical Header",'glossy-mega-menu'),
                                'type' => 'checkbox'
                            ],
                            $enable_vertical_header
                        );
                        
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php
        }   

        /**
         * Save Post
         */
        public function glossymm_hf_save_meta( $post_id ) {

            // Bail if we're doing an auto save.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }
            // if our nonce isn't there, or we can't verify it, bail.
            if ( !isset( $_POST['glossymm_hf_meta_nounce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['glossymm_hf_meta_nounce'] ) ), 'glossymm_hf_meta_nounce' ) ) {
                return;
            }
            // if our current user can't edit this post, bail.
            if ( !current_user_can( 'edit_posts' ) ) {
                return;
            }
            if ( isset( $_POST['glossymm-enable-vertical-header'] ) ) {
                update_post_meta( $post_id, '_glossymm_vertical_header', sanitize_text_field( wp_unslash( $_POST['glossymm-enable-vertical-header'] ) ) );
            }else{
                update_post_meta( $post_id, '_glossymm_vertical_header', 0 );
            }       

            $glossymm_template_type = isset( $_POST['glossymm-template-select'] ) ? sanitize_text_field( wp_unslash( $_POST['glossymm-template-select'] ) ) : '';

            $glossymm_location = isset( $_POST['glossymm-target-location-select'] ) ? sanitize_text_field( wp_unslash( $_POST['glossymm-target-location-select'] ) ) : '';

            update_post_meta( $post_id, '_glossymm_hf_template_type', $glossymm_template_type );
            
            $existting_data = glossymm_check_location_exists( $post_id );         

            if ( isset( $existting_data[$glossymm_template_type] ) && in_array( $glossymm_location, $existting_data[$glossymm_template_type] ) && !empty($glossymm_location) ) {               
                $notice_info = sprintf( "<b>The %s already exist in this location, <a href='%s'>%s</a></b>", $glossymm_template_type, esc_url( admin_url( "edit.php?post_type=glossymm_hf" ) ), __( 'View Header/Footer', 'glossy-mega-menu' ) );
                set_transient( 'glossy_post_validation_error', $notice_info, 30 );
                add_filter( 'redirect_post_location', [$this, 'glossy_remove_default_updated_message'], 10, 2 );
                return;
            }

            update_post_meta( $post_id, '_glossymm_hf_target_location', sanitize_text_field( wp_unslash( $_POST['glossymm-target-location-select'] ) ) );
            
            if ( isset( $_POST['glossymm-target-user-select'] ) ) {
                update_post_meta( $post_id, '_glossymm_hf_target_roles', sanitize_text_field( wp_unslash( $_POST['glossymm-target-user-select'] ) ) );
            }
           

        }

        function glossy_remove_default_updated_message( $location, $post_id ) {
            return add_query_arg( 'glossy_error', 'true', remove_query_arg( 'message', $location ) );
        }

        function glossy_display_validation_error_notice() {
            // Check if the error transient exists   
            if ( get_transient( 'glossy_post_validation_error' ) ) {
                $output = '';
                $output .= '<div class="notice notice-error is-dismissible">';
                $output .= '<p>' . get_transient( 'glossy_post_validation_error' ) . '</p>';
                $output .= '</div>';
                echo glossymm_wp_keys( $output );
                // Delete the transient to avoid repeated display
                delete_transient( 'glossy_post_validation_error' );
            }
        }

        /**
         * Set shortcode column for template list.
         *
         * @param array $columns template list columns.
         */
        function set_shortcode_columns( $columns ) {
            $date_column = $columns['date'];
            unset( $columns['date'] );
            $columns['shortcode'] = __( 'Shortcode', 'glossy-mega-menu' );
            $columns['glossymm_hf_display_rules'] = __( 'Display Rules', 'glossy-mega-menu' );
            $columns['glossymm_hf_enabled'] = __( 'Enabled', 'glossy-mega-menu' );
            $columns['date'] = $date_column;
            return $columns;
        }

        /**
         * Display shortcode in template list column.
         *
         * @param array $column template list column.
         * @param int   $post_id post id.
         */
        function render_shortcode_column( $column, $post_id ) {
            switch ( $column ) {
            case 'shortcode':
                ob_start();
                ?>
                    <span class="glossymm-hf-shortcode-col-wrap">
                        <input type="text" onfocus="this.select();" readonly="readonly" value="[glsm_hf_template id='<?php echo esc_attr( $post_id ); ?>']" class="glossymm-hf-large-text code">
                    </span>
				<?php
                ob_get_contents();
                break;
            case 'glossymm_hf_display_rules':
                echo "<h3>Display: <span> " . esc_html( glossymm_display_on_label( $post_id ) ) . "</span></h3>";
                break;
            case 'glossymm_hf_enabled':
                $enabled_template = get_post_meta( $post_id, "glossymm_enabled_template", true );
                $checked = empty( $enabled_template ) ? '' : "checked";
                ob_start()
                ?>
                    <div class="glossymm-toggle-wrap">
                        <label for="glossymm-toggle_<?php echo esc_attr( $post_id ); ?>" class="glossymm-toggle-label">
                            <?php wp_nonce_field( "security_nonce", "_security_nonce" );?>
                            <input type="checkbox" <?php echo esc_attr( $checked ); ?> data-tempid="<?php echo esc_attr( $post_id ); ?>" id="glossymm-toggle_<?php echo esc_attr( $post_id ); ?>" class="glossymm-toggle-input"></input>
                            <div class="glossymm-toggle-switch"></div>
                        </label>
                    </div>
                    <?php
                ob_get_contents();
                break;
            }
        }

        /**
         * Don't display the elementor Elementor Header & Footer Builder templates on the frontend for non edit_posts capable users.
         *
         * @since  1.0.0
         */
        public function block_template_frontend() {
            if ( is_singular( 'glossymm_hf' ) && !current_user_can( 'edit_posts' ) ) {
                wp_redirect( site_url(), 301 );
                die;
            }
        }

        /**
         * Single template function which will choose our template
         *
         * @since  1.0.1
         *
         * @param  String $single_template Single template.
         */
        function load_canvas_template( $single_template ) {
            global $post;

            if ( 'glossymm_hf' == $post->post_type ) {
                $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if ( file_exists( $elementor_2_0_canvas ) ) {
                    return $elementor_2_0_canvas;
                } else {
                    return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
                }
            }

            return $single_template;
        }

        public function flush_rewrites() {
            $this->custom_post_type();
            flush_rewrite_rules();
        }

    } // class end
}