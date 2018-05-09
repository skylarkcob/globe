<?php
function hocwp_init_admin_bar_menu_action( $wp_admin_bar ) {
	if ( ! current_user_can( 'edit_published_posts' ) ) {
		if ( $wp_admin_bar instanceof WP_Admin_Bar ) {
			$wp_admin_bar->remove_node( 'comments' );
			$wp_admin_bar->remove_node( 'wp-logo' );

			if ( ! is_admin() ) {
				$wp_admin_bar->remove_node( 'site-name' );
			}

			$types = hocwp_init_hide_post_types();

			foreach ( $types as $type ) {
				$wp_admin_bar->remove_node( 'new-' . $type );
			}
		}
	}
}

add_action( 'admin_bar_menu', 'hocwp_init_admin_bar_menu_action', 999 );

function hocwp_init_init_action() {
	add_rewrite_endpoint( 'go', EP_ALL );
}

add_action( 'init', 'hocwp_init_init_action' );

function hocwp_init_post_type_link_filter( $post_link, $post ) {
	if ( 'aff' == $post->post_type ) {
		$domain = HT()->get_domain_name( $post->post_title, true );

		if ( ! empty( $domain ) ) {
			$post_link = home_url( 'go/' . $domain );
		}
	}

	return $post_link;
}

add_filter( 'post_type_link', 'hocwp_init_post_type_link_filter', 10, 2 );

function hocwp_init_coupon_description_filter( $description ) {
	$description = do_shortcode( $description );

	return $description;
}

add_filter( 'coupon_description', 'hocwp_init_coupon_description_filter' );