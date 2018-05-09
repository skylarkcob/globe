<?php
if ( is_active_sidebar( 'footer' ) ) {
	?>
	<div class="footer-widgets">
		<div class="inner">
			<?php dynamic_sidebar( 'footer' ); ?>
		</div>
	</div>
	<?php
}
if ( has_nav_menu( 'footer' ) ) {
	?>
	<div class="footer-navigation">
		<div class="inner">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'menu_id'        => 'footer-menu',
			) );
			?>
		</div>
	</div>
	<?php
}
?>
<div class="inner clearfix">
	<div id="copyright" class="fl copyright">
		<?php printf( __( '%s. Please ask for our permission before using our content outside our declared rights.', 'hocwp-theme' ), 'HocWP &copy; 2008 - ' . date( 'Y' ) ); ?>
	</div>
	<div class="with-love fr">
		<span><?php _e( 'Made with love in Buon Ciet.', 'hocwp-theme' ); ?></span>
	</div>
</div>
