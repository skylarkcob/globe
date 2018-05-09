<?php
function hocwp_init_hide_menus() {
	$hide_menus = array(
		'edit-comments.php',
		'tools.php',
		'index.php'
	);

	return $hide_menus;
}

function hocwp_init_admin_menu_action() {
	if ( ! current_user_can( 'edit_published_posts' ) ) {
		$hide_types = hocwp_init_hide_post_types();

		foreach ( $hide_types as $post_type ) {
			remove_menu_page( 'edit.php?post_type=' . $post_type );
		}

		$hide_menus = hocwp_init_hide_menus();

		foreach ( $hide_menus as $menu ) {
			remove_menu_page( $menu );
		}
	}
}

add_action( 'admin_menu', 'hocwp_init_admin_menu_action' );

function hocwp_init_admin_init_action() {
	global $pagenow;

	if ( ! current_user_can( 'edit_published_posts' ) ) {
		$hide_types = hocwp_init_hide_post_types();

		$hide_menus = hocwp_init_hide_menus();

		$post_type = HT_Util()->get_current_post_type();

		if ( in_array( $pagenow, $hide_menus ) || ( 'edit.php' == $pagenow && in_array( $post_type, $hide_types ) ) || ( 'post-new.php' == $pagenow && in_array( $post_type, $hide_types ) ) ) {
			wp_redirect( admin_url( 'profile.php' ) );
			exit;
		}
	}
}

add_action( 'admin_init', 'hocwp_init_admin_init_action', 99 );

function hocwp_init_save_post_action( $post_id ) {
	$obj = get_post( $post_id );

	if ( 'aff' == $obj->post_type ) {
		$domain = HT()->get_domain_name( $obj->post_title, true );
		update_post_meta( $post_id, 'domain', $domain );
	}
}

add_action( 'save_post', 'hocwp_init_save_post_action' );