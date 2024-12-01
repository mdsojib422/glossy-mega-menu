<?php
/*
Elementor Header Footer
 */

namespace GlossyMM\Glossymm_HF {

    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    class Glossymm_HF {

        /**
         * Current theme template
         *
         * @var String
         */
        public $template;

        /**
         * Instance of Glossymm_HF
         *
         * @var Glossymm_HF
         */
        private static $_instance = null;

        /**
         * Instance of Elemenntor Frontend class.
         *
         * @var \Elementor\Frontend()
         */
        private static $elementor_instance;

        /**
         * Instance of Glossymm_HF
         *
         * @return Glossymm_HF Instance of Glossymm_HF
         */
        public static function instance() {
            if ( !isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            $this->template = get_template();
            $is_elementor_callable = ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) ? true : false;

            if ( $is_elementor_callable ) {
                self::$elementor_instance = \Elementor\Plugin::instance();

            }
            add_action( 'init', [$this, 'init_glossymm_hf'], 9 );
			add_shortcode( 'glossymm_hf_template', [$this, 'glossymm_render_template'] );
        }

        public function init_glossymm_hf() {
            $this->includeFiles();
            $this->glossymm_hf_fallback();

        }

        public function includeFiles() {
            require_once GLOSSYMM_HF_PATH . "/class-glossymm-conditions-fields.php";
            require_once GLOSSYMM_HF_PATH . "/class-cpt.php";
            if ( class_exists( 'GlossyMM\Glossymm_HF\Cpt' ) ) {
                Cpt::instance();
            }

        }

        public function glossymm_hf_fallback() {
            require_once GLOSSYMM_HF_PATH . '/themes/default/class-default-hf-compat.php';

        }

        /**
         * Get option for the plugin settings
         *
         * @param  mixed $setting Option name.
         * @param  mixed $default Default value to be received if the option value is not stored in the option.
         *
         * @return mixed.
         */
        public static function get_settings( $setting = '', $default = '' ) {
            if ( 'header' == $setting || 'footer' == $setting || 'template' == $setting ) {
                $templates = self::get_template_id( $setting );

                return $templates;
                $template = !is_array( $templates ) ? $templates : $templates[0];
                $template = apply_filters( "glossymm_hf_get_settings_{$setting}", $template );
                return $template;
            }
        }

        /**
         * Get header or footer template id based on the meta query.
         *
         * @param  String $type Type of the template header/footer.
         *
         * @return Mixed       Returns the header or footer template id if found, else returns string ''.
         */
        public static function get_template_id( $type ) {
            $option = [
                'location'  => '_glossymm_hf_target_location',
                'exclusion' => '_glossymm_hf_exclusion_target_location',
                'users'     => '_glossymm_hf_target_roles',
            ];

            $glossymm_hf_templates = Glossymm_Conditions_Fields::get_instance()->get_posts_by_conditions( 'glossymm_hf', $option );
            foreach ( $glossymm_hf_templates as $template ) {
                $post_template_type = get_post_meta( absint( $template['id'] ), '_glossymm_hf_template_type', true );
                $enabled = get_post_meta( absint( $template['id'] ), "glossymm_enabled_template", true );
                if ( $post_template_type === $type && !empty( $enabled ) ) {
                    return $template['id'];
                }
            }

            return '';
        }

        /**
         * Prints the Header content.
         */
        public static function get_header_content() {
           echo  wp_kses(\Elementor\Plugin::instance()->frontend->get_builder_content_for_display( glossymm_get_header_id() ),glossymm_allowed_tags());          
        }

        /**
         * Prints the Footer content.
         */
        public static function get_footer_content() {
            echo "<div class='footer-width-fixer'>";
            echo wp_kses(\Elementor\Plugin::instance()->frontend->get_builder_content_for_display( glossymm_get_footer_id() ),glossymm_allowed_tags()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</div>';
        }

        /**
         * Callback to shortcode.
         *
         * @param array $atts attributes for shortcode.
         */

        function glossymm_render_template( $atts ) {
            $atts = shortcode_atts(
                [
                    'id' => '',
                ],
                $atts,
 				'glossymm_hf_template'
            );

            $id = !empty( $atts['id'] ) ? apply_filters( 'glossymm_render_template_id', intval( $atts['id'] ) ) : '';

            if ( empty( $id ) ) {
                return '';
            }

            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $id );
            } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                // Load elementor styles.
                $css_file = new \Elementor\Post_CSS_File( $id );
            }
            $css_file->enqueue();

            return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
        }

    } // End Class

    Glossymm_HF::instance();
}
