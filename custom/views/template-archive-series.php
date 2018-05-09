<?php
do_action( 'hocwp_theme_content_area_before' );
HT_Util()->breadcrumb();
$term        = get_queried_object();
$description = do_shortcode( $term->description );
?>
	<div class="module">
		<div class="module-header clear">
			<h1 class="name"><?php the_archive_title(); ?></h1>

			<div class="description">
				<?php echo wpautop( $description ); ?>
			</div>
		</div>
		<div class="module-body loop">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					hocwp_theme_load_custom_loop( 'series' );
				}
			} else {
				hocwp_theme_load_template_none();
			}
			?>
		</div>
		<?php
		if ( have_posts() ) {
			HT_Util()->pagination();
		}
		?>
	</div>
<?php
do_action( 'hocwp_theme_content_area_after' );
get_sidebar();