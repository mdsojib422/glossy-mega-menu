<?php
namespace GlossyMM;
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Vertical_Menu {

    public function __construct() {
        add_action('wp_nav_menu_item_custom_fields', [$this, 'add_image_field_to_menu_items'], 10, 4);
        add_action('wp_update_nav_menu_item', [$this, 'save_menu_item_image'], 10, 3);
    }

    function save_menu_item_image($menu_id, $menu_item_db_id, $args) {
        if (isset($_POST['menu-item-image'][$menu_item_db_id])) {
            $image_url = sanitize_text_field($_POST['menu-item-image'][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, '_menu_item_image', $image_url);
        } else {
            // Remove the meta if the field is empty
            delete_post_meta($menu_item_db_id, '_menu_item_image');
        }
    }

    function add_image_field_to_menu_items($item_id, $item, $depth, $args) {
        // Define target locations
        $target_locations = ['glossymm_vertical_nav_menu'];         
        // Get the menu locations assigned in the theme
        $menu_locations = get_nav_menu_locations();
        // Check if this menu is assigned to one of the target locations
        $is_target_location = false;
        foreach ($target_locations as $location) {
            if (isset($menu_locations[$location])) {
                $is_target_location = true;
                break;
            }
        }        
        // If the menu is in the target location, add the image field
        if ($is_target_location) {
            // Get the current image URL if it exists
            $menu_image = get_post_meta($item_id, '_menu_item_image', true);
            ?>
            <p class="field-custom description description-wide">
                <label for="edit-menu-item-image-<?php echo $item_id; ?>">
                    <?php _e('Menu Item Image', 'textdomain'); ?><br>
                    <input type="text" id="edit-menu-item-image-<?php echo $item_id; ?>" class="widefat code edit-menu-item-image" name="menu-item-image[<?php echo $item_id; ?>]" value="<?php echo esc_attr($menu_image); ?>" placeholder="Image URL" />
                    <button type="button" class="button upload-menu-item-image">Upload Image</button>
                </label>
            </p>
            <?php
        }
    }
  

}