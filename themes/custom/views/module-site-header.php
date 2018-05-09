<div class="custom-header">
    <div class="custom-header-media">
		<?php the_custom_header_markup(); ?>
    </div>
</div><!-- .custom-header -->
<div id="header-main" class="inner">
    <div class="header-main-container">
		<?php do_action( 'hocwp_theme_site_branding' ); ?>
        <div class="nav-bar-container">
            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php
					HOCWP_Theme_SVG_Icon::bars();
					HOCWP_Theme_SVG_Icon::close();
					?>
                    <span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'hocwp-theme' ); ?></span>
                </button>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
				?>
            </nav>
            <!-- #site-navigation -->
            <div class="nav-bar-border"></div>
            <div class="overlay-text">
				<?php
				$urlparts = parse_url( home_url() );
				$domain   = $urlparts['host'];
				$domain   = str_replace( 'www.', '', $domain );
				?>
                <span><?php echo $domain; ?></span>
            </div>
            <div class="overlay-bg"></div>
            <div class="user-tools">
				<?php
				if ( is_user_logged_in() ) {
					?>
                    <a href="<?php echo wp_logout_url(); ?>"
                       title="<?php _e( 'Logout', 'hocwp-theme' ); ?>"><?php HOCWP_Theme_SVG_Icon::sign_out(); ?></a>
					<?php
				} else {
					?>
                    <a href="<?php echo wp_login_url(); ?>"
                       title="<?php _e( 'Login', 'hocwp-theme' ); ?>"><?php HOCWP_Theme_SVG_Icon::sign_in(); ?></a>
					<?php
				}
				?>
            </div>
        </div>
        <div id="site-search" class="site-search">
			<?php
			$form = get_search_form( false );
			ob_start();
			HOCWP_Theme_SVG_Icon::search();
			$icon = ob_get_clean();
			$form = hocwp_theme_replace_search_submit_button( $form, $icon );
			$form = str_replace( '</form>', $icon . '</form>', $form );
			echo $form;
			?>
        </div>
        <a href="#colophon" class="menu-scroll-down">
			<?php HOCWP_Theme_SVG_Icon::arrow_right(); ?>
            <span class="screen-reader-text"><?php _e( 'Scroll down to bottom', 'hocwp-theme' ); ?></span>
        </a>
    </div>
</div>