<?php
do_action( 'hocwp_theme_content_area_before' );
?>
	<div class="module">
		<div class="module-header clear">
			<h2 class="name"><?php _e( 'Posts', 'hocwp-theme' ); ?></h2>

			<?php hocwp_theme_load_custom_module( 'post-tabs' ); ?>
		</div>
		<div class="module-body loop">
			<?php
			$tab = get_query_var( 'tab' );
			if ( have_posts() && ( empty( $tab ) || 'comment' != $tab ) ) {
				while ( have_posts() ) {
					the_post();
					hocwp_theme_load_custom_loop( 'post' );
				}
				the_posts_navigation();
			} else {
				$have_posts = false;
				if ( 'comment' == $tab ) {
					global $wpdb;
					$ppp     = get_option( 'posts_per_page' );
					$results = $wpdb->get_results( "SELECT wp_posts.ID, MAX(comment_date_gmt) AS comment_date_gmt FROM $wpdb->posts wp_posts RIGHT JOIN $wpdb->comments ON ( ID = $wpdb->comments.comment_post_ID AND '1' = $wpdb->comments.comment_approved ) WHERE wp_posts.comment_count > 1 GROUP BY ID ORDER BY comment_date_gmt DESC LIMIT $ppp" );
					if ( $results ) {
						$have_posts = true;
						global $post;
						$tmp = $post;
						foreach ( $results as $obj ) {
							$post = get_post( $obj->ID );
							setup_postdata( $post );
							hocwp_theme_load_custom_loop( 'post' );
						}
						wp_reset_postdata();
						$post = $tmp;
					}
				}
				if ( ! $have_posts ) {
					hocwp_theme_load_template_none();
				}
			}
			?>
		</div>
	</div>
<?php
do_action( 'hocwp_theme_content_area_after' );
get_sidebar();