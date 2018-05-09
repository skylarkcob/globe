<?php
do_action( 'hocwp_theme_content_area_before' );
HT_Util()->breadcrumb();

while ( have_posts() ) {
	the_post();
	do_action( 'hocwp_theme_article_before' );
	$post_id = get_the_ID();
	?>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
		<div class="entry-meta">
			<?php hocwp_theme_posted_on(); ?>
		</div>
		<!-- .entry-meta -->
	</header><!-- .entry-header -->
	<?php
	hocwp_theme_socials();
	?>
	<div class="entry-content">
		<?php
		the_content( sprintf(
			wp_kses(
			/* translators: %s: Name of current post. */
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'hocwp-theme' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );

		$post_type = get_post_type();

		switch ( $post_type ) {
			case 'showcase':
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'full' );
				}
				break;
			case 'project':
				$demo_image = get_post_meta( $post_id, 'sbmb_project_full_image', true );
				$demo_image = HOCWP_Theme_Sanitize::media_value( $demo_image );

				if ( ! empty( $demo_image['url'] ) ) {
					$img = new HOCWP_Theme_HTML_Tag( 'img' );
					$img->add_attribute( 'src', $demo_image['url'] );
					$img->output();
				} else {
					$thumb = get_the_post_thumbnail( null, 'full' );
					echo wpautop( $thumb );
				}
				break;
			case 'reference':
				hocwp_theme_custom_reference_function();
				hocwp_theme_custom_reference_description();
				hocwp_theme_custom_reference_long_description();
				hocwp_theme_custom_reference_return();
				hocwp_theme_custom_reference_explanation();
				hocwp_theme_custom_reference_parameters();
				hocwp_theme_custom_reference_source();
				hocwp_theme_custom_reference_example();
				break;
		}

		$terms = wp_get_post_terms( $post_id, 'series' );

		if ( HT()->array_has_value( $terms ) ) {
			$term = current( $terms );
			$link = '<a href="' . get_term_link( $term ) . '" title="' . $term->name . '">' . __( 'other posts', 'hocwp-theme' ) . '</a>';
			?>
			<div class="series-box">
				<p><?php printf( __( 'You are viewing post in serie <strong>%s</strong>. If you find this article useful, you can also view %s in this list.', 'hocwp-theme' ), $term->name, $link ); ?></p>
			</div>
			<?php
		}

		wp_link_pages( array(
			'before'      => '<div class="page-links post-pagination"><span class="pages">' . __( 'Pages:', 'hocwp-theme' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php hocwp_theme_load_custom_module( 'post-terms-buttons' ); ?>
	</footer><!-- .entry-footer -->
	<?php
	do_action( 'hocwp_theme_article_after' );
	the_post_navigation();
	do_action( 'hocwp_theme_related_posts' );

	if ( hocwp_theme_comments_open() ) {
		comments_template();
	}
}

do_action( 'hocwp_theme_content_area_after' );
get_sidebar();