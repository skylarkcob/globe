<?php
function hocwp_theme_custom_query_vars( $vars ) {
	$vars[] = 'tab';

	return $vars;
}

add_filter( 'query_vars', 'hocwp_theme_custom_query_vars' );

function hocwp_theme_custom_after_setup_theme() {

}

add_action( 'after_setup_theme', 'hocwp_theme_custom_after_setup_theme' );

function hocwp_theme_custom_admin_notices() {

}

add_action( 'admin_notices', 'hocwp_theme_custom_admin_notices' );

function hocwp_theme_custom_sidebar_widgets_filter( $sidebars_widgets ) {
	if ( ! is_admin() && isset( $sidebars_widgets['sidebar-1'] ) ) {
		shuffle( $sidebars_widgets['sidebar-1'] );
	}

	return $sidebars_widgets;
}

//add_filter( 'sidebars_widgets', 'hocwp_theme_custom_sidebar_widgets_filter' );

function hocwp_theme_custom_widgets_init_action() {
	$args = array(
		'name'          => esc_html__( 'Sub Sidebar', 'hocwp-theme' ),
		'id'            => 'sub-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'hocwp-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);
	register_sidebar( $args );

	$args = array(
		'name'          => esc_html__( 'Footer Widgets', 'hocwp-theme' ),
		'id'            => 'footer',
		'description'   => esc_html__( 'Add widgets here.', 'hocwp-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);
	register_sidebar( $args );
}

add_action( 'widgets_init', 'hocwp_theme_custom_widgets_init_action', 99 );

function hocwp_theme_custom_after_setup_theme_action() {
	register_nav_menus( array(
		'footer' => esc_html__( 'Footer', 'hocwp-theme' ),
	) );
}

add_action( 'after_setup_theme', 'hocwp_theme_custom_after_setup_theme_action', 99 );

function hocwp_theme_custom_init_action() {
	add_post_type_support( 'post', 'page-attributes' );
	add_post_type_support( 'guide', 'page-attributes' );
	add_post_type_support( 'blog', 'page-attributes' );
	add_post_type_support( 'home', 'page-attributes' );
}

add_action( 'init', 'hocwp_theme_custom_init_action' );