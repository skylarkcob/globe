<?php
$tabs = hocwp_theme_custom_post_tabs();
$tab  = get_query_var( 'tab' );
if ( empty( $tab ) ) {
	$tab = 'recent';
}
?>
<div id="tabs" class="post-tabs">
	<div id="mobile-post-tabs" class="mobile-tabs">
		<span class="text"><?php _e( 'Show', 'hocwp-theme' ); ?></span>
		<span class="current"><?php echo isset( $tabs[ $tab ] ) ? $tabs[ $tab ] : $tabs['recent']; ?></span>
	</div>
	<div class="tab-lists">
		<?php
		$count       = 0;
		$current_url = $current_url = HOCWP_Theme_Utility::get_current_url();
		if ( false !== strpos( $current_url, '/page/' ) ) {
			$parts       = explode( 'page/', $current_url );
			$current_url = array_shift( $parts );
			unset( $parts );
		}
		$current_url = trailingslashit( $current_url );
		foreach ( $tabs as $key => $text ) {
			$class = 'tab-item';
			if ( ( ! array_key_exists( $tab, $tabs ) && 0 == $count ) || $tab == $key ) {
				$class .= ' active';
			}
			$current_url = add_query_arg( array( 'tab' => $key ), $current_url );
			?>
			<a class="<?php echo $class; ?>" href="<?php echo esc_url( $current_url ); ?>" title=""
			   data-value="<?php echo $key; ?>"><?php echo $text; ?></a>
			<?php
			$count ++;
		}
		?>
	</div>
</div>