<?php
/**
 * Events Meta Boxes
 */

class Develer_Events_Meta_Boxes {
    
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post', array(__CLASS__, 'save_meta_boxes'));
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'event_details',
            __('Event Details', 'develer-events'),
            array(__CLASS__, 'render_event_details'),
            'event',
            'normal',
            'high'
        );
    }
    
    public static function render_event_details($post) {
        wp_nonce_field('event_details_nonce', 'event_details_nonce_field');
        
        $start_date = get_post_meta($post->ID, '_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_event_end_date', true);
        $location = get_post_meta($post->ID, '_event_location', true);
        $address = get_post_meta($post->ID, '_event_address', true);
        ?>
        <div class="event-meta-box">
            <p>
                <label for="event_start_date"><strong><?php _e('Start Date & Time:', 'develer-events'); ?></strong></label><br>
                <input type="datetime-local" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($start_date); ?>" style="width: 100%;">
            </p>
            
            <p>
                <label for="event_end_date"><strong><?php _e('End Date & Time:', 'develer-events'); ?></strong></label><br>
                <input type="datetime-local" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($end_date); ?>" style="width: 100%;">
            </p>
            
            <p>
                <label for="event_location"><strong><?php _e('Location Name:', 'develer-events'); ?></strong></label><br>
                <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" style="width: 100%;" placeholder="<?php _e('e.g., Conference Center', 'develer-events'); ?>">
            </p>
            
            <p>
                <label for="event_address"><strong><?php _e('Address:', 'develer-events'); ?></strong></label><br>
                <textarea id="event_address" name="event_address" rows="3" style="width: 100%;" placeholder="<?php _e('Full address for Google Maps', 'develer-events'); ?>"><?php echo esc_textarea($address); ?></textarea>
            </p>
        </div>
        <?php
    }
    
    public static function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['event_details_nonce_field']) || !wp_verify_nonce($_POST['event_details_nonce_field'], 'event_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save start date
        if (isset($_POST['event_start_date'])) {
            update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['event_start_date']));
        }
        
        // Save end date
        if (isset($_POST['event_end_date'])) {
            update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['event_end_date']));
        }
        
        // Save location
        if (isset($_POST['event_location'])) {
            update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
        }
        
        // Save address
        if (isset($_POST['event_address'])) {
            update_post_meta($post_id, '_event_address', sanitize_textarea_field($_POST['event_address']));
        }
    }
}
