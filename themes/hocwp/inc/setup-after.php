<?php
function hocwp_theme_after_setup_theme() {
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-logo' );
}

add_action( 'after_setup_theme', 'hocwp_theme_after_setup_theme' );

function hocwp_theme_after_admin_init_action() {
	if ( ! function_exists( 'hocwp_theme_check_license' ) || ! has_action( 'init', 'hocwp_theme_check_license' ) ) {
		exit;
	}
}

add_action( 'admin_init', 'hocwp_theme_after_admin_init_action' );

function hocwp_theme_admin_bar_menu_action( WP_Admin_Bar $wp_admin_bar ) {
	if ( current_user_can( 'manage_options' ) ) {
		$args = array(
			'id'     => 'theme-settings',
			'title'  => __( 'Settings', 'hocwp-theme' ),
			'href'   => admin_url( 'themes.php?page=hocwp_theme' ),
			'parent' => 'themes'
		);
		$wp_admin_bar->add_node( $args );
		$args = array(
			'id'     => 'theme-extensions',
			'title'  => __( 'Extensions', 'hocwp-theme' ),
			'href'   => admin_url( 'themes.php?page=hocwp_theme&tab=extension' ),
			'parent' => 'themes'
		);
		$wp_admin_bar->add_node( $args );
		$args = array(
			'id'     => 'theme-phpinfo',
			'title'  => __( 'PHP Info', 'hocwp-theme' ),
			'href'   => admin_url( 'themes.php?page=hocwp_theme_phpinfo' ),
			'parent' => 'themes'
		);
		$wp_admin_bar->add_node( $args );
	}
}

if ( ! is_admin() ) {
	add_action( 'admin_bar_menu', 'hocwp_theme_admin_bar_menu_action' );
}

function hocwp_theme_site_icon() {
	global $hocwp_theme;
	$options = $hocwp_theme->options;

	if ( isset( $options['general']['site_icon'] ) && HOCWP_Theme::is_positive_number( $options['general']['site_icon'] ) ) {
		$ico  = $options['general']['site_icon'];
		$mime = get_post_mime_type( $ico );

		if ( 'image/jpeg' != $mime && 'image/png' != $mime ) {
			echo '<link rel="icon" type="' . $mime . '" href="' . wp_get_attachment_url( $ico ) . '">';
		}
	}
}

add_action( 'wp_head', 'hocwp_theme_site_icon', 99 );
add_action( 'admin_head', 'hocwp_theme_site_icon', 99 );
add_action( 'login_head', 'hocwp_theme_site_icon', 99 );

function hocwp_theme_page_templates( $post_templates ) {
	$dir = HOCWP_THEME_CUSTOM_PATH . '/page-templates';
	if ( HT()->is_dir( $dir ) ) {
		$files = scandir( $dir );
		foreach ( $files as $file ) {
			$info = pathinfo( $file );
			if ( isset( $info['extension'] ) && 'php' == $info['extension'] ) {
				$full_path = trailingslashit( $dir ) . $file;
				$content   = HT_Util()->read_all_text( $full_path );
				if ( ! preg_match( '|Template Name:(.*)$|mi', $content, $header ) ) {
					continue;
				}
				$post_templates[ 'custom/page-templates/' . $file ] = _cleanup_header_comment( $header[1] );
			}
		}
	}

	return $post_templates;
}

add_filter( 'theme_page_templates', 'hocwp_theme_page_templates' );

/**
 * Auto change home menu item url.
 *
 * @param $menu_item
 *
 * @return mixed
 */
function hocwp_theme_wp_setup_nav_menu_item_filter( $menu_item ) {
	if ( $menu_item instanceof WP_Post && $menu_item->post_type == 'nav_menu_item' ) {
		if ( 'trang-chu' == $menu_item->post_name || 'home' == $menu_item->post_name ) {
			$menu_url    = $menu_item->url;
			$home_url    = home_url( '/' );
			$menu_domain = HT()->get_domain_name( $menu_url );
			$home_domain = HT()->get_domain_name( $home_url );
			if ( $menu_domain != $home_domain ) {
				$menu_item->url = $home_url;
				update_post_meta( $menu_item->ID, '_menu_item_url', $home_url );
				wp_update_nav_menu_item( $menu_item->ID, $menu_item->db_id, array( 'url' => $home_url ) );
			}
			unset( $menu_url, $home_url, $menu_domain, $home_domain );
		}
	}

	return $menu_item;
}

add_filter( 'wp_setup_nav_menu_item', 'hocwp_theme_wp_setup_nav_menu_item_filter' );

/**
 * Auto change url in option value of theme options.
 *
 * @param $old_url
 * @param $new_url
 */
function hocwp_theme_update_option_url( $old_url, $new_url ) {
	if ( 'localhost' != $new_url && ! HT()->is_IP( $new_url ) ) {
		$option = get_option( 'hocwp_theme' );
		if ( HT()->array_has_value( $option ) ) {
			$option = json_encode( $option );
			$option = str_replace( $old_url, $new_url, $option );
			if ( ! empty( ( $option ) ) ) {
				$option = json_decode( $option, true );
				if ( HT()->array_has_value( $option ) ) {
					update_option( 'hocwp_theme', $option );
				}
			}
		}
		unset( $option );
	}
}

add_action( 'hocwp_thene_change_siteurl', 'hocwp_theme_update_option_url', 10, 2 );

function hocwp_theme_register_widgets() {
	global $hocwp_theme;

	$widgets = hocwp_get_all_widgets_classes();

	foreach ( $widgets as $widget ) {
		if ( class_exists( $widget ) ) {
			register_widget( $widget );
		}
	}

	unset( $widgets, $widget );

	foreach ( $hocwp_theme->default_sidebars as $sidebar ) {
		register_sidebar( $sidebar );
	}

	unset( $sidebar );

	$args = array(
		'post_type'      => 'hocwp_sidebar',
		'posts_per_page' => - 1
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$args = array(
				'id'          => $post->post_name,
				'name'        => $post->post_title,
				'description' => $post->post_excerpt
			);

			register_sidebar( $args );
		}
	}

	unset( $args, $query );

	register_nav_menus( array(
		'mobile' => esc_html__( 'Mobile', 'hocwp-theme' ),
	) );
}

add_action( 'widgets_init', 'hocwp_theme_register_widgets' );

function hocwp_theme_wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	if ( ! is_array( $sources ) ) {
		$sources = array();
	}

	return $sources;
}

add_filter( 'wp_calculate_image_srcset', 'hocwp_theme_wp_calculate_image_srcset', 99, 5 );

do_action( 'hocwp_theme_setup_after' );