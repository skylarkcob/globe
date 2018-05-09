<div <?php post_class(); ?>>
	<h2><?php the_title(); ?></h2>

	<div class="item-img">
		<a href="<?php the_permalink(); ?>">
			<?php
			hocwp_theme_post_thumbnail(
				array(
					'width' => 288,
					'crop'  => 0
				),
				array( 'lazyload' => true )
			);
			?>
		</a>
	</div>
</div>