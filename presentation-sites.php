<?php
/**
 * Plugin Name: Sitios para presentaciones
 * Plugin URI: https://github.com/edgon06/presentation-sites
 * Description: Gestionar venta de puestos en ubicaciones variadas para un grupo dedicado a hacer presentaciones presenciales.
 * Version: 0.1
 * Text Domain: tbare-wordpress-plugin
 * Author: Edwin González
 * Author URI: https://github.com/edgon06
 */


 function prefix_create_custom_post_type() {

$labels = array(
    'name' => 'Ubicaciones',
    'singular_name' => 'Ubicación'
);

$supports = array(
    'title',        // Post title
    'editor',       // Post content
    'excerpt',      // Allows short description
    'author',       // Allows showing and choosing author
    'thumbnail',    // Allows feature images
    'comments',     // Enables comments
    'trackbacks',   // Supports trackbacks
    'revisions',    // Shows autosaved version of the posts
    'custom-fields' // Supports by custom fields
);

    $args = array(
        'labels'              => $labels,
        'description'         => 'Post type post product', // Description
        'supports'            => $supports,
        'taxonomies'          => array( 'category', 'post_tag' ), // Allowed taxonomies
        'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
        'public'              => true,  // Makes the post type public
        'show_ui'             => true,  // Displays an interface for this post type
        'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
        'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
        'show_in_admin_bar'   => true,  // Displays in the black admin bar
        'menu_position'       => 5,     // The position number in the left menu
        'menu_icon'           => true,  // The URL for the icon used for this post type
        'can_export'          => true,  // Allows content export using Tools -> Export
        'has_archive'         => true,  // Enables post type archive (by month, date, or year)
        'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
        'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
        'capability_type'     => 'post' // Allows read, edit, delete like “Post”
    );
    register_post_type( 'ubicacion' , $args );
}
add_action( 'init', 'prefix_create_custom_post_type' );