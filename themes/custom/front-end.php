<?php
function hocwp_theme_custom_wp_action() {
	if ( is_search() ) {
		if ( empty( get_search_query() ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
}

add_action( 'wp', 'hocwp_theme_custom_wp_action' );

function hocwp_theme_custom_wp_footer_action() {

}

add_action( 'wp_footer', 'hocwp_theme_custom_wp_footer_action' );

function hocwp_theme_custom_comments_popup_link_class_filter( $class ) {
	$count = get_comments_number();
	if ( is_numeric( $count ) && $count > 50 ) {
		$class .= ' supernova';
	}

	return $class;
}

add_filter( 'hocwp_theme_comments_popup_link_class', 'hocwp_theme_custom_comments_popup_link_class_filter' );

function hocwp_theme_custom_post_thumbnail_html( $html, $post_id, $post_thumbnail_id ) {
	if ( HT_Util()->is_amp() ) {
		return $html;
	}

	if ( empty( $html ) && ! HOCWP_Theme::is_positive_number( $post_thumbnail_id ) ) {
		$title = get_the_title( $post_id );
		$char  = substr( $title, 0, 1 );
		$html  = '<span class="title-thumbnail">' . $char . '</span>';
	}

	return $html;
}

add_filter( 'post_thumbnail_html', 'hocwp_theme_custom_post_thumbnail_html', 10, 3 );

function hocwp_theme_custom_pre_get_posts( WP_Query $query ) {
	if ( $query->is_main_query() ) {
		if ( is_tax( 'series' ) ) {
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'asc' );
			$query->set( 'excerpt_length', 65 );
			$query->set( 'posts_per_page', 5 );
		} elseif ( is_tax( 'coupon' ) ) {
			$query->set( 'orderby', 'modified' );
		} elseif ( is_post_type_archive( 'service' ) ) {
			$query->set( 'posts_per_page', 6 );
			$query->set( 'orderby', 'name' );
			$query->set( 'order', 'asc' );
		} else {
			if ( is_search() || is_archive() || is_front_page() || is_tag() ) {
				if ( ! is_post_type_archive() || is_tag() ) {
					$post_types   = get_post_types( array( '_builtin' => false, 'public' => true ) );
					$post_types   = array_values( $post_types );
					$post_types[] = 'post';

					$service = array_search( 'service', $post_types );

					if ( $service ) {
						unset( $post_types[ $service ] );
					}

					if ( ! is_tag() ) {
						unset( $post_types[ array_search( 'project', $post_types ) ] );
					}

					$post_types = array_unique( $post_types );
					$query->set( 'post_type', $post_types );
				}

				$tab    = get_query_var( 'tab' );
				$offset = absint( $query->get( 'paged' ) ) - 1;

				if ( 0 > $offset ) {
					$offset = 0;
				}

				switch ( $tab ) {
					case 'featured':
						$query->set( 'meta_key', 'featured' );
						$query->set( 'meta_value', 1 );
						break;
					case 'modified':
						$query->set( 'orderby', 'modified' );
						$query->set( 'order', 'DESC' );
						break;
					case 'comment':
						break;
					case 'top_comment':
						$query->set( 'orderby', 'comment_count' );
						break;
				}
			}

			if ( is_post_type_archive( 'project' ) || is_tax( get_object_taxonomies( 'project' ) ) ) {
				$query->set( 'posts_per_page', 12 );
				$query->set( 'post_type', 'project' );
			}
		}
	}
}

if ( ! is_admin() ) {
	add_action( 'pre_get_posts', 'hocwp_theme_custom_pre_get_posts' );
	add_filter( 'posts_where', 'hocwp_theme_custom_posts_where_filter', 10, 2 );
}

function hocwp_theme_custom_posts_where_filter( $where, WP_Query $query ) {
	if ( is_search() || is_archive() || is_front_page() ) {
		global $wpdb;
		$tab = get_query_var( 'tab' );
		switch ( $tab ) {
			case 'featured':
				break;
			case 'modified':
				$where .= " AND $wpdb->posts.post_date != $wpdb->posts.post_modified";
				break;
			case 'comment':
				break;
		}
	}

	return $where;
}

function hocwp_theme_custom_scripts() {
	if ( is_post_type_archive( 'project' ) || is_tax( get_object_taxonomies( 'project' ) ) ) {
		wp_enqueue_script( 'lazyload', HOCWP_THEME_CUSTOM_URL . '/lib/lazyload/lazyload.min.js', array( 'jquery' ), false, true );
	} elseif ( is_single() || is_page() || is_tax( 'series' ) ) {
		wp_enqueue_script( 'hocwp-theme-sticky-widget', HOCWP_THEME_CORE_URL . '/js/sticky-widget' . HOCWP_THEME_JS_SUFFIX, array(), false, true );
	}
}

add_action( 'wp_enqueue_scripts', 'hocwp_theme_custom_scripts' );

function hocwp_theme_custom_sidebar_before() {
	if ( is_single() ) {
		hocwp_theme_load_custom_module( 'sidebar-sub' );
	}
}

add_action( 'hocwp_theme_sidebar_before', 'hocwp_theme_custom_sidebar_before' );

function hocwp_theme_custom_body_class_filter( $classes ) {
	if ( is_tax( 'coupon' ) ) {
		$classes[] = 'layout-2';
	}

	return $classes;
}

add_filter( 'body_class', 'hocwp_theme_custom_body_class_filter' );