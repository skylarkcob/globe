<?php
function hocwp_init_post_type_and_taxonomy() {
	$args = array(
		'name'                => __( 'Affiliates', 'hocwp-init' ),
		'singular_name'       => __( 'Affiliate', 'hocwp-init' ),
		'has_archive'         => false,
		'exclude_from_search' => true,
		'slug'                => 'aff',
		'show_in_nav_menu'    => true,
		'show_in_menu'        => true
	);
	hocwp_register_post_type_private( $args );

	$args = array(
		'name'             => __( 'Showcases', 'hocwp-init' ),
		'singular_name'    => __( 'Showcase', 'hocwp-init' ),
		'slug'             => 'showcase',
		'supports'         => array( 'thumbnail', 'editor' ),
		'taxonomies'       => array( 'post_tag' ),
		'show_in_nav_menu' => true,
		'show_in_menu'     => true
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'Blog', 'hocwp-init' ),
		'slug'              => 'blog',
		'supports'          => array( 'editor', 'thumbnail', 'comments' ),
		'show_in_admin_bar' => true,
		'taxonomies'        => array( 'post_tag' )
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'Home', 'hocwp-init' ),
		'slug'              => 'home',
		'supports'          => array( 'editor', 'thumbnail', 'comments' ),
		'show_in_admin_bar' => true,
		'taxonomies'        => array( 'post_tag' )
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'FAQs', 'hocwp-init' ),
		'singular_name'     => __( 'FAQ', 'hocwp-init' ),
		'slug'              => 'faq',
		'supports'          => array( 'editor', 'thumbnail' ),
		'show_in_admin_bar' => true,
		'taxonomies'        => array( 'post_tag' )
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'Projects', 'hocwp-init' ),
		'singular_name'     => __( 'Project', 'hocwp-init' ),
		'slug'              => 'project',
		'supports'          => array( 'thumbnail' ),
		'show_in_admin_bar' => true,
		'taxonomies'        => array( 'post_tag' )
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'             => __( 'References', 'hocwp-init' ),
		'singular_name'    => __( 'Reference', 'hocwp-init' ),
		'slug'             => 'reference',
		'supports'         => array( 'editor', 'excerpt' ),
		'taxonomies'       => array( 'post_tag' ),
		'show_in_nav_menu' => true,
		'show_in_menu'     => true
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'             => __( 'Examples', 'hocwp-init' ),
		'singular_name'    => __( 'Example', 'hocwp-init' ),
		'slug'             => 'example',
		'taxonomies'       => array( 'post_tag' ),
		'show_in_nav_menu' => true,
		'show_in_menu'     => true
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'Guides', 'hocwp-init' ),
		'singular_name'     => __( 'Guide', 'hocwp-init' ),
		'slug'              => 'guide',
		'taxonomies'        => array( 'post_tag' ),
		'supports'          => array( 'editor', 'thumbnail', 'comments' ),
		'show_in_admin_bar' => true
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'              => __( 'Services', 'hocwp-init' ),
		'singular_name'     => __( 'Service', 'hocwp-init' ),
		'slug'              => 'service',
		'supports'          => array( 'editor', 'thumbnail', 'comments' ),
		'show_in_admin_bar' => false,
		'show_in_nav_menu'  => false
	);
	hocwp_register_post_type( $args );

	$args = array(
		'name'             => __( 'Licenses', 'hocwp-init' ),
		'singular_name'    => __( 'License', 'hocwp-init' ),
		'slug'             => 'license',
		'show_in_nav_menu' => true,
		'show_in_menu'     => true
	);
	hocwp_register_post_type_private( $args );

	$args = array(
		'name'          => __( 'Showcase Tags', 'hocwp-init' ),
		'singular_name' => __( 'Showcase Tag', 'hocwp-init' ),
		'post_types'    => array( 'showcase' ),
		'hierarchical'  => false,
		'slug'          => 'stag'
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'          => __( 'Series', 'hocwp-init' ),
		'singular_name' => __( 'Serie', 'hocwp-init' ),
		'post_types'    => array( 'post', 'blog', 'guide', 'home' ),
		'hierarchical'  => true,
		'slug'          => 'series'
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'          => __( 'Coupons', 'hocwp-init' ),
		'singular_name' => __( 'Coupon', 'hocwp-init' ),
		'post_types'    => array( 'post' ),
		'hierarchical'  => true,
		'slug'          => 'coupon'
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'          => __( 'Reference Cats', 'hocwp-init' ),
		'singular_name' => __( 'Reference Cat', 'hocwp-init' ),
		'post_types'    => array( 'reference' ),
		'slug'          => 'rcat'
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'              => __( 'Project Cats', 'hocwp-init' ),
		'singular_name'     => __( 'Project Cat', 'hocwp-init' ),
		'post_types'        => array( 'project' ),
		'slug'              => 'pcat',
		'show_in_nav_menus' => true
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'              => __( 'Home Cats', 'hocwp-init' ),
		'singular_name'     => __( 'Home Cat', 'hocwp-init' ),
		'post_types'        => array( 'home', 'post' ),
		'slug'              => 'hcat',
		'show_in_nav_menus' => true
	);
	hocwp_register_taxonomy( $args );

	$args = array(
		'name'              => __( 'Blog Cats', 'hocwp-init' ),
		'singular_name'     => __( 'Blog Cat', 'hocwp-init' ),
		'post_types'        => array( 'blog', 'post' ),
		'slug'              => 'bcat',
		'show_in_nav_menus' => true
	);
	hocwp_register_taxonomy( $args );
}

add_action( 'init', 'hocwp_init_post_type_and_taxonomy', 2 );