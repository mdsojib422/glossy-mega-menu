<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>
<div class="glossymm_enable_option_wrap">
    <h5 class="enabled-groupname"><?php esc_html_e( 'Glossy Mega Menu', 'glossy-mega-menu' );?></h5>
    <div class="menu-settings_switch">
        <div class="button-row-container">
            <div class="switch-container switch-ios">
                <input type="checkbox" data-menuid="<?php echo esc_attr( $data['menu_id'] ); ?>" class="glossymm-toggle-btn" name="is_enabled"
                    <?php checked( ( isset( $data['is_enabled'] ) ? $data['is_enabled'] : '' ), '1' );?>
                    id="glossymm_megamenu_enabled" value="1" />
                <label for="glossymm_megamenu_enabled"></label>
            </div>
            <div class="ajax-loader"><img  src="<?php echo esc_url( admin_url( "images/spinner.gif" ) ); ?>" alt=""></div>
        </div>
    </div>
    <p class="notice notice-warning glossymm-notice"><?php esc_html_e( 'Enable Megamenu for this menu', 'glossy-mega-menu' );?></p>
</div>