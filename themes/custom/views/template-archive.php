<?php
do_action( 'hocwp_theme_content_area_before' );
HT_Util()->breadcrumb();
if ( is_post_type_archive( 'project' ) || is_tax( get_object_taxonomies( 'project' ) ) ) {
	hocwp_theme_load_custom_template( 'template-archive-project' );
} else {
	?>
	<div class="module">
		<div class="module-header clear">
			<h1 class="name"><?php the_archive_title(); ?></h1>

			<?php hocwp_theme_load_custom_module( 'post-tabs' ); ?>
		</div>
		<div class="module-body loop">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					hocwp_theme_load_custom_loop( 'post' );
				}
				the_posts_navigation();
			} else {
				hocwp_theme_load_template_none();
			}
			?>
		</div>
	</div>
	<?php
}
do_action( 'hocwp_theme_content_area_after' );
get_sidebar();