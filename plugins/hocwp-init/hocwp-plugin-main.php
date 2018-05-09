<?php
/*
Plugin Name: Init by HocWP Team
Plugin URI: http://hocwp.net/
Description: This plugin is created by HocWP Team.
Author: HocWP Team
Version: 2.0.5
Author URI: http://facebook.com/hocwpnet/
Text Domain: hocwp-init
Domain Path: /languages/
*/
define( 'HOCWP_INIT_FILE', __FILE__ );

define( 'HOCWP_INIT_PATH', untrailingslashit( plugin_dir_path( HOCWP_INIT_FILE ) ) );

define( 'HOCWP_INIT_URL', plugins_url( '', HOCWP_INIT_FILE ) );

define( 'HOCWP_INIT_INC_PATH', HOCWP_INIT_PATH . '/inc' );

define( 'HOCWP_INIT_BASENAME', plugin_basename( HOCWP_INIT_FILE ) );

define( 'HOCWP_INIT_DIRNAME', dirname( HOCWP_INIT_BASENAME ) );

function hocwp_init_load_textdomain() {
	load_plugin_textdomain( 'hocwp-init', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'hocwp_init_load_textdomain' );

function hocwp_register_post_type_normal( $args ) {
	$defaults = array(
		'supports'          => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'comments',
			'revisions'
		),
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true
	);

	$args = wp_parse_args( $args, $defaults );
	hocwp_register_post_type( $args );
}

function hocwp_sanitize_id( $id ) {
	if ( is_array( $id ) ) {
		$id = implode( '@', $id );
	}

	$id = strtolower( $id );
	$id = str_replace( '][', '_', $id );

	$chars = array(
		'-',
		' ',
		'[',
		']',
		'@',
		'.'
	);

	$id = str_replace( $chars, '_', $id );
	$id = trim( $id, '_' );

	return $id;
}

function hocwp_register_post_type( $args = array() ) {
	$args          = apply_filters( 'hocwp_post_type_args', $args );
	$name          = isset( $args['name'] ) ? $args['name'] : '';
	$singular_name = isset( $args['singular_name'] ) ? $args['singular_name'] : '';
	$menu_name     = isset( $args['menu_name'] ) ? $args['menu_name'] : '';

	if ( empty( $menu_name ) ) {
		$menu_name = $name;
	}

	$supports            = isset( $args['supports'] ) ? $args['supports'] : array();
	$hierarchical        = isset( $args['hierarchical'] ) ? $args['hierarchical'] : false;
	$public              = isset( $args['public'] ) ? $args['public'] : true;
	$show_ui             = isset( $args['show_ui'] ) ? $args['show_ui'] : true;
	$show_in_menu        = isset( $args['show_in_menu'] ) ? $args['show_in_menu'] : true;
	$show_in_nav_menus   = isset( $args['show_in_nav_menus'] ) ? $args['show_in_nav_menus'] : false;
	$show_in_admin_bar   = isset( $args['show_in_admin_bar'] ) ? $args['show_in_admin_bar'] : false;
	$menu_position       = isset( $args['menu_position'] ) ? $args['menu_position'] : 6;
	$can_export          = isset( $args['can_export'] ) ? $args['can_export'] : true;
	$has_archive         = isset( $args['has_archive'] ) ? $args['has_archive'] : true;
	$exclude_from_search = isset( $args['exclude_from_search'] ) ? $args['exclude_from_search'] : false;
	$publicly_queryable  = isset( $args['publicly_queryable'] ) ? $args['publicly_queryable'] : true;
	$capability_type     = isset( $args['capability_type'] ) ? $args['capability_type'] : 'post';
	$taxonomies          = isset( $args['taxonomies'] ) ? $args['taxonomies'] : array();
	$menu_icon           = isset( $args['menu_icon'] ) ? $args['menu_icon'] : 'dashicons-admin-post';
	$slug                = isset( $args['slug'] ) ? $args['slug'] : '';
	$with_front          = isset( $args['with_front'] ) ? $args['with_front'] : true;
	$pages               = isset( $args['pages'] ) ? $args['pages'] : true;
	$feeds               = isset( $args['feeds'] ) ? $args['feeds'] : true;
	$query_var           = isset( $args['query_var'] ) ? $args['query_var'] : '';
	$capabilities        = isset( $args['capabilities'] ) ? $args['capabilities'] : array();
	$custom_labels       = isset( $args['labels'] ) ? $args['labels'] : '';

	if ( ! is_array( $custom_labels ) ) {
		$custom_labels = array();
	}

	$show_in_rest = isset( $args['show_in_rest'] ) ? $args['show_in_rest'] : true;
	$show_in_rest = boolval( $show_in_rest );

	if ( empty( $singular_name ) ) {
		$singular_name = $name;
	}

	if ( empty( $name ) || ! is_array( $supports ) || empty( $slug ) || post_type_exists( $slug ) ) {
		return;
	}

	if ( ! in_array( 'title', $supports ) ) {
		array_push( $supports, 'title' );
	}

	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : $slug;
	$post_type = hocwp_sanitize_id( $post_type );

	if ( post_type_exists( $post_type ) ) {
		return;
	}

	$labels = array(
		'name'               => $name,
		'singular_name'      => $singular_name,
		'menu_name'          => $menu_name,
		'name_admin_bar'     => isset( $args['name_admin_bar'] ) ? $args['name_admin_bar'] : $singular_name,
		'all_items'          => sprintf( __( 'All %s', 'hocwp-init' ), $name ),
		'add_new'            => __( 'Add New', 'hocwp-init' ),
		'add_new_item'       => sprintf( __( 'Add New %s', 'hocwp-init' ), $singular_name ),
		'edit_item'          => sprintf( __( 'Edit %s', 'hocwp-init' ), $singular_name ),
		'new_item'           => sprintf( __( 'New %s', 'hocwp-init' ), $singular_name ),
		'view_item'          => sprintf( __( 'View %s', 'hocwp-init' ), $singular_name ),
		'search_items'       => sprintf( __( 'Search %s', 'hocwp-init' ), $singular_name ),
		'not_found'          => __( 'Not found', 'hocwp-init' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'hocwp-init' ),
		'parent_item_colon'  => sprintf( __( 'Parent %s:', 'hocwp-init' ), $singular_name ),
		'parent_item'        => sprintf( __( 'Parent %s', 'hocwp-init' ), $singular_name ),
		'update_item'        => sprintf( __( 'Update %s', 'hocwp-init' ), $singular_name )
	);

	$labels = wp_parse_args( $custom_labels, $labels );

	$rewrite_slug = str_replace( '_', '-', $slug );
	$rewrite_slug = apply_filters( 'hocwp_post_type_slug', $rewrite_slug, $post_type );
	$rewrite_slug = apply_filters( 'hocwp_post_type_' . $post_type . '_slug', $rewrite_slug, $args );

	$rewrite_defaults = array(
		'slug'       => $rewrite_slug,
		'with_front' => $with_front,
		'pages'      => $pages,
		'feeds'      => $feeds
	);

	$rewrite = isset( $args['rewrite'] ) ? $args['rewrite'] : array();
	$rewrite = wp_parse_args( $rewrite, $rewrite_defaults );

	if ( ! $public ) {
		$rewrite   = false;
		$query_var = false;
	}

	$description = isset( $args['description'] ) ? $args['description'] : '';

	$args = array(
		'labels'              => $labels,
		'description'         => $description,
		'supports'            => $supports,
		'taxonomies'          => $taxonomies,
		'hierarchical'        => $hierarchical,
		'public'              => $public,
		'show_ui'             => $show_ui,
		'show_in_menu'        => $show_in_menu,
		'show_in_nav_menus'   => $show_in_nav_menus,
		'show_in_admin_bar'   => $show_in_admin_bar,
		'menu_position'       => $menu_position,
		'menu_icon'           => $menu_icon,
		'can_export'          => $can_export,
		'has_archive'         => $has_archive,
		'exclude_from_search' => $exclude_from_search,
		'publicly_queryable'  => $publicly_queryable,
		'query_var'           => $query_var,
		'rewrite'             => $rewrite,
		'capability_type'     => $capability_type
	);

	if ( $show_in_rest ) {
		$rest_base = $rewrite_slug;
		if ( 'api' != $rest_base ) {
			$rest_base .= '-api';
		}
		$args['show_in_rest']          = true;
		$args['rest_base']             = $rest_base;
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	if ( count( $capabilities ) > 0 ) {
		$args['capabilities'] = $capabilities;
	}

	register_post_type( $post_type, $args );
}

function hocwp_array_has_value( $arr ) {
	if ( is_array( $arr ) && count( $arr ) > 0 ) {
		return true;
	}

	return false;
}

function hocwp_register_taxonomy( $args = array() ) {
	$old_args      = $args;
	$name          = isset( $args['name'] ) ? $args['name'] : '';
	$singular_name = isset( $args['singular_name'] ) ? $args['singular_name'] : '';
	$menu_name     = isset( $args['menu_name'] ) ? $args['menu_name'] : '';

	if ( empty( $menu_name ) ) {
		$menu_name = $name;
	}

	$hierarchical      = isset( $args['hierarchical'] ) ? $args['hierarchical'] : true;
	$public            = isset( $args['public'] ) ? $args['public'] : true;
	$show_ui           = isset( $args['show_ui'] ) ? $args['show_ui'] : true;
	$show_admin_column = isset( $args['show_admin_column'] ) ? $args['show_admin_column'] : true;
	$show_in_nav_menus = isset( $args['show_in_nav_menus'] ) ? $args['show_in_nav_menus'] : true;
	$show_tagcloud     = isset( $args['show_tagcloud'] ) ? $args['show_tagcloud'] : ( ( $hierarchical === true ) ? false : true );
	$post_types        = isset( $args['post_types'] ) ? $args['post_types'] : array();

	if ( ! is_array( $post_types ) ) {
		$post_types = array( $post_types );
	}

	if ( ! hocwp_array_has_value( $post_types ) ) {
		$post_type  = hocwp_get_value_by_key( $args, 'post_type' );
		$post_types = array( $post_type );
	}

	$slug    = isset( $args['slug'] ) ? $args['slug'] : '';
	$private = isset( $args['private'] ) ? $args['private'] : false;

	if ( empty( $singular_name ) ) {
		$singular_name = $name;
	}

	if ( empty( $slug ) ) {
		$slug = $singular_name;
	}

	if ( empty( $name ) || empty( $slug ) || taxonomy_exists( $slug ) ) {
		return;
	}

	$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : $slug;
	$taxonomy = hocwp_sanitize_id( $taxonomy );

	if ( taxonomy_exists( $taxonomy ) ) {
		return;
	}

	$labels = array(
		'name'                       => $name,
		'singular_name'              => $singular_name,
		'menu_name'                  => $menu_name,
		'all_items'                  => sprintf( __( 'All %s', 'hocwp-init' ), $name ),
		'edit_item'                  => sprintf( __( 'Edit %s', 'hocwp-init' ), $singular_name ),
		'view_item'                  => sprintf( __( 'View %s', 'hocwp-init' ), $singular_name ),
		'update_item'                => sprintf( __( 'Update %s', 'hocwp-init' ), $singular_name ),
		'add_new_item'               => sprintf( __( 'Add New %s', 'hocwp-init' ), $singular_name ),
		'new_item_name'              => sprintf( __( 'New %s Name', 'hocwp-init' ), $singular_name ),
		'parent_item'                => sprintf( __( 'Parent %s', 'hocwp-init' ), $singular_name ),
		'parent_item_colon'          => sprintf( __( 'Parent %s:', 'hocwp-init' ), $singular_name ),
		'search_items'               => sprintf( __( 'Search %s', 'hocwp-init' ), $name ),
		'popular_items'              => sprintf( __( 'Popular %s', 'hocwp-init' ), $name ),
		'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'hocwp-init' ), strtolower( $name ) ),
		'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'hocwp-init' ), $name ),
		'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'hocwp-init' ), $name ),
		'not_found'                  => __( 'Not Found', 'hocwp-init' ),
	);

	$rewrite         = isset( $args['rewrite'] ) ? $args['rewrite'] : array();
	$rewrite_slug    = str_replace( '_', '-', $slug );
	$rewrite_slug    = apply_filters( 'hocwp_taxonomy_slug', $rewrite_slug, $taxonomy );
	$rewrite_slug    = apply_filters( 'hocwp_taxonomy_' . $taxonomy . '_slug', $rewrite_slug, $args );
	$rewrite['slug'] = $rewrite_slug;

	if ( $private ) {
		$public  = false;
		$rewrite = false;
	}

	$update_count_callback = isset( $args['update_count_callback'] ) ? $args['update_count_callback'] : '_update_post_term_count';
	$capabilities          = isset( $args['capabilities'] ) ? $args['capabilities'] : array( 'manage_terms' );
	$show_in_rest          = isset( $args['show_in_rest'] ) ? $args['show_in_rest'] : true;
	$show_in_rest          = boolval( $show_in_rest );

	$args = array(
		'labels'                => $labels,
		'hierarchical'          => $hierarchical,
		'public'                => $public,
		'show_ui'               => $show_ui,
		'show_admin_column'     => $show_admin_column,
		'show_in_nav_menus'     => $show_in_nav_menus,
		'show_tagcloud'         => $show_tagcloud,
		'query_var'             => true,
		'rewrite'               => $rewrite,
		'update_count_callback' => $update_count_callback,
		'capabilities'          => $capabilities
	);

	if ( $show_in_rest ) {
		$args['show_in_rest']          = true;
		$args['rest_base']             = $rewrite_slug . '-api';
		$args['rest_controller_class'] = 'WP_REST_Terms_Controller';
	}

	$args = wp_parse_args( $old_args, $args );
	unset( $args['name'] );
	unset( $args['singular_name'] );
	register_taxonomy( $taxonomy, $post_types, $args );
}

function hocwp_register_taxonomy_private( $args = array() ) {
	$args['exclude_from_search'] = true;
	$args['show_in_quick_edit']  = false;
	$args['show_in_nav_menus']   = false;
	$args['show_admin_column']   = false;
	$args['show_in_admin_bar']   = false;
	$args['menu_position']       = 9999999;
	$args['show_tagcloud']       = false;
	$args['has_archive']         = false;
	$args['query_var']           = false;
	$args['rewrite']             = false;
	$args['public']              = false;
	$args['feeds']               = false;
	hocwp_register_taxonomy( $args );
}

function hocwp_register_post_type_private( $args = array() ) {
	global $hocwp_private_post_types;
	$args['public']              = false;
	$args['exclude_from_search'] = true;
	$args['show_in_nav_menus']   = false;
	$args['show_in_admin_bar']   = false;
	$args['menu_position']       = 9999999;
	$args['has_archive']         = false;
	$args['query_var']           = false;
	$args['rewrite']             = false;
	$args['feeds']               = false;
	$slug                        = isset( $args['slug'] ) ? $args['slug'] : '';

	if ( ! empty( $slug ) ) {
		if ( ! is_array( $hocwp_private_post_types ) ) {
			$hocwp_private_post_types = array();
		}
		$hocwp_private_post_types[] = $slug;
	}

	hocwp_register_post_type( $args );
}

require HOCWP_INIT_INC_PATH . '/functions.php';
require HOCWP_INIT_INC_PATH . '/post-type-and-taxonomy.php';
require HOCWP_INIT_INC_PATH . '/shortcodes.php';
require HOCWP_INIT_INC_PATH . '/notify-license.php';
require HOCWP_INIT_INC_PATH . '/hook.php';

if ( is_admin() ) {
	require HOCWP_INIT_INC_PATH . '/back-end.php';
} else {
	require HOCWP_INIT_INC_PATH . '/front-end.php';
}