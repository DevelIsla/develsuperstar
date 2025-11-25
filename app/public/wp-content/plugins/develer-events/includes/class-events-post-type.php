<?php
/**
 * Events Post Type and Taxonomy Registration
 */

class Develer_Events_Post_Type {
    
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_type'));
        add_action('init', array(__CLASS__, 'register_taxonomy'));
    }
    
    public static function register_post_type() {
        $labels = array(
            'name'                  => _x('Events', 'Post Type General Name', 'develer-events'),
            'singular_name'         => _x('Event', 'Post Type Singular Name', 'develer-events'),
            'menu_name'             => __('Events', 'develer-events'),
            'name_admin_bar'        => __('Event', 'develer-events'),
            'archives'              => __('Event Archives', 'develer-events'),
            'attributes'            => __('Event Attributes', 'develer-events'),
            'parent_item_colon'     => __('Parent Event:', 'develer-events'),
            'all_items'             => __('All Events', 'develer-events'),
            'add_new_item'          => __('Add New Event', 'develer-events'),
            'add_new'               => __('Add New', 'develer-events'),
            'new_item'              => __('New Event', 'develer-events'),
            'edit_item'             => __('Edit Event', 'develer-events'),
            'update_item'           => __('Update Event', 'develer-events'),
            'view_item'             => __('View Event', 'develer-events'),
            'view_items'            => __('View Events', 'develer-events'),
            'search_items'          => __('Search Event', 'develer-events'),
            'not_found'             => __('Not found', 'develer-events'),
            'not_found_in_trash'    => __('Not found in Trash', 'develer-events'),
            'featured_image'        => __('Featured Image', 'develer-events'),
            'set_featured_image'    => __('Set featured image', 'develer-events'),
            'remove_featured_image' => __('Remove featured image', 'develer-events'),
            'use_featured_image'    => __('Use as featured image', 'develer-events'),
        );
        
        $args = array(
            'label'                 => __('Event', 'develer-events'),
            'description'           => __('Events', 'develer-events'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'taxonomies'            => array('event-category'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        
        register_post_type('event', $args);
    }
    
    public static function register_taxonomy() {
        $labels = array(
            'name'                       => _x('Event Categories', 'Taxonomy General Name', 'develer-events'),
            'singular_name'              => _x('Event Category', 'Taxonomy Singular Name', 'develer-events'),
            'menu_name'                  => __('Categories', 'develer-events'),
            'all_items'                  => __('All Categories', 'develer-events'),
            'parent_item'                => __('Parent Category', 'develer-events'),
            'parent_item_colon'          => __('Parent Category:', 'develer-events'),
            'new_item_name'              => __('New Category Name', 'develer-events'),
            'add_new_item'               => __('Add New Category', 'develer-events'),
            'edit_item'                  => __('Edit Category', 'develer-events'),
            'update_item'                => __('Update Category', 'develer-events'),
            'view_item'                  => __('View Category', 'develer-events'),
            'separate_items_with_commas' => __('Separate categories with commas', 'develer-events'),
            'add_or_remove_items'        => __('Add or remove categories', 'develer-events'),
            'choose_from_most_used'      => __('Choose from the most used', 'develer-events'),
            'popular_items'              => __('Popular Categories', 'develer-events'),
            'search_items'               => __('Search Categories', 'develer-events'),
            'not_found'                  => __('Not Found', 'develer-events'),
            'no_terms'                   => __('No categories', 'develer-events'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        
        register_taxonomy('event-category', array('event'), $args);
    }
}
