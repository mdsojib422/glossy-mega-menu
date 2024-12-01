<?php
/**
 * Footer file in case of the elementor way
 *
 * @package glossy-mega-menu
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<?php do_action( 'glossymm_footer_before' );?>
<?php do_action( 'glossymm_footer' );?>

<?php wp_footer();?>
</body>
</html>