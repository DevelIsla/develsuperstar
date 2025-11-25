<?php
/**
 * Plugin Name: Develer Events
 * Plugin URI: https://develisla.com
 * Description: Comprehensive events management plugin with custom post type, admin UI, and frontend display.
 * Version: 1.0.0
 * Author: Develisla
 * Author URI: https://develisla.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: develer-events
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DEVELER_EVENTS_VERSION', '1.0.0');
define('DEVELER_EVENTS_PATH', plugin_dir_path(__FILE__));
define('DEVELER_EVENTS_URL', plugin_dir_url(__FILE__));

// Include required files
require_once DEVELER_EVENTS_PATH . 'includes/class-events-post-type.php';
require_once DEVELER_EVENTS_PATH . 'includes/class-events-meta-boxes.php';

// Initialize plugin
function develer_events_init() {
    // Register post type and taxonomy
    Develer_Events_Post_Type::init();
    
    // Register meta boxes
    Develer_Events_Meta_Boxes::init();
}
add_action('plugins_loaded', 'develer_events_init');

// Enqueue frontend styles and scripts
function develer_events_enqueue_assets() {
    if (is_post_type_archive('event') || is_singular('event')) {
        wp_enqueue_style(
            'develer-events-css',
            DEVELER_EVENTS_URL . 'assets/css/events.css',
            array(),
            DEVELER_EVENTS_VERSION
        );
        
        wp_enqueue_script(
            'develer-events-js',
            DEVELER_EVENTS_URL . 'assets/js/events.js',
            array('jquery'),
            DEVELER_EVENTS_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('develer-events-js', 'develerEvents', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('develer_events_filter')
        ));
    }
}
add_action('wp_enqueue_scripts', 'develer_events_enqueue_assets');

// Template loader
function develer_events_template_loader($template) {
    if (is_post_type_archive('event')) {
        $plugin_template = DEVELER_EVENTS_PATH . 'templates/archive-event.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    
    if (is_singular('event')) {
        $plugin_template = DEVELER_EVENTS_PATH . 'templates/single-event.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'develer_events_template_loader');

// Activation hook
function develer_events_activate() {
    // Register post type
    Develer_Events_Post_Type::register_post_type();
    Develer_Events_Post_Type::register_taxonomy();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'develer_events_activate');

// Deactivation hook
function develer_events_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'develer_events_deactivate');
