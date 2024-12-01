<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="glossymm_popup_overlaping"></div>
<div class="glossymm_adminmenu_popup">
    <div class="ajax_preloader"><img src="<?php echo esc_url( admin_url( "images/spinner-2x.gif" ) ); ?>" alt="" srcset=""></div>
    <button class="glossymm-close-popup" type="button" data-dismiss="modal">
        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
            <line fill="none" stroke="#000" stroke-width="1.1" x1="1" y1="1" x2="13" y2="13"></line>
            <line fill="none" stroke="#000" stroke-width="1.1" x1="13" y1="1" x2="1" y2="13"></line>
        </svg>
    </button>
    <div class="glossymm_popup_tabs round">
        <ul>
            <li data-tab="glossymm-pupup-settings"><?php esc_html_e( "Settings", 'glossy-mega-menu' )?></li>
            <li data-tab="glossymm-pupup-icon"><?php esc_html_e( "Icon", 'glossy-mega-menu' )?></li>
            <li class="active" data-tab="glossymm-pupup-content"><?php esc_html_e( "Content", 'glossy-mega-menu' )?>
            </li>
        </ul>
    </div>
    <form id="glossymm-item-form" method="post">
        <div class="glossymm-tab-content" id="glossymm-tab-content">
            <!-- PupUP content will be here -->
        </div>
        <div class="glossymm-popup-footer">
            <div class="popup-footer-btn">
                <a class="glossymm-popup-savebtn" type="submit" href="" id="glossymm-save-item">Save</a>
            </div>
        </div>
    </form>
</div>
<div class="glossymm_megamenu_builder_popup">
    <a class="glossymm_close_builder_popup" href="#">
        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
            <line fill="none" stroke="#fff" stroke-width="1.4" x1="1" y1="1" x2="19" y2="19"></line>
            <line fill="none" stroke="#fff" stroke-width="1.4" x1="19" y1="1" x2="1" y2="19"></line>
        </svg>
    </a>
    <iframe id="glossymm_megamenu_builder_iframe" src="" frameborder="0"></iframe>
</div>