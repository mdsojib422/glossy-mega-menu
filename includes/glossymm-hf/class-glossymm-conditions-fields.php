<?php
namespace GlossyMM\Glossymm_HF {
    // What are you trying to do?
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    class Glossymm_Conditions_Fields {

        /**
         * Instance
         *
         * @since  1.0.0
         *
         * @var $instance
         */
        private static $instance;

        /**
         * User Selection Option
         *
         * @since  1.0.0
         *
         * @var $user_selection
         */
        private static $user_selection;

        /**
         * Current page type
         *
         * @since  1.0.0
         *
         * @var $current_page_type
         */
        private static $current_page_type = null;

        /**
         * Current page data
         *
         * @since  1.0.0
         *
         * @var $current_page_data
         */
        private static $current_page_data = [];

        /**
         * Location Selection Option
         *
         * @since  1.0.0
         *
         * @var $location_selection
         */
        private static $location_selection;

        /**
         * Initiator
         *
         * @since  1.0.0
         */
        public static function get_instance() {
            if ( !isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function settings_fields($input_name, $settings, $value){           

             printf("<div class='%s glossymm-settings-field'>", 'setting-field-'.esc_attr($input_name));
             $type  = isset( $settings['type'] ) ? sanitize_text_field( $settings['type'] ) : '';
             $title  = isset( $settings['title'] ) ? sanitize_text_field( $settings['title'] ) : '';
             if('checkbox' == $type){
                printf("<label for='%s'>%s</label>",$input_name,esc_html__($title,'glossy-mega-menu'));
                printf('<input type="checkbox" value="1" name="%s" %s id="%s">', esc_attr($input_name),checked($value,1, false),esc_attr($input_name));                
             }  
            echo '</div>';
        }

        public static function settings_select_field( $input_name, $settings, $value ) {
            // Sanitize inputs
            $type  = isset( $settings['type'] ) ? sanitize_text_field( $settings['type'] ) : '';
            $title = isset( $settings['title'] ) ? sanitize_text_field( $settings['title'] ) : '';
        
            $output = '';
            $classes = ( 'target_user' == $type || 'target_location' == $type ) ? 'd-block' : '';
        
            // Escaping class and id attributes
            $output .= '<div class="glossymm-select-field ' . esc_attr( $classes ) . '" id="' . esc_attr( $input_name ) . '">';
            $output .= sprintf( '<label for="%s">%s</label>', esc_attr( $input_name ), esc_html( $title ) );
        
            // Escaping input name attribute
            $output .= '<select name="' . esc_attr( $input_name ) . '" class="template-select form-control">';
            $output .= '<option value="">' . esc_html__( 'Select', 'glossy-mega-menu' ) . '</option>';
        
            if ( "template_type" === $type ) {
                $template_options = [
                    'header'   => __( "Header", "glossy-mega-menu" ),
                    'footer'   => __( "Footer", "glossy-mega-menu" ),
                    'template' => __( "Template", "glossy-mega-menu" ),
                ];        
                foreach ( $template_options as $key => $option ) {
                    // Escape values and labels for each <option>
                    $output .= sprintf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $key ),
                        selected( (string) $value, (string) $key, false ),
                        esc_html( $option )
                    );
                }
        
            } elseif ( "target_location" === $type ) {
                if ( ! isset( self::$location_selection ) || empty( self::$location_selection ) ) {
                    self::$location_selection = self::get_location_selections();
                }
                $selection_options = self::$location_selection;        
                foreach ( $selection_options as $group => $group_data ) {
                    // Escape optgroup label
                    $output .= '<optgroup label="' . esc_attr( $group_data['label'] ) . '">';
                    foreach ( $group_data['value'] as $opt_key => $opt_value ) {
                        $output .= sprintf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $opt_key ),
                            selected( $value, $opt_key, false ),
                            esc_html( $opt_value )
                        );
                    }
                    $output .= '</optgroup>';
                }
        
            } elseif ( "target_user" === $type ) {
                if ( ! isset( self::$user_selection ) || empty( self::$user_selection ) ) {
                    self::$user_selection = self::get_user_selections();
                }
                $selection_options = self::$user_selection;        
                foreach ( $selection_options as $group => $group_data ) {
                    // Escape optgroup label
                    $output .= '<optgroup label="' . esc_attr( $group_data['label'] ) . '">';
                    foreach ( $group_data['value'] as $opt_key => $opt_value ) {
                        $output .= sprintf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $opt_key ),
                            selected( $value, $opt_key, false ),
                            esc_html( $opt_value )
                        );
                    }
                    $output .= '</optgroup>';
                }
            }
        
            $output .= '</select>';
            $output .= '</div>';
            $allowed_html = [
                'div'     => ['class' => [], 'id' => []],
                'label'   => ['for' => []],
                'select'  => ['name' => [], 'class' => []],
                'option'  => ['value' => [], 'selected' => []],
                'optgroup'=> ['label' => []]
            ];
            echo  wp_kses($output,$allowed_html );
        }
        

        /**
         * Get location selection options.
         *
         * @return array
         */
        public static function get_location_selections() {
            $args = [
                'public'   => true,
                '_builtin' => true,
            ];

            $post_types = get_post_types( $args, 'objects' );
            unset( $post_types['attachment'] );

            $args['_builtin'] = false;
            $custom_post_type = get_post_types( $args, 'objects' );

            $post_types = apply_filters( 'glossymm_location_rule_post_types', array_merge( $post_types, $custom_post_type ) );

            $special_pages = [
                'special-404'    => __( '404 Page', 'glossy-mega-menu' ),
                'special-search' => __( 'Search Page', 'glossy-mega-menu' ),
                'special-blog'   => __( 'Blog / Posts Page', 'glossy-mega-menu' ),
                'special-front'  => __( 'Front Page', 'glossy-mega-menu' ),
                'special-date'   => __( 'Date Archive', 'glossy-mega-menu' ),
                'special-author' => __( 'Author Archive', 'glossy-mega-menu' ),
            ];

            if ( class_exists( 'WooCommerce' ) ) {
                $special_pages['special-woo-shop'] = __( 'WooCommerce Shop Page', 'glossy-mega-menu' );
            }

            $selection_options = [
                'basic'         => [
                    'label' => __( 'Basic', 'glossy-mega-menu' ),
                    'value' => [
                        'basic-global'    => __( 'Entire Website', 'glossy-mega-menu' ),
                        'basic-singulars' => __( 'All Singulars', 'glossy-mega-menu' ),
                        'basic-archives'  => __( 'All Archives', 'glossy-mega-menu' ),
                    ],
                ],

                'special-pages' => [
                    'label' => __( 'Special Pages', 'glossy-mega-menu' ),
                    'value' => $special_pages,
                ],
            ];

            $args = [
                'public' => true,
            ];

            $taxonomies = get_taxonomies( $args, 'objects' );

            if ( !empty( $taxonomies ) ) {
                foreach ( $taxonomies as $taxonomy ) {

                    // skip post format taxonomy.
                    if ( 'post_format' == $taxonomy->name ) {
                        continue;
                    }

                    foreach ( $post_types as $post_type ) {
                        $post_opt = self::get_post_target_rule_options( $post_type, $taxonomy );

                        if ( isset( $selection_options[$post_opt['post_key']] ) ) {
                            if ( !empty( $post_opt['value'] ) && is_array( $post_opt['value'] ) ) {
                                foreach ( $post_opt['value'] as $key => $value ) {
                                    if ( !in_array( $value, $selection_options[$post_opt['post_key']]['value'] ) ) {
                                        $selection_options[$post_opt['post_key']]['value'][$key] = $value;
                                    }
                                }
                            }
                        } else {
                            $selection_options[$post_opt['post_key']] = [
                                'label' => $post_opt['label'],
                                'value' => $post_opt['value'],
                            ];
                        }
                    }
                }
            }

            $selection_options['specific-target'] = [
                'label' => __( 'Specific Target', 'glossy-mega-menu' ),
                'value' => [
                    'specifics' => __( 'Specific Pages / Posts / Taxonomies, etc.', 'glossy-mega-menu' ),
                ],
            ];

            /**
             * Filter options displayed in the display conditions select field of Display conditions.
             *
             * @since 1.5.0
             */
            return apply_filters( 'glossymm_display_on_list', $selection_options );
        }

        /**
         * Get target rules for generating the markup for rule selector.
         *
         * @since  1.0.0
         *
         * @param object $post_type post type parameter.
         * @param object $taxonomy taxonomy for creating the target rule markup.
         */
        public static function get_post_target_rule_options( $post_type, $taxonomy ) {
            $post_key = str_replace( ' ', '-', strtolower( $post_type->label ) );
            $post_label = ucwords( $post_type->label );
            $post_name = $post_type->name;
            $post_option = [];

            /* translators: %s post label */
            $all_posts = sprintf( __( 'All %s', 'glossy-mega-menu' ), $post_label );
            $post_option[$post_name . '|all'] = $all_posts;

            if ( 'pages' != $post_key ) {
                /* translators: %s post label */
                $all_archive = sprintf( __( 'All %s Archive', 'glossy-mega-menu' ), $post_label );
                $post_option[$post_name . '|all|archive'] = $all_archive;
            }

            if ( in_array( $post_type->name, $taxonomy->object_type ) ) {
                $tax_label = ucwords( $taxonomy->label );
                $tax_name = $taxonomy->name;

                /* translators: %s taxonomy label */
                $tax_archive = sprintf( __( 'All %s Archive', 'glossy-mega-menu' ), $tax_label );

                $post_option[$post_name . '|all|taxarchive|' . $tax_name] = $tax_archive;
            }

            $post_output['post_key'] = $post_key;
            $post_output['label'] = $post_label;
            $post_output['value'] = $post_option;

            return $post_output;
        }

        /**
         * Get user selection options.
         *
         * @return array
         */
        public static function get_user_selections() {
            // Ensure get_editable_roles() is available
            if ( !function_exists( 'get_editable_roles' ) ) {
                require_once ABSPATH . 'wp-admin/includes/user.php';
            }
            $selection_options = [
                'basic'    => [
                    'label' => __( 'Basic', 'glossy-mega-menu' ),
                    'value' => [
                        'all'        => __( 'All', 'glossy-mega-menu' ),
                        'logged-in'  => __( 'Logged In', 'glossy-mega-menu' ),
                        'logged-out' => __( 'Logged Out', 'glossy-mega-menu' ),
                    ],
                ],

                'advanced' => [
                    'label' => __( 'Advanced', 'glossy-mega-menu' ),
                    'value' => [],
                ],
            ];

            /* User roles */

            $roles = get_editable_roles();

            foreach ( $roles as $slug => $data ) {
                $selection_options['advanced']['value'][$slug] = $data['name'];
            }

            /**
             * Filter options displayed in the user select field of Display conditions.
             *
             * @since 1.5.0
             */
            return apply_filters( 'glossymm_user_roles_list', $selection_options );
        }

        /**
         * Get current page type
         *
         * @since  1.0.0
         *
         * @return string Page Type.
         */
        public function get_current_page_type() {
            if ( null === self::$current_page_type ) {
                $page_type = '';
                $current_id = false;

                if ( is_404() ) {
                    $page_type = 'is_404';
                } elseif ( is_search() ) {
                    $page_type = 'is_search';
                } elseif ( is_archive() ) {
                    $page_type = 'is_archive';

                    if ( is_category() || is_tag() || is_tax() ) {
                        $page_type = 'is_tax';
                    } elseif ( is_date() ) {
                        $page_type = 'is_date';
                    } elseif ( is_author() ) {
                        $page_type = 'is_author';
                    } elseif ( function_exists( 'is_shop' ) && is_shop() ) {
                        $page_type = 'is_woo_shop_page';
                    }
                } elseif ( is_home() ) {
                    $page_type = 'is_home';
                } elseif ( is_front_page() ) {
                    $page_type = 'is_front_page';
                    $current_id = get_the_id();
                } elseif ( is_singular() ) {
                    $page_type = 'is_singular';
                    $current_id = get_the_id();
                } else {
                    $current_id = get_the_id();
                }

                self::$current_page_data['ID'] = $current_id;
                self::$current_page_type = $page_type;
            }

            return self::$current_page_type;
        }

        /**
         * Get posts by conditions
         *
         * @since  1.0.0
         * @param  string $post_type Post Type.
         * @param  array  $option meta option name.
         *
         * @return object  Posts.
         */
        public function get_posts_by_conditions( $post_type, $option ) {
            global $wpdb;
            global $post;

            $post_type = $post_type ? esc_sql( $post_type ) : esc_sql( $post->post_type );

            if ( is_array( self::$current_page_data ) && isset( self::$current_page_data[$post_type] ) ) {
                return apply_filters( 'glossymm_get_display_posts_by_conditions', self::$current_page_data[$post_type], $post_type );
            }

            $current_page_type = $this->get_current_page_type();

            self::$current_page_data[$post_type] = [];

            $option['current_post_id'] = self::$current_page_data['ID'];

            $meta_header = self::get_meta_option_post( $post_type, $option );

            /* Meta option is enabled */
            if ( false === $meta_header ) {
                $current_post_type = esc_sql( get_post_type() );

                $current_post_id = false;
                $q_obj = get_queried_object();

                $current_id = esc_sql( get_the_id() );

                // Find WPML Object ID for current page.
                /*                 if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
                $default_lang = apply_filters( 'wpml_default_language', '' );
                $current_lang = apply_filters( 'wpml_current_language', '' );

                if ( $default_lang !== $current_lang ) {
                $current_post_type = get_post_type( $current_id );
                $current_id = apply_filters( 'wpml_object_id', $current_id, $current_post_type, true, $default_lang );
                }
                } */

                $location = isset($option['location']) ? esc_sql($option['location']) : '';  // Already sanitized with esc_sql
                $post_type = isset($post_type) ? sanitize_key($post_type) : '';  // Sanitize post type
                $current_post_type = isset($current_post_type) ? sanitize_key($current_post_type) : '';  // Sanitize current post type
                
                // Prepare the basic query
                $query = $wpdb->prepare(
                    "SELECT p.ID, pm.meta_value 
                     FROM {$wpdb->postmeta} as pm
                     INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID
                     WHERE pm.meta_key = %s
                     AND p.post_type = %s
                     AND p.post_status = 'publish'", 
                     $location, $post_type
                );
                
                // Prepare the order clause
                $orderby = ' ORDER BY p.post_date DESC';
                
                // Initialize the meta arguments
                $meta_args = "pm.meta_value LIKE %s";
                $meta_query_params = ['%basic-global%'];
                
                // Build meta_args based on page type
                switch ($current_page_type) {
                    case 'is_404':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%special-404%';
                        break;
                    case 'is_search':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%special-search%';
                        break;
                    case 'is_archive':
                    case 'is_tax':
                    case 'is_date':
                    case 'is_author':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%basic-archives%';
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%"' . esc_sql($current_post_type) . '|all|archive"%';
                
                        if ('is_tax' == $current_page_type && (is_category() || is_tag() || is_tax())) {
                            if (is_object($q_obj)) {
                                $meta_args .= " OR pm.meta_value LIKE %s";
                                $meta_query_params[] = '%"' . esc_sql($current_post_type) . '|all|taxarchive|' . esc_sql($q_obj->taxonomy) . '"%';
                                $meta_args .= " OR pm.meta_value LIKE %s";
                                $meta_query_params[] = '%tax-' . esc_sql($q_obj->term_id) . '%';
                            }
                        } elseif ('is_date' == $current_page_type) {
                            $meta_args .= " OR pm.meta_value LIKE %s";
                            $meta_query_params[] = '%special-date%';
                        } elseif ('is_author' == $current_page_type) {
                            $meta_args .= " OR pm.meta_value LIKE %s";
                            $meta_query_params[] = '%special-author%';
                        }
                        break;
                    case 'is_home':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%special-blog%';
                        break;
                    case 'is_front_page':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%special-front%';
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%"' . esc_sql($current_post_type) . '|all"%';
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%post-' . esc_sql($current_id) . '%';
                        break;
                    case 'is_singular':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%basic-singulars%';
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%"' . esc_sql($current_post_type) . '|all"%';
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%post-' . esc_sql($current_id) . '%';
                
                        $taxonomies = get_object_taxonomies($q_obj->post_type);
                        $terms = wp_get_post_terms($q_obj->ID, $taxonomies);
                
                        foreach ($terms as $key => $term) {
                            $meta_args .= " OR pm.meta_value LIKE %s";
                            $meta_query_params[] = '%tax-' . esc_sql($term->term_id) . '-single-' . esc_sql($term->taxonomy) . '%';
                        }
                        break;
                    case 'is_woo_shop_page':
                        $meta_args .= " OR pm.meta_value LIKE %s";
                        $meta_query_params[] = '%special-woo-shop%';
                        break;
                    case '':
                        $current_post_id = $current_id;
                        break;
                }
                
                // Final prepared query with meta arguments
                $final_query = $wpdb->prepare($query . ' AND (' . $meta_args . ')' . $orderby, ...$meta_query_params);
                
                // Execute the query
                $posts = $wpdb->get_results($final_query);
                

                // @codingStandardsIgnoreEnd

                foreach ( $posts as $local_post ) {
                    $meta_value = is_serialized( $local_post->meta_value ) ? unserialize( $local_post->meta_value ) : $local_post->meta_value;
                    self::$current_page_data[$post_type][$local_post->ID] = [
                        'id'       => $local_post->ID,
                        'location' => $meta_value,
                    ];
                }

                $option['current_post_id'] = $current_post_id;
                $this->remove_exclusion_rule_posts( $post_type, $option );
                $this->remove_user_rule_posts( $post_type, $option );
             
            }
            return apply_filters( 'glossymm_get_display_posts_by_conditions', self::$current_page_data[$post_type], $post_type );
        }
        /**
         * Remove exclusion rule posts.
         *
         * @since  1.0.0
         * @param  string $post_type Post Type.
         * @param  array  $option meta option name.
         */
        public function remove_exclusion_rule_posts( $post_type, $option ) {
            $exclusion = isset( $option['exclusion'] ) ? $option['exclusion'] : '';
            $current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;

            foreach ( self::$current_page_data[$post_type] as $c_post_id => $c_data ) {
                $exclusion_rules = get_post_meta( $c_post_id, $exclusion, true );
                $is_exclude = $this->parse_layout_display_condition( $current_post_id, $exclusion_rules );

                if ( $is_exclude ) {
                    unset( self::$current_page_data[$post_type][$c_post_id] );
                }
            }
        }

        /**
         * Remove user rule posts.
         *
         * @since  1.0.0
         * @param  int   $post_type Post Type.
         * @param  array $option meta option name.
         */
        public function remove_user_rule_posts( $post_type, $option ) {
            $users = isset( $option['users'] ) ? $option['users'] : '';
            $current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;
            foreach ( self::$current_page_data[$post_type] as $c_post_id => $c_data ) {
                $user_rules = get_post_meta( $c_post_id, $users, true );
                $is_user = $this->parse_user_role_condition( $current_post_id, $user_rules );             
                if ( !$is_user ) {
                    unset( self::$current_page_data[$post_type][$c_post_id] );
                }
            }
        }

        /**
         * Checks for the display condition for the current page/
         *
         * @param  int   $post_id Current post ID.
         * @param  array $rules   Array of rules Display on | Exclude on.
         *
         * @return boolean      Returns true or false depending on if the $rules match for the current page and the layout is to be displayed.
         */
        public function parse_layout_display_condition( $post_id, $rules ) {
            $display = false;
            $current_post_type = get_post_type( $post_id );

            if ( isset( $rules['rule'] ) && is_array( $rules['rule'] ) && !empty( $rules['rule'] ) ) {
                foreach ( $rules['rule'] as $key => $rule ) {
                    if ( strrpos( $rule, 'all' ) !== false ) {
                        $rule_case = 'all';
                    } else {
                        $rule_case = $rule;
                    }

                    switch ( $rule_case ) {
                    case 'basic-global':
                        $display = true;
                        break;

                    case 'basic-singulars':
                        if ( is_singular() ) {
                            $display = true;
                        }
                        break;

                    case 'basic-archives':
                        if ( is_archive() ) {
                            $display = true;
                        }
                        break;

                    case 'special-404':
                        if ( is_404() ) {
                            $display = true;
                        }
                        break;

                    case 'special-search':
                        if ( is_search() ) {
                            $display = true;
                        }
                        break;

                    case 'special-blog':
                        if ( is_home() ) {
                            $display = true;
                        }
                        break;

                    case 'special-front':
                        if ( is_front_page() ) {
                            $display = true;
                        }
                        break;

                    case 'special-date':
                        if ( is_date() ) {
                            $display = true;
                        }
                        break;

                    case 'special-author':
                        if ( is_author() ) {
                            $display = true;
                        }
                        break;

                    case 'special-woo-shop':
                        if ( function_exists( 'is_shop' ) && is_shop() ) {
                            $display = true;
                        }
                        break;

                    case 'all':
                        $rule_data = explode( '|', $rule );

                        $post_type = isset( $rule_data[0] ) ? $rule_data[0] : false;
                        $archieve_type = isset( $rule_data[2] ) ? $rule_data[2] : false;
                        $taxonomy = isset( $rule_data[3] ) ? $rule_data[3] : false;
                        if ( false === $archieve_type ) {
                            $current_post_type = get_post_type( $post_id );

                            if ( false !== $post_id && $current_post_type == $post_type ) {
                                $display = true;
                            }
                        } else {
                            if ( is_archive() ) {
                                $current_post_type = get_post_type();
                                if ( $current_post_type == $post_type ) {
                                    if ( 'archive' == $archieve_type ) {
                                        $display = true;
                                    } elseif ( 'taxarchive' == $archieve_type ) {
                                        $obj = get_queried_object();
                                        $current_taxonomy = '';
                                        if ( '' !== $obj && null !== $obj ) {
                                            $current_taxonomy = $obj->taxonomy;
                                        }

                                        if ( $current_taxonomy == $taxonomy ) {
                                            $display = true;
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case 'specifics':
                        if ( isset( $rules['specific'] ) && is_array( $rules['specific'] ) ) {
                            foreach ( $rules['specific'] as $specific_page ) {
                                $specific_data = explode( '-', $specific_page );

                                $specific_post_type = isset( $specific_data[0] ) ? $specific_data[0] : false;
                                $specific_post_id = isset( $specific_data[1] ) ? $specific_data[1] : false;
                                if ( 'post' == $specific_post_type ) {
                                    if ( $specific_post_id == $post_id ) {
                                        $display = true;
                                    }
                                } elseif ( isset( $specific_data[2] ) && ( 'single' == $specific_data[2] ) && 'tax' == $specific_post_type ) {
                                    if ( is_singular() ) {
                                        $term_details = get_term( $specific_post_id );

                                        if ( isset( $term_details->taxonomy ) ) {
                                            $has_term = has_term( (int) $specific_post_id, $term_details->taxonomy, $post_id );

                                            if ( $has_term ) {
                                                $display = true;
                                            }
                                        }
                                    }
                                } elseif ( 'tax' == $specific_post_type ) {
                                    $tax_id = get_queried_object_id();
                                    if ( $specific_post_id == $tax_id ) {
                                        $display = true;
                                    }
                                }
                            }
                        }
                        break;

                    default:
                        break;
                    }

                    if ( $display ) {
                        break;
                    }
                }
            }

            return $display;
        }

        /**
         * Parse user role condition.
         *
         * @since  1.0.0
         * @param  int   $post_id Post ID.
         * @param  Array $rules   Current user rules.
         *
         * @return boolean  True = user condition passes. False = User condition does not pass.
         */
        public function parse_user_role_condition( $post_id, $rule ) {
            $show_popup = true;
            if ( !empty( $rule ) ) {
                $show_popup = false;
                switch ( $rule ) {
                case '':
                case 'all':
                    $show_popup = true;
                    break;

                case 'logged-in':
                    if ( is_user_logged_in() ) {
                        $show_popup = true;
                    }
                    break;

                case 'logged-out':
                    if ( !is_user_logged_in() ) {
                        $show_popup = true;
                    }
                    break;

                default:
                    if ( is_user_logged_in() ) {
                        $current_user = wp_get_current_user();

                        if ( isset( $current_user->roles )
                            && is_array( $current_user->roles )
                            && in_array( $rule, $current_user->roles )
                        ) {
                            $show_popup = true;
                        }
                    }
                    break;
                }

            }

            return $show_popup;
        }

        /**
         * Meta option post.
         *
         * @since  1.0.0
         * @param  string $post_type Post Type.
         * @param  array  $option meta option name.
         *
         * @return false | object
         */
        public static function get_meta_option_post( $post_type, $option ) {
            $page_meta = ( isset( $option['page_meta'] ) && '' != $option['page_meta'] ) ? $option['page_meta'] : false;

            if ( false !== $page_meta ) {
                $current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;
                $meta_id = get_post_meta( $current_post_id, $option['page_meta'], true );

                if ( false !== $meta_id && '' != $meta_id ) {
                    self::$current_page_data[$post_type][$meta_id] = [
                        'id'       => $meta_id,
                        'location' => '',
                    ];

                    return self::$current_page_data[$post_type];
                }
            }

            return false;
        }

    } // Class End

    Glossymm_Conditions_Fields::get_instance();
}