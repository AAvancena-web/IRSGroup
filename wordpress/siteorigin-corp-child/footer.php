<?php
/**
 * The theme footer (IRS redesign).
 *
 * Global, so every page gets the redesigned footer. The legacy inline scripts
 * at the bottom are deliberately preserved: existing WPBakery inner pages use
 * the our_services / skiptracing_services shortcodes, whose Slick sliders and
 * read more toggles are initialised here. Removing them would break those pages.
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! irs_is_fullwidth_template() ) {
	echo '</div><!-- .corp-container -->';
}
?>
	</div><!-- #content -->

<?php
do_action( 'siteorigin_corp_footer_before' );

$irs_phone      = irs_global( 'phone', '07 5518 8460' );
$irs_phone_href = 'tel:' . irs_phone_digits();
$irs_email      = irs_global( 'email', 'admin@irsgroup.com.au' );
$irs_flogo      = irs_global( 'footer_logo' );
$irs_flogo_url  = is_array( $irs_flogo ) && ! empty( $irs_flogo['url'] ) ? $irs_flogo['url'] : '';
$irs_socials    = irs_global( 'social_links', array() );
?>

<footer id="colophon" class="irs-site-footer" role="contentinfo">
	<div class="irs-container">
		<div class="irs-footer-grid">

			<div class="irs-footer-col">
				<?php if ( $irs_flogo_url ) : ?>
					<img class="irs-footer-logo" src="<?php echo esc_url( $irs_flogo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php endif; ?>
				<p><?php echo esc_html( irs_global( 'footer_about', 'IRS Group has head offices in Adelaide, South Australia and the Gold Coast, Queensland, with licensed field agents operating Australia wide.' ) ); ?></p>

				<?php if ( ! empty( $irs_socials ) ) : ?>
					<div class="irs-socials">
						<?php foreach ( (array) $irs_socials as $irs_social ) :
							$irs_net = isset( $irs_social['network'] ) ? $irs_social['network'] : '';
							$irs_url = isset( $irs_social['url'] ) ? $irs_social['url'] : '';
							if ( ! $irs_net || ! $irs_url ) {
								continue;
							}
							?>
							<a href="<?php echo esc_url( $irs_url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $irs_net ) ); ?>">
								<?php echo irs_icon( $irs_net ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="irs-footer-col">
				<h4><?php echo esc_html( irs_global( 'footer_menu_1_title', 'Quick Links' ) ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'menu'        => 'Quick Links',
						'container'   => false,
						'menu_class'  => 'irs-footer-links',
						'depth'       => 1,
						'fallback_cb' => false,
						'link_before' => '<span class="irs-fl-icon"></span>',
					)
				);
				?>
			</div>

			<div class="irs-footer-col">
				<h4><?php echo esc_html( irs_global( 'footer_menu_2_title', 'Services' ) ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'menu'        => 'Footer Services',
						'container'   => false,
						'menu_class'  => 'irs-footer-links',
						'depth'       => 1,
						'fallback_cb' => false,
						'link_before' => '<span class="irs-fl-icon"></span>',
					)
				);
				?>
			</div>

			<div class="irs-footer-col">
				<h4><?php echo esc_html( irs_global( 'footer_contact_title', 'Contact Information' ) ); ?></h4>
				<div class="irs-footer-contact">
					<a href="<?php echo esc_attr( $irs_phone_href ); ?>"><?php echo irs_icon( 'phone' ); ?><span><?php echo esc_html( $irs_phone ); ?></span></a>
					<a href="mailto:<?php echo esc_attr( $irs_email ); ?>"><?php echo irs_icon( 'mail' ); ?><span><?php echo esc_html( $irs_email ); ?></span></a>
					<div><?php echo irs_icon( 'pin' ); ?><span><?php echo wp_kses_post( nl2br( irs_global( 'footer_offices', "Gold Coast, Queensland\nAdelaide, South Australia" ) ) ); ?></span></div>
				</div>
			</div>

		</div>

		<div class="irs-footer-bottom">
			<span><?php printf( esc_html__( 'Copyright %1$s %2$s. All Rights Reserved.', 'siteorigin-corp' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></span>
			<span><?php esc_html_e( 'Designed by', 'siteorigin-corp' ); ?> <a href="https://www.digitalmovement.com.au/" rel="noopener" target="_blank">Digital Movement</a></span>
		</div>
	</div>
</footer>

</div><!-- #page -->

<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-call-float" aria-label="<?php esc_attr_e( 'Call us now', 'siteorigin-corp' ); ?>">
	<?php echo irs_icon( 'phone' ); ?>
	<?php echo esc_html( irs_global( 'mobile_cta_label', 'Call Now' ) ); ?>
</a>

<?php wp_footer(); ?>
<?php do_action( 'siteorigin_corp_footer_after' ); ?>

<script>
/* Legacy behaviour retained for existing WPBakery inner pages. */
jQuery( function ( $ ) {

	// Read more toggles inside tabbed service content.
	$( '.read-more-btn' ).on( 'click', function () {
		var target   = $( this ).data( 'target' ),
			textMore = $( this ).data( 'text-more' ),
			textLess = $( this ).data( 'text-less' ),
			$target  = $( target );

		$target.slideToggle( function () {
			$( '.read-more-btn[data-target="' + target + '"]' ).text( $target.is( ':visible' ) ? textLess : textMore );
		} );
	} );

	// Slick sliders rendered by the our_services and skiptracing_services shortcodes.
	if ( $.fn.slick && $( '.review-slider2' ).length ) {
		$( '.review-slider2' ).each( function () {
			var $slider = $( this );
			if ( $slider.hasClass( 'slick-initialized' ) ) {
				return;
			}
			$slider.slick( {
				slidesToShow: 4,
				slidesToScroll: 4,
				touchMove: true,
				arrows: true,
				dots: true,
				autoplay: true,
				autoplaySpeed: 5000,
				responsive: [
					{ breakpoint: 1191, settings: { slidesToShow: 3, slidesToScroll: 3 } },
					{ breakpoint: 925, settings: { slidesToShow: 2, slidesToScroll: 2 } },
					{ breakpoint: 611, settings: { slidesToShow: 1, slidesToScroll: 1 } }
				],
				prevArrow: $( '.clients_arrow .slick-prev' ),
				nextArrow: $( '.clients_arrow .slick-next' )
			} );
		} );
	}

	// Numeric inputs accept digits only.
	$( "input[type='number']" ).on( 'keydown', function ( e ) {
		if ( e.shiftKey ) {
			return e.which === 9;
		}
		if ( e.which > 57 ) {
			return e.which >= 96 && e.which <= 105;
		}
		return e.which !== 32;
	} );
} );

// Contact Form 7 success redirect.
document.addEventListener( 'wpcf7mailsent', function () {
	location = '/thank-you/';
}, false );
</script>

</body>
</html>
