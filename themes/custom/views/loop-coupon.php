<?php
global $hocwp_theme;
$post_id = get_the_ID();
$obj     = get_post( $post_id );

$defaults = $hocwp_theme->defaults;
$df       = $defaults['date_format'];
$tf       = $defaults['time_format'];

$title = get_the_title();

do_action( 'hocwp_theme_article_before' );
echo HT()->wrap_text( $title, '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
$term = '';
$taxs = get_object_taxonomies( $obj, 'objects' );

foreach ( $taxs as $tax ) {
	if ( $tax->hierarchical ) {
		if ( 'series' == $tax->name ) {
			continue;
		}

		$terms = wp_get_post_terms( $post_id, $tax->name );

		if ( HT()->array_has_value( $terms ) ) {
			$ct = current( $terms );

			$link = '<a href="' . get_term_link( $ct ) . '" title="' . $ct->name . '">' . $ct->name . '</a>';

			$term = '<span>' . sprintf( __( 'In: %s', 'hocwp-theme' ), $link ) . '</span>';

			break;
		}
	}
}

$updated = '<time datetime="' . get_the_date( 'c' ) . '" itemprop="datePublished">' . get_the_modified_time( $df ) . '</time>';
?>
	<div class="entry-meta">
		<div class="post-meta">
			<span><?php printf( __( 'By: %s', 'hocwp-theme' ), get_the_author() ); ?></span>
			<?php echo $term; ?>
			<span><?php printf( __( 'Last Updated: %s', 'hocwp-theme' ), $updated ); ?></span>
			<span>
				<?php hocwp_theme_comments_popup_link(); ?>
			</span>
		</div>
	</div>
	<div class="clear">
		<div class="thumb-box alignleft">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( array( 200, 120 ) );
			} else {
				$color = get_post_meta( $post_id, 'color', true );

				if ( empty( $color ) ) {
					$color = HOCWP_Theme::random_color_hex();
					update_post_meta( $post_id, 'color', $color );
				}
				?>
				<div class="color" style="background-color: <?php echo $color; ?>">
					<span><?php echo mb_substr( get_the_title(), 0, 1 ); ?></span>
				</div>
				<?php
			}
			?>
		</div>
		<?php
		do_action( 'hocwp_theme_the_excerpt' );
		?>
		<div class="readmore">
			<a class="btn_link cred" href="<?php the_permalink(); ?>"
			   title="<?php the_title(); ?>"><?php _e( 'Read Full &rarr;', 'hocwp-theme' ); ?></a>
		</div>
	</div>
<?php
do_action( 'hocwp_theme_article_after' );