<?php
/*
 * Functions For Glossymm
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use GlossyMM\Glossymm_HF\Glossymm_Conditions_Fields;
use GlossyMM\Glossymm_HF\Glossymm_HF;
use GlossyMM\Utils;

function glossymm_get_view( $path, $data = null ) {
    include GLOSSYMM_PATH . 'views/' . $path . ".php";
}

/**
 * Checks if Header is enabled from HFE.
 *
 * @since  1.0.2
 * @return bool True if header is enabled. False if header is not enabled
 */
function glossymm_header_enabled() {
    $header_id = Glossymm_HF::get_settings( 'header', '' );
    $status = false;
    if ( '' !== $header_id ) {
        $status = true;
    }
    return apply_filters( 'glossymm_header_enabled', $status );
}
/**
 * Checks if Header is enabled from HFE.
 *
 * @since  1.0.2
 * @return bool True if header is enabled. False if header is not enabled
 */
function glossymm_footer_enabled() {
    $footer_id = Glossymm_HF::get_settings( 'footer', '' );

    return $footer_id;
    $status = false;
    if ( '' !== $footer_id ) {
        $status = true;
    }
    return apply_filters( 'glossymm_footer_enabled', $status );
}

/**
 * Display header markup.
 *
 * @since  1.0.2
 */
function glossymm_render_header() {
    if ( false == apply_filters( 'glossymm_enable_render_header', true ) ) {
        return;
    }

    $classes = is_vertical_menu_enabled() ? 'glossymm-vertical-header' : '';
    ?>
    <header id="masthead" class="glossymm-hf <?php echo esc_attr( $classes ); ?>">
        <?php Glossymm_HF::get_header_content();?>
    </header>

    <?php

    if ( is_vertical_menu_enabled() ):
    ?>
    <div id="glossymm-megamenu-vertical" class="glossymm-vertical-menu-wrapper">
        <?php if ( !empty( glossymm_get_vertical_menu_id() ) ): ?>
        <ul id="menu-vertical-header" class="glossymm-vertical-navbar-nav">
        <?php
$menu_items = wp_get_nav_menu_items( glossymm_get_vertical_menu_id() );
    if ( is_array( $menu_items ) ) {
        foreach ( $menu_items as $item ) {
            ?>
                    <div class="menu-item-wrapper">
                        <a href="<?php echo esc_url( $item->url ); ?>" class="menu-link">
                            <div class="menu_text"><?php echo esc_html( $item->title ); ?></div>
                            <div class="menu_img " style="background-image: url();"></div>
                        </a>
                    </div>
                    <?php
}
    }
    ?>
        </ul>
        <?php
else:
        echo '<div class="warning-menu-notselected"><p>Please select a vertical menu from option settings</p></div>';
    endif;
    ?>
    </div>
<?php
endif;
}

/**
 * Display footer markup.
 *
 * @since  1.0.2
 */
function glossymm_render_footer() {

    if ( false == apply_filters( 'glossymm_enable_render_footer', true ) ) {
        return;
    }
    ?>
<footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" role="contentinfo">
    <?php Glossymm_HF::get_footer_content();?>
</footer>
<?php

}

/**
 * Get Glossymm Header ID
 *
 * @since  1.0.2
 * @return (String|boolean) header id if it is set else returns false.
 */
function glossymm_get_header_id() {
    $header_id = Glossymm_HF::get_settings( 'header', '' );
    if ( '' === $header_id ) {
        $header_id = false;
    }
    return apply_filters( 'glossymm_get_header_id', $header_id );
}

/**
 * Get Glossymm Footer ID
 *
 * @since  1.0.2
 * @return (String|boolean) header id if it is set else returns false.
 */
function glossymm_get_footer_id() {
    $footer_id = Glossymm_HF::get_settings( 'footer', '' );
    if ( '' === $footer_id ) {
        $footer_id = false;
    }
    return apply_filters( 'glossymm_get_footer_id', $footer_id );
}

/**
 * Display On Label
 */
function glossymm_display_on_label( $post_id ) {
    $target_location = get_post_meta( $post_id, "_glossymm_hf_target_location", true );
    if ( !empty( $target_location ) ) {
        $target_locations = Glossymm_Conditions_Fields::get_location_selections();
        return glossymm_get_value_bykey( $target_locations, $target_location );
    } else {
        return __( "Everywhere", "glossy-mega-menu" );
    }
}

/* Get value of multidimension array by key */
function glossymm_get_value_bykey( $array, $key ) {
    foreach ( $array as $k => $v ) {
        if ( $k === $key ) {
            return $v;
        } elseif ( is_array( $v ) ) {
            $result = glossymm_get_value_bykey( $v, $key );
            if ( $result !== null ) {
                return $result;
            }
        }
    }
    return null;
}

function glossymm_get_template_by_type() {
    $option = [
        'location'  => '_glossymm_hf_target_location',
        'exclusion' => '_glossymm_hf_exclusion_target_location',
        'users'     => '_glossymm_hf_target_roles',
    ];
    // Glossymm_Conditions_Fields::get_instance()->get_posts_by_conditions( 'glossymm_hf', $option );

    return Glossymm_HF::get_template_id( 'header' );
}

function glossymm_check_location_exists( $post_id ) {

    
    $query = new WP_Query(
        [
            'post_type'    => 'glossymm_hf',
            'post_status'  => ['publish','draft'],
            'post__not_in' => [$post_id],
        ]
    );
   
    $exist_tmpt_type_location = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ): $query->the_post();
            $exist_type = get_post_meta( get_the_ID(), "_glossymm_hf_template_type", true );
            $exist_location = get_post_meta( get_the_ID(), "_glossymm_hf_target_location", true );
            // Check if the type already exists in the array
            if ( !empty( $exist_location ) && !isset( $exist_tmpt_type_location[$exist_type] ) ) {
                $exist_tmpt_type_location[$exist_type] = [];
            }
            // Push the location value into the array for the specific type
            $exist_tmpt_type_location[$exist_type][] = $exist_location;
        endwhile;
    }
    // Reset post data
    wp_reset_postdata();
    return $exist_tmpt_type_location;
}

function glossymm_wp_keys( $content ) {

    $allowed_html = [
        'a'        => [
            'href'  => [],
            'title' => [],
        ],
        'b'        => [],
        'p'        => [],
        'em'       => [],
        'div'      => [
            'class' => [],
            'id'    => [],
        ],
        'select'   => [
            'class' => [],
            'name'  => [],
        ],
        'optgroup' => [
            'label' => [],
        ],
        'option'   => [
            'value' => [],
        ],
        'label'    => [
            'for' => [],
        ],
        'strong'   => [],
    ];
    return wp_kses( $content, $allowed_html );
}

/* Allowed Tags For Post With SVG Support */

function glossymm_allowed_tags() {
    // Define allowed HTML tags
    $allowed_tags = array_merge(
        wp_kses_allowed_html( 'post' ), // Existing allowed tags for 'post'
        [
            'style' => ['type' => []],
            'i'     => ['class' => []],
            'svg'   => [
                'class'       => [],
                'xmlns'       => [],
                'fill'        => [],
                'viewbox'     => [],
                'role'        => [],
                'aria-hidden' => [],
                'focusable'   => [],
                'height'      => [],
                'width'       => [],
            ],
            'path'  => [
                'd'    => [],
                'fill' => [],
            ],
        ]
    );
    return $allowed_tags;
}
/* End Allowed Tags For Post With SVG Support */

/* Saving Items Settings */
function glossymm_save_item_settings( $item_id, $settings ) {
    $existing_settings = Utils::get_option( "megamenu_settings", [] );
    $existing_settings["item_settings_$item_id"] = $settings;
    Utils::save_option( "megamenu_settings", $existing_settings );
}
/* Fetching Saved item settings */
function glossymm_get_save_item_settings( $item_id ) {
    $existing_settings = Utils::get_option( "megamenu_settings", [] );
    return $existing_settings["item_settings_$item_id"] ?? '';
}
/* Is vertical menu enabled  */
function is_vertical_menu_enabled() {
    $is_vertical_enabled = Utils::get_settings( "vertical_navmenu", false );
    return $is_vertical_enabled ? true : false;
}

function glossymm_get_vertical_menu_id() {
    return (int) Utils::get_settings( "vertical_menu_select" );
}
/* End Is vertical menu enabled  */