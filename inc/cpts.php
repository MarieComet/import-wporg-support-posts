<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register Custom Post Types and Taxonomies
 */

add_action( 'init', 'wpsp_register_post_tyes' );
function wpsp_register_post_tyes() {

    $labels = array(
		'name'                       => _x( 'Personas', 'Taxonomy General Name', 'import-wporg-support-posts' ),
		'singular_name'              => _x( 'Persona', 'Taxonomy Singular Name', 'import-wporg-support-posts' ),
		'menu_name'                  => __( 'Personas', 'import-wporg-support-posts' ),
		'all_items'                  => __( 'All Items', 'import-wporg-support-posts' ),
		'parent_item'                => __( 'Parent Item', 'import-wporg-support-posts' ),
		'parent_item_colon'          => __( 'Parent Item:', 'import-wporg-support-posts' ),
		'new_item_name'              => __( 'New Item Name', 'import-wporg-support-posts' ),
		'add_new_item'               => __( 'Add New Item', 'import-wporg-support-posts' ),
		'edit_item'                  => __( 'Edit Item', 'import-wporg-support-posts' ),
		'update_item'                => __( 'Update Item', 'import-wporg-support-posts' ),
		'view_item'                  => __( 'View Item', 'import-wporg-support-posts' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'import-wporg-support-posts' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'import-wporg-support-posts' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'import-wporg-support-posts' ),
		'popular_items'              => __( 'Popular Items', 'import-wporg-support-posts' ),
		'search_items'               => __( 'Search Items', 'import-wporg-support-posts' ),
		'not_found'                  => __( 'Not Found', 'import-wporg-support-posts' ),
		'no_terms'                   => __( 'No items', 'import-wporg-support-posts' ),
		'items_list'                 => __( 'Items list', 'import-wporg-support-posts' ),
		'items_list_navigation'      => __( 'Items list navigation', 'import-wporg-support-posts' ),
	);
	$rewrite = array(
		'slug'                       => 'persona',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'helphub_persona', array( 'helphub_article' ), $args );

    $labels = array(
		'name'                       => _x( 'Experiences', 'Taxonomy General Name', 'import-wporg-support-posts' ),
		'singular_name'              => _x( 'Experience', 'Taxonomy Singular Name', 'import-wporg-support-posts' ),
		'menu_name'                  => __( 'Experiences', 'import-wporg-support-posts' ),
		'all_items'                  => __( 'All Items', 'import-wporg-support-posts' ),
		'parent_item'                => __( 'Parent Item', 'import-wporg-support-posts' ),
		'parent_item_colon'          => __( 'Parent Item:', 'import-wporg-support-posts' ),
		'new_item_name'              => __( 'New Item Name', 'import-wporg-support-posts' ),
		'add_new_item'               => __( 'Add New Item', 'import-wporg-support-posts' ),
		'edit_item'                  => __( 'Edit Item', 'import-wporg-support-posts' ),
		'update_item'                => __( 'Update Item', 'import-wporg-support-posts' ),
		'view_item'                  => __( 'View Item', 'import-wporg-support-posts' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'import-wporg-support-posts' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'import-wporg-support-posts' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'import-wporg-support-posts' ),
		'popular_items'              => __( 'Popular Items', 'import-wporg-support-posts' ),
		'search_items'               => __( 'Search Items', 'import-wporg-support-posts' ),
		'not_found'                  => __( 'Not Found', 'import-wporg-support-posts' ),
		'no_terms'                   => __( 'No items', 'import-wporg-support-posts' ),
		'items_list'                 => __( 'Items list', 'import-wporg-support-posts' ),
		'items_list_navigation'      => __( 'Items list navigation', 'import-wporg-support-posts' ),
	);
	$rewrite = array(
		'slug'                       => 'experience',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'helphub_experience', array( 'helphub_article' ), $args );

    $labels = array(
		'name'                  => _x( 'Articles', 'Post Type General Name', 'import-wporg-support-posts' ),
		'singular_name'         => _x( 'Article', 'Post Type Singular Name', 'import-wporg-support-posts' ),
		'menu_name'             => __( 'Articles', 'import-wporg-support-posts' ),
		'name_admin_bar'        => __( 'Article', 'import-wporg-support-posts' ),
		'archives'              => __( 'Item Archives', 'import-wporg-support-posts' ),
		'attributes'            => __( 'Item Attributes', 'import-wporg-support-posts' ),
		'parent_item_colon'     => __( 'Parent Item:', 'import-wporg-support-posts' ),
		'all_items'             => __( 'All Items', 'import-wporg-support-posts' ),
		'add_new_item'          => __( 'Add New Item', 'import-wporg-support-posts' ),
		'add_new'               => __( 'Add New', 'import-wporg-support-posts' ),
		'new_item'              => __( 'New Item', 'import-wporg-support-posts' ),
		'edit_item'             => __( 'Edit Item', 'import-wporg-support-posts' ),
		'update_item'           => __( 'Update Item', 'import-wporg-support-posts' ),
		'view_item'             => __( 'View Item', 'import-wporg-support-posts' ),
		'view_items'            => __( 'View Items', 'import-wporg-support-posts' ),
		'search_items'          => __( 'Search Item', 'import-wporg-support-posts' ),
		'not_found'             => __( 'Not found', 'import-wporg-support-posts' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'import-wporg-support-posts' ),
		'featured_image'        => __( 'Featured Image', 'import-wporg-support-posts' ),
		'set_featured_image'    => __( 'Set featured image', 'import-wporg-support-posts' ),
		'remove_featured_image' => __( 'Remove featured image', 'import-wporg-support-posts' ),
		'use_featured_image'    => __( 'Use as featured image', 'import-wporg-support-posts' ),
		'insert_into_item'      => __( 'Insert into item', 'import-wporg-support-posts' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'import-wporg-support-posts' ),
		'items_list'            => __( 'Items list', 'import-wporg-support-posts' ),
		'items_list_navigation' => __( 'Items list navigation', 'import-wporg-support-posts' ),
		'filter_items_list'     => __( 'Filter items list', 'import-wporg-support-posts' ),
	);
	$args = array(
		'label'                 => __( 'Article', 'import-wporg-support-posts' ),
		'description'           => __( 'Articles', 'import-wporg-support-posts' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'page-attributes' ),
		'taxonomies'            => array( 'category', 'helphub_persona', 'helphub_experience' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-media-document',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'rewrite'               => false,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
		'rest_base'             => 'articles',
	);
	register_post_type( 'helphub_article', $args );
}