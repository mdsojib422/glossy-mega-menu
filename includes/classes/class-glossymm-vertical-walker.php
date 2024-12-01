<?php 
namespace GlossyMM;

class GlossyMM_Vertical_Walker extends \Walker_Nav_Menu {
    // Start Level
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='sub-menu'>\n";
    }

    // End Level
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }

    // Start Element
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        $menu_item_id = $item->ID;

        $img_url = function_exists("get_field") ? get_field("menu_item_image",$menu_item_id, true) : '';

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $item->classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= $indent . "<div class='menu-item-wrapper'>";

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );
      //  $title = apply_filters( 'nav_menu_item_title', $title, $item, $args );

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'><div class="menu_text" >';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</div><div class="menu_img " style="background-image: url('.$img_url.');"></div></a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    // End Element
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</div>\n";
    }
}
