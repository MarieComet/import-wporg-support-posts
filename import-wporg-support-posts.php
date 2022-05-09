<?php
/**
 * Plugin Name:       Import WordPress.org Support Posts
 * Description:       Import Posts from https://fr.wordpress.org/support
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Marie Comet
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       import-wporg-support-posts
 * Domain Path: /languages/
 */

/*
Uncomment add_action( 'admin_init', 'wpsp_import_terms' ); for import terms
Uncomment add_action( 'admin_init', 'wpsp_import_posts' ); for import posts
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'WPSP_PATH' ) ) {
    define( 'WPSP_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WPSP_URL' ) ) {
    define( 'WPSP_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WPSP_BASE_URL' ) ) {
    define( 'WPSP_BASE_URL', 'https://fr.wordpress.org/support/wp-json/wp/v2/' );
}

add_action( 'plugins_loaded', 'wpsp_include_files' );
function wpsp_include_files() {
    load_plugin_textdomain( 'import-wporg-support-posts', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    require_once( WPSP_PATH . 'inc/cpts.php' );
}

/**
 * Get dist term slug by ID and retrieve local term
 * 
 * @param $taxonomy string Taxonomy key
 * @param $term_id int Term ID
 * 
 * @return int or null
 */
function wpsp_get_dist_term( $taxonomy = '', $term_id = 0 ) {
	if ( empty( $taxonomy ) || empty( $term_id ) ) {
		return;
	}

	$response = wp_remote_get( WPSP_BASE_URL . $taxonomy . '/' . $term_id );
	if ( is_wp_error( $response ) ) {
		return;
	}
	
	$dist_term = json_decode( wp_remote_retrieve_body( $response ) );
	if ( ! empty( $dist_term ) ) {
		$term_exist = get_term_by( 'slug', $dist_term->slug, $dist_term->taxonomy );
		if ( $term_exist ) {
			return $term_exist->term_id;
		}
	}

	return null;
}


/**
 * Update or create a term
 * 
 * @param $term WP_Term object
 */
function wpsp_update_create_term( $term ) {
	if ( ! is_object( $term ) || is_wp_error( $term ) ) {
		return;
	}

	// Base term params
	$term_arr = [
		'slug'			=> $term->slug,
		'description'	=> $term->description,
		'parent'		=> 0
	];

	// If term has parent, we need to get slug from dist site to check if this term exist by slug on our site
	if ( $term->parent > 0 ) {
		$term_exist = wpsp_get_dist_term( $term->taxonomy, $term->parent );
		if ( $term_exist ) {
			$term_arr[ 'parent' ] = $term_exist;
		}
	}

	$term_exist = get_term_by( 'slug', $term->slug, $term->taxonomy );
	if ( $term_exist ) {
		$term_arr[ 'name' ] = $term->name;
		wp_update_term( $term_exist->term_id, $term->taxonomy, $term_arr );
	} else {
		wp_insert_term( $term->name, $term->taxonomy, $term_arr );
	}
}

/**
 * Import terms from dist site
 * 
 */
//add_action( 'admin_init', 'wpsp_import_terms' );
function wpsp_import_terms() {

	if ( ! defined( 'WPSP_BASE_URL' ) ) {
		return;
	}

	// avoid multiple imports at a time
	set_transient( 'wpsp_is_importing_terms', true );
	if ( get_transient( 'wpsp_is_importing_terms' ) ) {
		return;
	}

	$per_page = 10;
	$count_page = 1;
	$total_page = 10; // limit to 10 pages, total of 200 categories

	// Array of child categories
	$child_categories = [];

	while ($count_page <= $total_page) {
		$response = wp_remote_get( WPSP_BASE_URL . 'category/?per_page=' . $per_page . '&page=' . $count_page );
		if ( is_wp_error( $response ) ) {
			return;
		}
	
		$terms = json_decode( wp_remote_retrieve_body( $response ) );
		if ( empty( $terms ) ) { // no more terms
			// end of import, clean transient
			delete_transient( 'wpsp_is_importing_terms' );
			break;
		}
	
		foreach ( $terms as $term ) {
			// Import parent categories first
			if ( $term->parent == 0 ) {
				wpsp_update_create_term( $term );
			} else {
				// Array of child categories used after main loop
				$child_categories[] = $term;
			}
  
		}
		$count_page++;
	}

	// When parent categories are imported, import childrens
	if ( ! empty( $child_categories ) ) {
		foreach( $child_categories as $cat ) {
			wpsp_update_create_term( $cat );
		}
	}
}

/**
 * Get posts via REST API and import them as CPTs
 */
//add_action( 'admin_init', 'wpsp_import_posts' );
function wpsp_import_posts() {

	if ( ! defined( 'WPSP_BASE_URL' ) ) {
		return;
	}

	// avoid multiple imports at a time
	if ( get_transient( 'wpsp_is_importing_posts' ) ) {
		//return;
	}
	set_transient( 'wpsp_is_importing_posts', true );

	$per_page = 20;
	$count_page = 1;
	$total_page = 2; // limit to 40 pages, total of 800 posts

	while ($count_page <= $total_page) {

		$response = wp_remote_get( WPSP_BASE_URL . 'articles/?per_page=' . $per_page . '&page=' . $count_page );
		if ( is_wp_error( $response ) ) {
			return;
		}

		$posts = json_decode( wp_remote_retrieve_body( $response ) );
		if ( empty( $posts ) ) {
			// end of import, clean transient
			delete_transient( 'wpsp_is_importing_posts' );
			break;
		}

		$taxs = [ 'category', 'helphub_persona', 'helphub_experience' ];
		$post_taxs = [
			'category'				=> [],
			'helphub_persona'		=> [],
			'helphub_experience'	=> []
		];

		// for logs
		$imported_posts = [];

		foreach ( $posts as $post ) {

			if ( ! is_object( $post ) ) {
				continue;
			}

			// construct post array
			$post_arr = [ 
				'post_type'		=> $post->type,
				'post_title'	=> $post->title->rendered,
				'post_date'		=> $post->date,
				'post_date_gmt'	=> $post->date_gmt,
				'post_modified' => str_replace( 'T', ' ', $post->modified ),
				'post_modified_gmt' => str_replace( 'T', ' ', $post->modified_gmt ),
				'post_excerpt'	=> $post->excerpt->rendered,
				'post_status'	=> $post->status,
				'post_content'	=> $post->content->rendered,
				'post_name'		=> $post->slug
			];

			// Build array of taxonomy => terms ids for this post
			foreach( $taxs as $tax ) {
				if ( isset( $post->$tax ) && ! empty( $post->$tax ) && is_array( $post->$tax ) ) {
					foreach( $post->$tax as $term ) {
						$term_id = wpsp_get_dist_term( $tax, $term );
						if ( $term_id ) {
							$post_taxs[ $tax ] = $term_id;
						}
					}
				}
			}

			if ( ! empty( $post_taxs ) ) {
				$post_arr[ 'tax_input' ] = $post_taxs;
			}

			// Check if post exist or not
			$post_id = post_exists( $post->title->rendered, $post->content->rendered, $post->date, $post->type, 'publish' );

			if ( $post_id > 0 ) {
				// update post if it was modified
				$existing_post = get_post( $post_id );
				$existing_modified = $existing_post->post_modified;
				$post_modified = str_replace( 'T', ' ', $post->modified );
				if ( $post_modified > $existing_modified ) {
					$post_arr[ 'ID' ] = $post_id;
					wp_update_post( $post_arr, true );
				}
			} else {
				$post_id = wp_insert_post( $post_arr, true );
			}

			$imported_posts[] = $post->title->rendered;
		}

		$count_page++;
	}

	error_log(print_r($imported_posts, true));
}