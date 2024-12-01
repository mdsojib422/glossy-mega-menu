<?php
/**
 * Glossymm_Default_HF_Support setup
 *
 * @package glossy-mega-menu
 */

namespace GlossyMM\Glossymm_HF;

/**
 * Astra theme compatibility.
 */
class Glossymm_Default_HF_Support {

    /**
     *  Initiator
     */
    public function __construct() {
        add_action( 'wp', [$this, 'hooks'] );
    }

    /**
     * Run all the Actions / Filters.
     */
    public function hooks() {
        if ( glossymm_header_enabled() ) {
            // Replace header.php template.
            add_action( 'get_header', [$this, 'override_header'] );
            // Display header in the replaced header.
            add_action( 'glossymm_header', 'glossymm_render_header' );
        }        
        if ( glossymm_footer_enabled() ) {
            // Display HFE's footer in the replaced header.
            add_action( 'get_footer', [$this, 'override_footer'] );
            add_action( 'glossymm_footer', 'glossymm_render_footer' );
        }
    }

    /**
     * Function for overriding the header in the elmentor way.
     *
     * @since 1.2.0
     *
     * @return void
     */
    public function override_header() {
        require GLOSSYMM_HF_PATH . '/themes/default/glossymm-header.php';
        $templates = [];
        $templates[] = 'header.php';
        // Avoid running wp_head hooks again.
        remove_all_actions( 'wp_head' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }

    /**
     * Function for overriding the footer in the elmentor way.
     *
     * @since 1.2.0
     *
     * @return void
     */
    public function override_footer() {
        require GLOSSYMM_HF_PATH . '/themes/default/glossymm-footer.php';
        $templates = [];
        $templates[] = 'footer.php';
        // Avoid running wp_footer hooks again.
        remove_all_actions( 'wp_footer' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }

}

new Glossymm_Default_HF_Support();
