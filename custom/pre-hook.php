<?php
/**
 * Define the theme name.
 */
define( 'HOCWP_THEME_NAME', 'Globe' );

/**
 * Define the required plugins for current theme.
 *
 * Data type: string
 *
 * Each plugin slug separates by commas.
 */
define( 'HOCWP_THEME_REQUIRED_PLUGINS', 'sb-core' );

/**
 * Skip work time checking.
 *
 * If you still want to continue working, just define this value to TRUE.
 */
define( 'HOCWP_THEME_OVERTIME', true );

define( 'HOCWP_THEME_BREAK_MINUTES', 0 );

function hocwp_theme_custom_mobile_width() {
	return 0;
}

add_filter( 'hocwp_theme_mobile_menu_media_screen_width', 'hocwp_theme_custom_mobile_width' );
