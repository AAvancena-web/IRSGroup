<?php
/**
 * The theme header (IRS redesign).
 *
 * Global: this header renders on the homepage template and on every inner page,
 * so the redesign is consistent site wide. Inner pages additionally get the
 * banner enquiry form on the right, matching the homepage hero.
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
do_action( 'siteorigin_corp_body_top' );

$irs_phone      = irs_global( 'phone', '07 5518 8460' );
$irs_phone_href = 'tel:' . irs_phone_digits();
$irs_email      = irs_global( 'email', 'admin@irsgroup.com.au' );
$irs_logo       = irs_global( 'header_logo' );
$irs_logo_url   = is_array( $irs_logo ) && ! empty( $irs_logo['url'] ) ? $irs_logo['url'] : '';
?>

<div class="irs-scroll-progress" id="irs-scrollProgress" aria-hidden="true"></div>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'siteorigin-corp' ); ?></a>

	<div class="irs-topbar">
		<div class="irs-container">
			<div class="irs-topbar-note">
				<?php echo irs_icon( 'pin' ); ?>
				<span><?php echo esc_html( irs_global( 'topbar_note', 'Australia wide coverage, offices in Gold Coast QLD and Adelaide SA' ) ); ?></span>
			</div>
			<ul class="irs-topbar-links">
				<li><a href="mailto:<?php echo esc_attr( $irs_email ); ?>"><?php echo irs_icon( 'mail' ); ?><?php echo esc_html( $irs_email ); ?></a></li>
				<li><a href="<?php echo esc_attr( $irs_phone_href ); ?>"><?php echo irs_icon( 'phone' ); ?><?php echo esc_html( $irs_phone ); ?></a></li>
			</ul>
		</div>
	</div>

	<header id="irs-siteHeader" class="irs-site-header" role="banner">
		<div class="irs-container">
			<div class="irs-header-inner">

				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="irs-brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php if ( $irs_logo_url ) : ?>
						<img src="<?php echo esc_url( $irs_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php elseif ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="irs-brand-text"><?php bloginfo( 'name' ); ?></span>
					<?php endif; ?>
				</a>

				<nav class="irs-main-nav" aria-label="<?php esc_attr_e( 'Primary', 'siteorigin-corp' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'container'      => false,
							'menu_class'     => 'irs-nav-list',
							'depth'          => 2,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>

				<div class="irs-header-actions">
					<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-phone-circle" aria-label="<?php echo esc_attr( sprintf( __( 'Call us on %s', 'siteorigin-corp' ), $irs_phone ) ); ?>">
						<?php echo irs_icon( 'phone' ); ?>
					</a>
					<a href="<?php echo esc_url( irs_global( 'header_cta_url', home_url( '/contact-us/' ) ) ); ?>" class="irs-btn irs-btn--primary">
						<?php echo esc_html( irs_global( 'header_cta_label', 'Instant Quote' ) ); ?>
					</a>
					<button class="irs-burger" id="irs-burger" aria-label="<?php esc_attr_e( 'Open menu', 'siteorigin-corp' ); ?>" aria-expanded="false" aria-controls="irs-drawer">
						<span></span><span></span><span></span>
					</button>
				</div>

			</div>
		</div>
	</header>

	<div class="irs-drawer" id="irs-drawer">
		<div class="irs-drawer-panel">
			<div class="irs-drawer-head">
				<?php if ( $irs_logo_url ) : ?>
					<img src="<?php echo esc_url( $irs_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php else : ?>
					<span class="irs-brand-text"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
				<button class="irs-drawer-close" id="irs-drawerClose" aria-label="<?php esc_attr_e( 'Close menu', 'siteorigin-corp' ); ?>">&times;</button>
			</div>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'container'      => false,
					'menu_class'     => 'irs-m-nav',
					'depth'          => 2,
					'fallback_cb'    => false,
				)
			);
			?>
			<div class="irs-drawer-cta">
				<a href="<?php echo esc_url( irs_global( 'header_cta_url', home_url( '/contact-us/' ) ) ); ?>" class="irs-btn irs-btn--primary irs-btn--block"><?php echo esc_html( irs_global( 'header_cta_label', 'Instant Quote' ) ); ?></a>
			</div>
			<div class="irs-drawer-contact">
				<a href="<?php echo esc_attr( $irs_phone_href ); ?>"><?php echo irs_icon( 'phone' ); ?><?php echo esc_html( $irs_phone ); ?></a>
				<a href="mailto:<?php echo esc_attr( $irs_email ); ?>"><?php echo irs_icon( 'mail' ); ?><?php echo esc_html( $irs_email ); ?></a>
			</div>
		</div>
	</div>

<?php
do_action( 'siteorigin_corp_content_before' );

/*
 * Inner page banner. The homepage template renders its own hero, so this only
 * runs elsewhere. Layout matches the homepage: copy left, enquiry form right.
 */
if ( ! is_page_template( 'template-home.php' ) && ! is_front_page() ) :

	$irs_has_form  = irs_show_inner_banner_form();
	$irs_bg        = get_the_post_thumbnail_url( null, 'full' );
	if ( ! $irs_bg ) {
		$irs_fallback = irs_global( 'banner_fallback_image' );
		$irs_bg       = is_array( $irs_fallback ) && ! empty( $irs_fallback['url'] ) ? $irs_fallback['url'] : '';
	}

	$irs_title = '';
	if ( function_exists( 'get_field' ) && get_field( 'banner_title' ) ) {
		$irs_title = get_field( 'banner_title' );
	} elseif ( is_home() ) {
		$irs_title = get_the_title( get_option( 'page_for_posts' ) );
	} elseif ( is_archive() ) {
		$irs_title = get_the_archive_title();
	} elseif ( is_search() ) {
		$irs_title = sprintf( __( 'Search results for %s', 'siteorigin-corp' ), get_search_query() );
	} elseif ( is_404() ) {
		$irs_title = __( 'Page not found', 'siteorigin-corp' );
	} else {
		$irs_title = get_the_title();
	}

	$irs_text = ( function_exists( 'get_field' ) ) ? get_field( 'banner_text' ) : '';
	?>
	<section class="irs-hero irs-hero--inner<?php echo $irs_has_form ? '' : ' irs-hero--nofrom'; ?>">
		<?php if ( $irs_bg ) : ?>
			<img class="irs-hero-bg" src="<?php echo esc_url( $irs_bg ); ?>" alt="" aria-hidden="true">
		<?php endif; ?>
		<div class="irs-container">
			<div class="<?php echo $irs_has_form ? 'irs-hero-grid' : 'irs-hero-grid irs-hero-grid--single'; ?>">
				<div class="irs-hero-copy" data-reveal="left">
					<?php if ( ! is_front_page() ) : ?>
						<nav class="irs-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'siteorigin-corp' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'siteorigin-corp' ); ?></a>
							<span aria-hidden="true">/</span>
							<span><?php echo esc_html( wp_strip_all_tags( $irs_title ) ); ?></span>
						</nav>
					<?php endif; ?>
					<h1><?php echo wp_kses_post( $irs_title ); ?></h1>
					<?php if ( $irs_text ) : ?>
						<p class="irs-hero-lead"><?php echo wp_kses_post( $irs_text ); ?></p>
					<?php endif; ?>
					<div class="irs-hero-cta">
						<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-btn irs-btn--ghost irs-btn--lg"><?php echo irs_icon( 'phone' ); ?><?php echo esc_html( $irs_phone ); ?></a>
					</div>
				</div>

				<?php
				if ( $irs_has_form ) {
					get_template_part( 'template-parts/banner-form', null, array( 'reveal' => 'right' ) );
				}
				?>
			</div>
		</div>
	</section>
	<?php
endif;
?>

	<div id="content" class="site-content">
		<?php
		/*
		 * Existing inner pages are built with WPBakery and rely on .corp-container
		 * for their max width. The homepage template lays out its own full bleed
		 * sections, so the wrapper is skipped there. footer.php mirrors this.
		 */
		if ( ! irs_is_fullwidth_template() ) {
			echo '<div class="corp-container">';
		}
		do_action( 'siteorigin_corp_content_top' );
		?>
