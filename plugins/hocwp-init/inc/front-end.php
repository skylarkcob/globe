<?php
function hocwp_init_front_end_request( $query_vars ) {
	if ( isset( $query_vars['feed'] ) ) {
		$post_types   = get_post_types( array( '_builtin' => false, 'public' => true ) );
		$post_types   = array_values( $post_types );
		$post_types[] = 'post';

		if ( ! is_tag() ) {
			unset( $post_types[ array_search( 'project', $post_types ) ] );
		}
		$post_types = array_unique( $post_types );

		$query_vars['post_type'] = $post_types;
	}

	return $query_vars;
}

add_filter( 'request', 'hocwp_init_front_end_request' );

function hocwp_init_template_redirect_action() {
	$go = get_query_var( 'go' );

	if ( empty( $go ) && isset( $_GET['goto'] ) ) {
		$goto   = $_GET['goto'];
		$domain = HT()->get_domain_name( $goto, true );

		if ( ! empty( $goto ) ) {
			$go = $goto;
		}

		unset( $goto, $domain );
	}

	if ( ! empty( $go ) ) {
		$go = HT()->get_domain_name( $go, true );

		$args = array(
			'post_type'      => 'aff',
			'posts_per_page' => 1,
			'meta_key'       => 'domain',
			'meta_value'     => $go
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$obj = current( $query->posts );
			unset( $go, $args, $query );
			wp_redirect( $obj->post_title );
			exit;
		}
	}

	if ( is_singular( 'aff' ) ) {
		global $post;

		wp_redirect( $post->post_title );
		exit;
	}
}

add_action( 'template_redirect', 'hocwp_init_template_redirect_action' );