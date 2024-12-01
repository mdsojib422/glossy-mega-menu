<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!empty($data)){
    extract($data); 
}
$glossymm_icon_class = isset($glossymm_fontawesome_class) ? $glossymm_fontawesome_class : '';
?>
<div class="glossymm_icon_wrapper">
    <h2><?php esc_html_e( "Icon", "glossy-mega-menu" );?></h2>
    <div class="form-group">  
        <input class="yhs-fontawesome-class" name="yhs-fontawesome-class" value="<?php echo esc_attr($glossymm_icon_class); ?>" type="text" />
        <p><?php esc_html_e("Use font-awesome icon classes","glossy-mega-menu"); ?></p>
    </div>
</div>
