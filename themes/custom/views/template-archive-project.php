<div class="module">
	<div class="module-header clear">
		<h1 class="name"><?php the_archive_title(); ?></h1>

		<?php hocwp_theme_load_custom_module( 'post-tabs' ); ?>
	</div>
	<div class="module-body loop loop-projects">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				hocwp_theme_load_custom_loop( 'project' );
			}
			HT_Util()->pagination();
		} else {
			hocwp_theme_load_template_none();
		}
		?>
		<script>
			jQuery(document).ready(function ($) {
				(function () {
					$("img.wp-post-image").lazyload();
				})();
			});
		</script>
	</div>
</div>