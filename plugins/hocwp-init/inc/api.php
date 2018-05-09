<?php
function hocwp_init_allow_license_meta_query_in_rest( $valid_vars ) {
	$valid_vars = array_merge( $valid_vars, array(
		'license_type',
		'use_for',
		'customer_email',
		'customer_site',
		'customer_domain',
		'license_code',
		'forever_domain'
	) );

	return $valid_vars;
}

add_filter( 'rest_query_vars', 'hocwp_init_allow_license_meta_query_in_rest' );

function hocwp_init_rest_api_license_data() {
	register_rest_field( 'license',
		'license_data',
		array(
			'get_callback'    => 'hocwp_init_rest_api_license_data_callback',
			'update_callback' => null,
			'schema'          => null
		)
	);
}

add_action( 'rest_api_init', 'hocwp_init_rest_api_license_data' );

function hocwp_init_rest_api_license_data_callback( $object, $field_name, $request ) {
	$data                   = array();
	$post_id                = $object['id'];
	$data['user']           = get_post_meta( $post_id, 'user', true );
	$data['customer_email'] = hocwp_get_post_meta( 'customer_email', $post_id );
	$data['active']         = hocwp_get_post_meta( 'active', $post_id );

	return $data;
}

function hocwp_json_prepare_license( $data, $post, $context ) {
	$blocked               = get_post_meta( $post->ID, 'blocked', true );
	$data->data['blocked'] = absint( $blocked );
	$domain                = get_post_meta( $post->ID, 'domain', true );
	if ( empty( $domain ) ) {
		$domain = get_post_meta( $post->ID, 'customer_domain', true );
		if ( empty( $domain ) ) {
			$domain = get_post_meta( $post->ID, 'customer_site', true );
		}
		if ( ! empty( $domain ) ) {
			$domain = hocwp_get_root_domain_name( $domain );
			update_post_meta( $post->ID, 'domain', $domain );
		}
	}
	if ( empty( $domain ) ) {
		$domain = $post->post_title;
	}
	$domain               = hocwp_get_root_domain_name( $domain );
	$data->data['domain'] = $domain;

	return $data;
}

add_filter( 'rest_prepare_license', 'hocwp_json_prepare_license', 10, 3 );

function hocwp_plugin_init_rest_license_query( $args, $request ) {
	$params = $request->get_params();
	if ( isset( $params ['filter'] ) ) {
		$filters = $params ['filter'];
		if ( isset( $filters['meta_key'] ) ) {
			$args['meta_key']   = $filters['meta_key'];
			$args['meta_value'] = $filters['meta_value'];
		}
		if ( isset( $filters['meta_query'] ) ) {
			$meta_query = $filters['meta_query'];
			if ( isset( $args['meta_query'] ) ) {
				$meta_query = wp_parse_args( $meta_query, $args['meta_query'] );
			}
			$args['meta_query'] = $meta_query;
		}
		if ( isset( $filters['name'] ) ) {
			$args['name'] = $filters['name'];
		}
	}

	return $args;
}

add_filter( 'rest_license_query', 'hocwp_plugin_init_rest_license_query', 10, 2 );

function hocwp_plugin_init_rest_prepare_license( $response, $post, $request ) {

	return $response;
}

add_filter( 'rest_prepare_license', 'hocwp_plugin_init_rest_prepare_license', 10, 3 );