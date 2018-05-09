<?php
// Affiliate link
function sb_shortcode_affiliate_url( $atts = array(), $content = null ) {
	$result = '';
	extract( shortcode_atts( array( 'name' => '', 'text' => '' ), $atts ) );
	if ( empty( $text ) && $content && ! empty( $content ) ) {
		$text = $content;
	}
	$affiliate = hocwp_get_post_by_slug( $name );
	$url       = '';
	if ( $affiliate ) {
		$url = get_permalink( $affiliate );
	}
	if ( ! empty( $text ) && ! empty( $url ) ) {
		$result = sprintf( '<a href="%1$s" rel="nofollow" target="_blank" class="affiliate">%2$s</a>', $url, $text );
	}

	return $result;
}

add_shortcode( 'sbaff', 'sb_shortcode_affiliate_url' );

function hocwp_init_aff_shortcode_function( $atts = array(), $content = null ) {
	$atts = shortcode_atts( array(
		'post'   => '',
		'target' => '_blank',
		'rel'    => 'nofollow',
		'class'  => '',
		'url'    => '',
		'text'   => ''
	), $atts );

	$result = $domain = $obj = '';

	$class = $atts['class'];
	$class .= ' affiliate';
	$class = trim( $class );

	if ( ! $content || empty( $content ) ) {
		$content = $atts['text'];
	}

	if ( empty( $content ) ) {
		$content = $atts['url'];
	}

	if ( ! empty( $atts['url'] ) ) {
		$result = sprintf( '<a href="%s" rel="%s" target="%s" class="%s">%s</a>', $atts['url'], $atts['rel'], $atts['target'], $class, $content );
	}

	$search = $atts['post'];

	if ( empty( $search ) && $content && ! empty( $content ) ) {
		$search = $content;
	}

	if ( is_numeric( $search ) ) {
		$obj = get_post( $search );
	} else {
		$domain = HT()->get_domain_name( $search, true );

		$args = array(
			'post_type'      => 'aff',
			'posts_per_page' => 1,
			'meta_key'       => 'domain',
			'meta_value'     => $domain
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$obj = current( $query->posts );
		}
	}

	if ( $obj instanceof WP_Post && 'aff' == $obj->post_type ) {
		if ( empty( $domain ) ) {
			$domain = HT()->get_domain_name( $obj->post_title, true );
		}

		$domain = home_url( 'go/' . $domain );

		if ( ! $content || empty( $content ) ) {
			$content = $domain;
		}

		$result = sprintf( '<a href="%s" rel="%s" target="%s" class="%s">%s</a>', $domain, $atts['rel'], $atts['target'], $class, $content );
	}

	return $result;
}

add_shortcode( 'aff', 'hocwp_init_aff_shortcode_function' );

function hocwp_init_coupon_shortcode_function( $atts = array(), $content = null ) {
	if ( ! function_exists( 'HT_Util' ) ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'code'   => '',
		'url'    => '',
		'target' => '_blank',
		'text'   => '',
		'rel'    => 'nofollow'
	), $atts );

	$code = $atts['code'];

	if ( empty( $code ) ) {
		$code = __( 'Shop Now', 'hocwp-init' );
	}

	$text = $atts['text'];

	if ( empty( $text ) ) {
		$text = $code;
	}

	if ( HT_Util()->is_amp() || is_admin() ) {
		return '<a href="' . $atts['url'] . '" target="' . $atts['target'] . '" rel="' . $atts['rel'] . '" class="coupon-code">' . $text . '</a>';
	}

	if ( ! $content || empty( $content ) ) {
		if ( ! empty( $atts['code'] ) ) {
			$content = __( 'Copy and use the coupon below when making payment:', 'hocwp-init' );
		} else {
			$content = __( 'No coupon code required. Just shop online and save.', 'hocwp-init' );
		}
	}

	$content = wp_strip_all_tags( $content );

	if ( ! empty( $atts['code'] ) ) {
		$onclick = "prompt('" . $content . "', '" . $atts['code'] . "');";
	} else {
		$onclick = "alert('" . $content . "', '" . $atts['code'] . "');";
	}

	$onclick .= "window.open('" . $atts['url'] . "')";

	return '<a href="javascript:" target="' . $atts['target'] . '" rel="' . $atts['rel'] . '" onclick="' . $onclick . '" class="coupon-code">' . $text . '</a>';
}

add_shortcode( 'coupon', 'hocwp_init_coupon_shortcode_function' );

function hocwp_init_coupon_table_shortcode_function( $atts = array(), $content = null ) {
	$atts  = shortcode_atts( array( 'class' => '' ), $atts );
	$class = $atts['class'];

	if ( false === strpos( $class, 'coupon-table' ) ) {
		$class .= ' coupon-table';
	}

	$class = trim( $class );
	ob_start();
	?>
	<table class="<?php echo sanitize_html_class( $class ); ?>">
		<thead>
		<tr>
			<td><strong><?php _e( 'Coupon', 'hocwp-init' ); ?></strong></td>
			<td><strong><?php _e( 'Description', 'hocwp-init' ); ?></strong></td>
		</tr>
		</thead>
		<tbody>
		<?php
		if ( null != $content ) {
			echo do_shortcode( $content );
		}
		?>
		</tbody>
	</table>
	<?php
	return ob_get_clean();
}

add_shortcode( 'coupon_table', 'hocwp_init_coupon_table_shortcode_function' );

function hocwp_init_coupon_table_row_shortcode_function( $atts = array(), $content = null ) {
	$atts = shortcode_atts( array(
		'code' => '',
		'url'  => ''
	), $atts );

	ob_start();
	?>
	<tr>
		<td>
			<?php
			echo hocwp_init_coupon_shortcode_function( $atts );
			?>
		</td>
		<td>
			<?php
			if ( null != $content ) {
				echo do_shortcode( $content );
			}
			?>
		</td>
	</tr>
	<?php
	return ob_get_clean();
}

add_shortcode( 'coupon_table_row', 'hocwp_init_coupon_table_row_shortcode_function' );

// SB function
function sb_shortcode_function( $atts = array(), $content = null ) {
	extract( shortcode_atts( array( 'name' => '' ), $atts ) );
	$result = $name . ' ( ';
	$result .= do_shortcode( $content );
	$result .= ' )';

	return $result;
}

add_shortcode( 'sbfunction', 'sb_shortcode_function' );

// SB Hook
function sb_shortcode_hook( $atts = array(), $content = null ) {
	extract( shortcode_atts( array( 'function' => '', 'name' => '' ), $atts ) );
	$result = '<span class="hook-func">' . $function . '</span> ( \'' . $name . '\', ';
	$result .= do_shortcode( $content );
	$result .= ' )';

	return $result;
}

add_shortcode( 'sbhook', 'sb_shortcode_hook' );

// SB function argument
function sb_shortcode_function_arg( $atts = array(), $content = null ) {
	extract( shortcode_atts( array( 'name' => '', 'type' => '', 'default' => '' ), $atts ) );
	$result = ( ! empty( $type ) ) ? '<span class="arg-type">' . $type . '</span>' : '';
	$result .= ' <span class="arg-name">' . $name . '</span>';
	if ( $default && ! empty( $default ) && 'null' != $default ) {
		$result .= ' = ';
		$result = '<span class="arg-default">' . $default . '</span> ' . $result;
	}

	return $result;
}

add_shortcode( 'sbfunc_arg', 'sb_shortcode_function_arg' );

// SB parameter description
function sb_shortcode_parameter_description( $atts = array(), $content = null ) {
	$data = shortcode_atts( array( 'type' => '', 'required' => '', 'default' => '' ), $atts );
	$type = isset( $data['type'] ) ? $data['type'] : 'default';

	$args = array(
		'name'           => 'truyen-tham-so-trong-wordpress',
		'post_type'      => 'post',
		'posts_per_page' => 1
	);

	$query = new WP_Query( $args );

	$post = array_shift( $query->posts );

	if ( $post instanceof WP_Post ) {
		$url = get_permalink( $post );
		$url = trailingslashit( $url );
		$url .= '#' . ucfirst( strtolower( $type ) );
		$type = '<a href="' . $url . '">' . $type . '</a>';
	}

	$result = '<p class="desc">';
	$result .= '<span class="type">(' . $type . ')</span>';
	$required = isset( $data['required'] ) ? $data['required'] : 0;
	$required = (bool) $required;

	if ( $required ) {
		$required = _x( 'required', 'parameter', 'hocwp-init' );
	} else {
		$required = _x( 'optional', 'parameter', 'hocwp-init' );
	}

	$result .= '<span class="required"> (' . $required . ')</span>';
	$result .= '<span class="description"> ' . $content . '</span>';
	$result .= '</p>';

	if ( ! empty( $default ) ) {
		$result .= '<p class="default">' . _x( 'Default:', 'parameter', 'hocwp-init' ) . ' <span>' . $default . '</span>';
	}

	return $result;
}

add_shortcode( 'sbparameter_description', 'sb_shortcode_parameter_description' );