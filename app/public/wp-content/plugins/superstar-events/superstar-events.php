<?php
/**
 * Plugin Name: Superstar Events
 * Description: A comprehensive events management plugin with calendar, list view, and map integration.
 * Version: 1.0.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define( 'SUPERSTAR_EVENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SUPERSTAR_EVENTS_URL', plugin_dir_url( __FILE__ ) );

// Include Files
require_once SUPERSTAR_EVENTS_PATH . 'includes/admin.php';
require_once SUPERSTAR_EVENTS_PATH . 'includes/frontend.php';

/**
 * Register 'Event' Custom Post Type
 */
function superstar_register_event_cpt() {
	$labels = array(
		'name'               => 'Events',
		'singular_name'      => 'Event',
		'menu_name'          => 'Events',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Event',
		'edit_item'          => 'Edit Event',
		'new_item'           => 'New Event',
		'view_item'          => 'View Event',
		'search_items'       => 'Search Events',
		'not_found'          => 'No events found',
		'not_found_in_trash' => 'No events found in Trash',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => true,
		'menu_icon'           => 'dashicons-calendar-alt',
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'events' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'event', $args );
}
add_action( 'init', 'superstar_register_event_cpt' );

/**
 * Register 'Event Category' Taxonomy
 */
function superstar_register_event_taxonomy() {
	$labels = array(
		'name'              => 'Event Categories',
		'singular_name'     => 'Event Category',
		'search_items'      => 'Search Categories',
		'all_items'         => 'All Categories',
		'parent_item'       => 'Parent Category',
		'parent_item_colon' => 'Parent Category:',
		'edit_item'         => 'Edit Category',
		'update_item'       => 'Update Category',
		'add_new_item'      => 'Add New Category',
		'new_item_name'     => 'New Category Name',
		'menu_name'         => 'Categories',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'event-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'event_category', array( 'event' ), $args );
}
add_action( 'init', 'superstar_register_event_taxonomy' );

/**
 * Enqueue Scripts and Styles
 */
function superstar_events_scripts() {
	wp_enqueue_style( 'superstar-events-style', SUPERSTAR_EVENTS_URL . 'assets/css/style.css', array(), '1.0.0' );
	
	// Leaflet CSS
	wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );

	wp_enqueue_script( 'superstar-events-script', SUPERSTAR_EVENTS_URL . 'assets/js/script.js', array( 'jquery' ), '1.0.0', true );
	
	// Leaflet JS
	wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );

	wp_localize_script( 'superstar-events-script', 'superstar_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'superstar_events_nonce' )
	));
}
add_action( 'wp_enqueue_scripts', 'superstar_events_scripts' );

/**
 * Template Loader
 */
function superstar_events_template_loader( $template ) {
	if ( is_singular( 'event' ) ) {
		$plugin_template = SUPERSTAR_EVENTS_PATH . 'templates/single-event.php';
		if ( file_exists( $plugin_template ) ) {
			return $plugin_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'superstar_events_template_loader' );

/**
 * Flush Rewrite Rules on Activation
 */
function superstar_events_activate() {
	superstar_register_event_cpt();
	superstar_register_event_taxonomy();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'superstar_events_activate' );

