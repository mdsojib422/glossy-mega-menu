<?php
namespace GlossyMM;
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use GlossyMM\Utils;
class Glossymm_Menu_Walker extends \Walker_Nav_Menu {

    public $menu_Settings;

    // custom methods
    public function get_item_meta( $menu_item_id ) {
        $data = (array) glossymm_get_save_item_settings($menu_item_id);       
        $default = [
            'menu_id'                         => null,
            'menu_has_child'                  => '',
            'item_is_enabled'                 => 0,
            'menu_icon'                       => '',
            'menu_icon_color'                 => '',
            'menu_badge_text'                 => '',
            'menu_badge_color'                => '',
            'menu_badge_background'           => '',
            'mobile_submenu_content_type'     => 'builder_content',
            'vertical_megamenu_position_type' => 'relative_position',
            'glossymm_fontawesome_class'      => '',
            'glossymm_custom_width'           => '',
            'glossymm_mmwidth'                => 'default_width',
            'megamenu_ajax_load'              => 'no',
        ];
        
        return array_merge( $default, $data );
    }

    public function is_megamenu( $menu_slug ) {
        $menu_obj = wp_get_nav_menu_object( $menu_slug );
        $menu_id = ( ( ( gettype( $menu_obj ) == 'object' ) && ( isset( $menu_obj->term_id ) ) ) ? $menu_obj->term_id : $menu_slug );
        $data = Utils::get_option( "megamenu_settings" );
        $data = ( isset( $data['menu_location_' . $menu_id] ) ) ? $data['menu_location_' . $menu_id] : [];
        return $data['is_enabled'];
    }

    public function is_megamenu_item( $item_meta, $menu ) {
        if ( $this->is_megamenu( $menu ) == 1 && $item_meta['item_is_enabled'] == 1 && class_exists( 'Elementor\Plugin' ) ) {
            return true;
        }
        return false;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = [] ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"glossymm-dropdown glossymm-submenu-panel\">\n";

    }
    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = [] ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }
    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        // New
        $class_names .= ' nav-item';
        $item_meta = $this->get_item_meta( $item->ID );
       
        $is_megamenu_item = $this->is_megamenu_item( $item_meta, $args->menu );

        if ( in_array( 'menu-item-has-children', $classes ) || $is_megamenu_item == true ) {
            $class_names .= ' glossymm-dropdown-has ' . $item_meta['vertical_megamenu_position_type'] . ' glossymm-dropdown-menu-' . $item_meta['glossymm_mmwidth'] . '';
        }

        if ( $is_megamenu_item == true ) {
            $class_names .= ' glossymm-megamenu-has';
        } else {
            $class_names .= " glossymm-menu-item";
        }

        if ( $item_meta['mobile_submenu_content_type'] == 'builder_content' ) {
            $class_names .= ' glossymm-mobile-builder-content';
        }

        if ( in_array( 'current-menu-item', $classes ) ) {
            $class_names .= ' active';
        }

        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';
        if ( isset( $item_meta['glossymm_fontawesome_class'] ) && '' != $item_meta['glossymm_fontawesome_class'] ) {
            $output .= sprintf( "<i class='%s'></i>", $item_meta['glossymm_fontawesome_class'] );
        }
        $atts = [];
        $atts['title'] = !empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = !empty( $item->target ) ? $item->target : '';
        $atts['rel'] = !empty( $item->xfn ) ? $item->xfn : '';
        $atts['href'] = !empty( $item->url ) ? $item->url : '';

        $submenu_indicator = '';

        // New
        if ( $depth === 0 ) {
            $atts['class'] = 'glossymm-menu-nav-link';
        }
        if ( $depth === 0 && in_array( 'menu-item-has-children', $classes ) ) {
            $atts['class'] .= ' glossymm-menu-dropdown-toggle';
        }
        if ( in_array( 'menu-item-has-children', $classes ) || $is_megamenu_item == true ) {
            // Use an if statement to conditionally display the submenu indicator icon
            if ( !empty( $args->submenu_indicator_icon ) ) {
                $submenu_indicator .= $args->submenu_indicator_icon;
            } else {
                $submenu_indicator .= '<i class="icon icon-down-arrow1 glossymm-submenu-indicator"></i>';
            }
        }
        if ( $depth > 0 ) {
            $manual_class = array_values( $classes )[0] . ' ' . 'dropdown-item';
            $atts['class'] = $manual_class;
        }
        if ( in_array( 'current-menu-item', $item->classes ) ) {
            $atts['class'] .= ' active';
        }

        //
        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( !empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $item_output = $args->before;

        $item_output .= '<a' . $attributes . '>';

        if ( $this->is_megamenu( $args->menu ) == 1 ) {
            // add badge text
            if ( $item_meta['menu_badge_text'] != '' ) {
                $badge_style = 'background:' . $item_meta['menu_badge_background'] . '; color:' . $item_meta['menu_badge_color'];
                $badge_carret_style = 'border-top-color:' . $item_meta['menu_badge_background'];
                $item_output .= '<span style="' . $badge_style . '" class="glossymm-menu-badge">' . $item_meta['menu_badge_text'] . '<i style="' . $badge_carret_style . '" class="glossymm-menu-badge-arrow"></i></span>';
            }

            // add menu icon & style
            if ( $item_meta['menu_icon'] != '' ) {
                $icon_style = 'color:' . $item_meta['menu_icon_color'];
                $item_output .= '<i class="glossymm-menu-icon ' . $item_meta['menu_icon'] . '" style="' . $icon_style . '" ></i>';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= $submenu_indicator . '</a>';
        $item_output .= $args->after;
        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = [] ) {
        if ( $depth === 0 ) {
            if ( $this->is_megamenu( $args->menu ) == 1 ) {
                $item_meta = $this->get_item_meta( $item->ID );
                if ( $item_meta['item_is_enabled'] == 1 && class_exists( 'Elementor\Plugin' ) ) {

                    $data_attr = '';
                    $menu_width_class = '';
                    $custom_width = '';
                    switch ( $item_meta['glossymm_mmwidth'] ) {
                    case 'default_width':
                        $menu_width_class = 'default_width';
                        break;

                    case 'full_width':
                        $menu_width_class = 'full_width';
                        break;

                    case 'custom_width':
                        $custom_width = $item_meta['glossymm_custom_width'] === '' ? 'style="width: 750px"' : 'style="width:' . esc_attr( $item_meta['glossymm_custom_width'] ) . 'px;"';
                        break;
                    default:
                        $menu_width_class = 'default_width';
                        break;
                    }
                    $builder_post_title = 'glossymm-content-menuitem' . $item->ID;

                    $builder_post = Utils::get_page_by_title( $builder_post_title, 'glossymm_content' );

                    $output .= sprintf( '<div class="glossymm-megamenu-panel %s" %s >', esc_attr( $menu_width_class ), $custom_width );

                    if ( $builder_post != null ) {
                        $elementor = \Elementor\Plugin::instance();
                        $mega_menu_output = $elementor->frontend->get_builder_content_for_display( $builder_post->ID );

                        // if ajax load is enable and not elementor editor mode
                        if ( !empty( $item_meta['megamenu_ajax_load'] ) && $item_meta['megamenu_ajax_load'] == 'yes' ) {
                            $mega_menu_output = sprintf( '<div class="megamenu-ajax-load" data-id="%1$s"></div>', $builder_post->ID );
                        }
                        $output .= $mega_menu_output;
                    } else {
                        $output .= esc_html__( 'No content found', 'glossy-mega-menu' );
                    }

                    $output .= '</div>';
                }
            }
            $output .= "</li>\n";
        }
    }
}
