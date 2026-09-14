<?php
/**
 * IRS redesign: bootstrap, assets, ACF options page and shared helpers.
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IRS_REDESIGN_VERSION', '1.0.0' );

/**
 * Register the ACF options page that holds the global header, footer and
 * banner form content shared by every template.
 */
function irs_register_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'IRS Global Content', 'siteorigin-corp' ),
				'menu_title' => __( 'IRS Global', 'siteorigin-corp' ),
				'menu_slug'  => 'irs-global',
				'capability' => 'edit_theme_options',
				'redirect'   => false,
				'icon_url'   => 'dashicons-admin-customizer',
				'position'   => 59,
			)
		);
	}
}
add_action( 'acf/init', 'irs_register_options_page' );

/**
 * Front end assets.
 *
 * Loaded after the child theme stylesheet so the redesign wins where the two
 * overlap. The redesign CSS is fully irs- prefixed and uses px rather than rem,
 * because style.css sets a fluid html font-size that would otherwise rescale it.
 */
function irs_enqueue_redesign_assets() {
	$base = trailingslashit( get_stylesheet_directory_uri() );
	$dir  = trailingslashit( get_stylesheet_directory() );

	wp_enqueue_style(
		'irs-redesign',
		$base . 'assets/css/irs-redesign.css',
		array( 'chld_thm_cfg_child' ),
		file_exists( $dir . 'assets/css/irs-redesign.css' ) ? filemtime( $dir . 'assets/css/irs-redesign.css' ) : IRS_REDESIGN_VERSION
	);

	wp_enqueue_style(
		'irs-redesign-wp',
		$base . 'assets/css/irs-wp.css',
		array( 'irs-redesign' ),
		file_exists( $dir . 'assets/css/irs-wp.css' ) ? filemtime( $dir . 'assets/css/irs-wp.css' ) : IRS_REDESIGN_VERSION
	);

	// Must load before irs-redesign.js: it injects the drawer submenu toggles
	// that wp_nav_menu cannot output, which the main script then binds to.
	wp_enqueue_script(
		'irs-wp',
		$base . 'assets/js/irs-wp.js',
		array(),
		file_exists( $dir . 'assets/js/irs-wp.js' ) ? filemtime( $dir . 'assets/js/irs-wp.js' ) : IRS_REDESIGN_VERSION,
		true
	);

	wp_enqueue_script(
		'irs-redesign',
		$base . 'assets/js/irs-redesign.js',
		array( 'irs-wp' ),
		file_exists( $dir . 'assets/js/irs-redesign.js' ) ? filemtime( $dir . 'assets/js/irs-redesign.js' ) : IRS_REDESIGN_VERSION,
		true
	);

	wp_enqueue_style(
		'irs-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'irs_enqueue_redesign_assets', 20 );

/**
 * Read a global value from the ACF options page with a sensible fallback.
 *
 * @param string $key      Field name.
 * @param mixed  $fallback Value to use when ACF is missing or the field is empty.
 * @return mixed
 */
function irs_global( $key, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}
	$value = get_field( $key, 'option' );
	return ( '' === $value || null === $value || false === $value || array() === $value ) ? $fallback : $value;
}

/**
 * Phone number digits, safe for a tel: href.
 *
 * @return string
 */
function irs_phone_digits() {
	return preg_replace( '/[^0-9+]/', '', irs_global( 'phone', '07 5518 8460' ) );
}

/**
 * Inline SVG icons. Kept in PHP so markup stays dependency free.
 *
 * @param string $name  Icon key.
 * @param string $class Optional class attribute.
 * @return string
 */
function irs_icon( $name, $class = '' ) {
	$stroke = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
	$icons  = array(
		'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.4 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2Z"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
		'pin'       => '<path d="M12 22s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11Z"/><circle cx="12" cy="11" r="3"/>',
		'arrow'     => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'check'     => '<path d="M20 6 9 17l-5-5"/>',
		'check-c'   => '<circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/>',
		'chevron'   => '<path d="m6 9 6 6 6-6"/>',
		'lock'      => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'caret'     => '<path d="m6 9 6 6 6-6"/>',
		'experience'=> '<path d="M12 2v4"/><path d="M12 18v4"/><path d="m4.9 4.9 2.9 2.9"/><path d="m16.2 16.2 2.9 2.9"/><path d="M2 12h4"/><path d="M18 12h4"/><circle cx="12" cy="12" r="4"/>',
		'find'      => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'verify'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
		'report'    => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M9 11h3"/>',
	);
	$solid = array(
		'facebook'  => '<path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h3l1-3h-4v-2c0-.6.4-1 1-1Z"/>',
		'linkedin'  => '<path d="M6.9 8.5H4v11.6h2.9V8.5ZM5.4 3.9a1.7 1.7 0 1 0 0 3.4 1.7 1.7 0 0 0 0-3.4ZM20 13.7c0-3.1-1.7-4.6-3.9-4.6-1.8 0-2.6 1-3 1.7V8.5H10v11.6h2.9v-6.5c0-1.4.9-2.1 1.9-2.1s1.7.6 1.7 2.1v6.5H20v-6.4Z"/>',
	);

	$attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	if ( isset( $solid[ $name ] ) ) {
		return '<svg' . $attr . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $solid[ $name ] . '</svg>';
	}
	if ( 'instagram' === $name ) {
		return '<svg' . $attr . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.2" fill="currentColor" stroke="none"/></svg>';
	}
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg' . $attr . ' viewBox="0 0 24 24" ' . $stroke . ' aria-hidden="true">' . $icons[ $name ] . '</svg>';
}

/**
 * Render a button list from an ACF repeater of { label, url, style }.
 *
 * @param array  $rows  Repeater rows.
 * @param string $extra Extra classes applied to the first (primary) button.
 */
function irs_render_buttons( $rows, $extra = '' ) {
	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return;
	}
	foreach ( $rows as $i => $row ) {
		$label = isset( $row['label'] ) ? $row['label'] : '';
		$url   = isset( $row['url'] ) ? $row['url'] : '#';
		$style = isset( $row['style'] ) && $row['style'] ? $row['style'] : ( 0 === $i ? 'primary' : 'outline' );

		if ( ! $label ) {
			continue;
		}

		$classes = 'irs-btn irs-btn--lg irs-btn--' . sanitize_html_class( $style );
		if ( 0 === $i && $extra ) {
			$classes .= ' ' . $extra;
		}

		$is_phone = ( 0 === strpos( $url, 'tel:' ) );
		printf(
			'<a class="%1$s" href="%2$s">%3$s%4$s%5$s</a>',
			esc_attr( $classes ),
			esc_url( $url ),
			$is_phone ? irs_icon( 'phone' ) : '',
			esc_html( $label ),
			$is_phone ? '' : irs_icon( 'arrow' )
		);
	}
}

/**
 * Should the enquiry form show in the banner on this page?
 *
 * Home uses its own hero. Inner pages opt in globally, with a per page override.
 *
 * @return bool
 */
function irs_show_inner_banner_form() {
	if ( is_front_page() || is_page_template( 'template-home.php' ) ) {
		return false;
	}
	if ( function_exists( 'get_field' ) ) {
		$per_page = get_field( 'hide_banner_form' );
		if ( $per_page ) {
			return false;
		}
	}
	return (bool) irs_global( 'inner_banner_form', true );
}

/**
 * Templates that lay out their own full bleed sections and therefore skip the
 * theme's .corp-container wrapper. header.php and footer.php both consult this.
 *
 * @return bool
 */
function irs_is_fullwidth_template() {
	return is_page_template( 'template-home.php' ) || is_404();
}
