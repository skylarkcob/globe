<?php
do_action( 'hocwp_theme_content_area_before' );
HT_Util()->breadcrumb();
while ( have_posts() ) {
	the_post();
	?>
	<div class="module">
		<div class="module-header clear">
			<h1 class="name"><?php the_title(); ?></h1>
		</div>
		<div class="module-body loop services">
			<?php
			$args = array(
				'post_type'      => 'service',
				'posts_per_page' => '6',
				'orderby'       => 'name',
				'order'          => 'asc'
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					hocwp_theme_load_custom_loop( 'service' );
				}

				wp_reset_postdata();
			} else {
				hocwp_theme_load_template_none();
			}
			?>
		</div>
		<?php
		if ( $query->have_posts() ) {
			HT_Util()->pagination( array( 'query' => $query ) );
		}
		?>
	</div>
	<?php
}
do_action( 'hocwp_theme_content_area_after' );
get_sidebar();