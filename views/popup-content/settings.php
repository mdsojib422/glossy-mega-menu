<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!empty($data) && is_array($data)){ extract($data);} ?>
<div class="glossymm-fieldset mmwidth">
    <label for="#"><?php esc_html_e("Menu Width:", "glossy-mega-menu"); ?></label>
    <?php 
        $glossymm_item_settings_width_options = [
                'default_width' => esc_html__('Default Width',"glossy-mega-menu"),
                'full_width' => esc_html__('Full Width',"glossy-mega-menu"),
                'custom_width' => esc_html__('Custom Width',"glossy-mega-menu"),
        ];
        $glossymm_item_settings_position_options = [
            'default' => esc_html__('Default',"glossy-mega-menu"),
            'relative' => esc_html__('Relative',"glossy-mega-menu"),
        ];
    ?>
    <select name="glossymm-mmwidth" id="glossymm-mmwidth">
        <?php 
            $glossymm_mmwidth = isset($glossymm_mmwidth) ? $glossymm_mmwidth : '';
            foreach($glossymm_item_settings_width_options as $key => $gmm_with_options){
                if($glossymm_mmwidth == $key){
                    printf('<option value="%s" selected>%s</option>',esc_attr($key),esc_html($gmm_with_options));
                }else{
                    printf('<option value="%s">%s</option>',esc_attr($key),esc_html($gmm_with_options));
                }              
            }
        ?>
    </select>
</div>
<?php 
    $custom_width = isset($glossymm_custom_width) ? intval($glossymm_custom_width) : '';
    $custom_width_class = $glossymm_mmwidth == "custom_width" ? 'glossymm-d-block' : 'glossymm-d-none';
?>
<div class="glossymm-fieldset mmcustom_width <?php echo esc_attr($custom_width_class); ?>">
    <label for="glossymm_custom_width"><?php esc_html_e("Custom Width:", "glossy-mega-menu"); ?></label>
    <input type="number" name="glossymm_custom_width" placeholder="<?php echo esc_attr("700"); ?>" value="<?php echo esc_attr($custom_width); ?>" id="glossymm_custom_width"> <span>px;</span>
</div>

<div class="glossymm-fieldset mmposition">
    <label for="#"><?php echo esc_html__("Menu Position:", "glossy-mega-menu"); ?></label>
    <select name="glossymm-mmposition" id="glossymm-mmposition">
    <?php 
            $glossymm_mmposition = isset($glossymm_mmposition) ? $glossymm_mmposition : '';
            foreach($glossymm_item_settings_position_options as $key => $position_options){
                if($glossymm_mmposition == $key){
                    printf('<option value="%s" selected>%s</option>',esc_attr($key),esc_html($position_options));
                }else{
                    printf('<option value="%s">%s</option>',esc_attr($key),esc_html($position_options));
                }              
            }
        ?>     
    </select>
</div>

