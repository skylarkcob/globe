<div <?php post_class(); ?>>
	<div class="inner">
		<?php do_action( 'hocwp_theme_the_title' ); ?>
		<div class="details">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'full' ); ?>
			</a>

			<div class="desc">
				<?php echo wp_trim_words( get_the_excerpt(), 40 ); ?>
			</div>
			<a class="view-more" href="<?php the_permalink(); ?>"><?php _e( 'View details', 'hocwp-theme' ); ?></a>
		</div>
	</div>
</div>