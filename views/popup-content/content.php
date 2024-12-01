<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$settings = $data[0];
$item_is_enabled = isset($settings['item_is_enabled']) ? $settings['item_is_enabled'] : 0;
?>
<div class="glossymm-settings-wrapper">  
    <div class="glossymm-d-flex glossymm-jstcont-between glossymm-alignitem-middle">
        <div class="menu-settings_switch">
            <div class="button-row-container">
                <div class="switch-container switch-ios">            
                    <input type="checkbox" class="glossymm-toggle-btn" <?php checked( $item_is_enabled , '1' ); ?> name="item_is_enabled" id="glossymm_megamenu_item_enabled" value="1" />
                    <label for="glossymm_megamenu_item_enabled"></label>
                </div>
            </div>
            <span><?php echo esc_html__("Enabled Megamenu", "glossy-mega-menu"); ?></span>
        </div>  
        <div class="glossymm-edit-content">
           <div class="glossymm-edit-item">
                <a href="#" id="glossymm-builder-open" class="<?php echo empty($item_is_enabled) ? "disabled" : ""; ?>" data-menuitem="<?php echo isset($data[1]) ? intval($data[1]) : ''; ?>"> <img src="<?php echo esc_url(GLOSSYMM_ADMIN_ASSETS . "/img/elementor-icon.png"); ?>" alt="" srcset=""> 
                    <span><?php echo esc_html__("Edit Content", "glossy-mega-menu"); ?></span>
                </a>
           </div> 
        </div>
    </div>
</div>
