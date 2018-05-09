<?php
function hocwp_theme_custom_post_tabs() {
	$defaults = array(
		'recent'      => _x( 'Recent Posts', 'post tab', 'hocwp-theme' ),
		'featured'    => _x( 'Featured Posts', 'post tab', 'hocwp-theme' ),
		'top_comment' => _x( 'Top Commented Posts', 'post tab', 'hocwp-theme' ),
		'modified'    => _x( 'Recent Modified Posts', 'post tab', 'hocwp-theme' ),
		'comment'     => _x( 'Recent Commented Posts', 'post tab', 'hocwp-theme' )
	);

	return apply_filters( 'hocwp_theme_custom_post_tabs', $defaults );
}

function hocwp_theme_custom_reference_function( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = do_shortcode( get_post_meta( $post_id, 'sbmb_reference_function', true ) );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<h2 class="reference-function">
		<a href="<?php echo get_permalink( $post_id ); ?>"><?php echo $value; ?></a>
	</h2>
	<?php
}

function hocwp_theme_custom_reference_description( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_description', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<section class="description">
		<p><?php echo apply_filters( 'the_content', $value ); ?></p>
	</section>
	<?php
}

function hocwp_theme_custom_reference_long_description( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_long_description', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<section class="long-description">
		<?php echo apply_filters( 'the_content', $value ); ?>
	</section>
	<?php
}

function hocwp_theme_custom_reference_return( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_return', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<section class="return">
		<p><strong><?php _e( 'Return', 'hocwp-theme' ); ?>
				:</strong> <?php echo apply_filters( 'the_content', $value ); ?></p>
	</section>
	<?php
}

function hocwp_theme_custom_reference_explanation( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_explanation', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<section class="explanation">
		<?php echo apply_filters( 'the_content', $value ); ?>
	</section>
	<?php
}

function hocwp_theme_custom_reference_parameters( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_parameters', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<hr>
	<section class="parameters">
		<h2><?php _e( 'Parameters', 'hocwp-theme' ); ?></h2>
		<?php echo apply_filters( 'the_content', $value ); ?>
	</section>
	<?php
}

function hocwp_theme_custom_reference_source( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_source', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<hr>
	<section class="source-content">
		<h2><?php _e( 'Source', 'hocwp-theme' ); ?></h2>
		<?php echo apply_filters( 'the_content', $value ); ?>
	</section>
	<?php
}

function hocwp_theme_custom_reference_example( $post_id = null ) {
	if ( ! HOCWP_Theme::is_positive_number( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$value = get_post_meta( $post_id, 'sbmb_reference_example', true );
	if ( empty( $value ) ) {
		return;
	}
	?>
	<hr>
	<section class="example">
		<h2><?php _e( 'Example', 'hocwp-theme' ); ?></h2>
		<?php echo apply_filters( 'the_content', $value ); ?>
	</section>
	<?php
}