<?php
/**
 * ADD THESE LINES to the existing child theme functions.php.
 *
 * Paste the block below near the top of
 * wp-content/themes/siteorigin-corp-child/functions.php, after the
 * "END ENQUEUE PARENT ACTION" comment. Do not replace the whole file: the
 * existing shortcodes, sidebars and Slick assets are still used by the inner
 * pages built in WPBakery.
 */

/* ------------------------------------------------------------------
 * IRS redesign
 * ------------------------------------------------------------------ */
require_once get_stylesheet_directory() . '/inc/irs-setup.php';
require_once get_stylesheet_directory() . '/inc/irs-acf-fields.php';
require_once get_stylesheet_directory() . '/inc/irs-seeder.php';

/*
 * The redesigned header and footer replace the old markup, so the parent
 * theme's navigation script no longer has anything to bind to. Dropping it
 * avoids a redundant request and any chance of it fighting the new drawer.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_dequeue_script( 'siteorigin-corp-script' );
	},
	100
);

/*
 * Register the footer menu locations used by the redesigned footer columns.
 */
add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'irs-footer-1' => __( 'IRS Footer: Quick Links', 'siteorigin-corp' ),
				'irs-footer-2' => __( 'IRS Footer: Services', 'siteorigin-corp' ),
			)
		);
	}
);
