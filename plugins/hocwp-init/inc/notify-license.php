<?php
function hocwp_init_wp_footer_action() {
	$notify = isset( $_REQUEST['notify_license'] ) ? $_REQUEST['notify_license'] : '';
	$notify = absint( $notify );

	if ( 1 === $notify ) {
		$domain  = isset( $_REQUEST['domain'] ) ? $_REQUEST['domain'] : '';
		$email   = isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : '';
		$product = isset( $_REQUEST['product'] ) ? $_REQUEST['product'] : '';

		if ( ! empty( $domain ) && ! empty( $product ) ) {
			$tr_name = 'hocwp_notify_license_' . md5( $domain . $email . $product );

			if ( false === get_transient( $tr_name ) ) {
				$subject = __( 'Notify license', 'hocwp-init' );
				$message = wpautop( $domain );
				$message .= wpautop( $product );
				$message .= wpautop( $email );
				$email   = get_bloginfo( 'admin_email' );
				$headers = array( 'Content-Type: text/html; charset=UTF-8' );
				$sent    = wp_mail( $email, $subject, $message, $headers );

				if ( $sent ) {
					set_transient( $tr_name, 1, MONTH_IN_SECONDS );
				}
			}
		}
	}
}

add_action( 'wp_footer', 'hocwp_init_wp_footer_action' );