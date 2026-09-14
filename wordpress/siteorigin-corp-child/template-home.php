<?php
/**
 * Template Name: IRS Homepage (Redesign)
 *
 * Assign this from Page Attributes > Template on the page you want to use as
 * the homepage. All content comes from ACF fields on that page, populated once
 * by the seeder in inc/irs-seeder.php.
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$irs_phone      = irs_global( 'phone', '07 5518 8460' );
$irs_phone_href = 'tel:' . irs_phone_digits();
$irs_f          = function ( $key, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}
	$v = get_field( $key );
	return ( '' === $v || null === $v || false === $v || array() === $v ) ? $fallback : $v;
};
?>

<!-- Hero -->
<section class="irs-hero" id="irs-quote">
	<?php
	$irs_hero_bg = $irs_f( 'hero_background' );
	if ( is_array( $irs_hero_bg ) && ! empty( $irs_hero_bg['url'] ) ) : ?>
		<img class="irs-hero-bg" src="<?php echo esc_url( $irs_hero_bg['url'] ); ?>" alt="" aria-hidden="true">
	<?php endif; ?>

	<div class="irs-container">
		<div class="irs-hero-grid">
			<div class="irs-hero-copy" data-reveal="left">
				<?php if ( $irs_f( 'hero_badge' ) ) : ?>
					<span class="irs-hero-badge"><span class="irs-dot"></span> <?php echo esc_html( $irs_f( 'hero_badge' ) ); ?></span>
				<?php endif; ?>

				<h1>
					<?php echo esc_html( $irs_f( 'hero_title' ) ); ?>
					<?php if ( $irs_f( 'hero_title_highlight' ) ) : ?>
						<span class="irs-hl"><?php echo esc_html( $irs_f( 'hero_title_highlight' ) ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( $irs_f( 'hero_lead' ) ) : ?>
					<p class="irs-hero-lead"><?php echo esc_html( $irs_f( 'hero_lead' ) ); ?></p>
				<?php endif; ?>

				<div class="irs-hero-cta">
					<?php irs_render_buttons( $irs_f( 'hero_buttons', array() ), 'irs-btn--glow' ); ?>
				</div>

				<?php $irs_points = $irs_f( 'hero_points', array() ); ?>
				<?php if ( $irs_points ) : ?>
					<ul class="irs-hero-points">
						<?php foreach ( (array) $irs_points as $irs_point ) : ?>
							<li><?php echo irs_icon( 'check' ); ?><?php echo esc_html( is_array( $irs_point ) ? $irs_point['text'] : $irs_point ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/banner-form', null, array( 'reveal' => 'right' ) ); ?>
		</div>
	</div>
</section>

<!-- Stats -->
<?php $irs_stats = $irs_f( 'stats', array() ); ?>
<?php if ( $irs_stats ) : ?>
<section class="irs-stats">
	<div class="irs-container">
		<div class="irs-stats-grid" data-reveal="scale">
			<?php foreach ( (array) $irs_stats as $irs_stat ) : ?>
				<div class="irs-stat">
					<div class="irs-stat-num"><?php echo esc_html( $irs_stat['number'] ); ?></div>
					<div class="irs-stat-label"><?php echo esc_html( $irs_stat['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Services -->
<?php $irs_services = $irs_f( 'services', array() ); ?>
<?php if ( $irs_services ) : ?>
<section class="irs-section irs-section--alt irs-has-wash" id="services">
	<div class="irs-container">
		<div class="irs-sec-head irs-sec-head--center irs-reveal">
			<?php if ( $irs_f( 'services_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'services_eyebrow' ) ); ?></span><?php endif; ?>
			<h2><?php echo esc_html( $irs_f( 'services_title' ) ); ?></h2>
			<?php if ( $irs_f( 'services_intro' ) ) : ?><p><?php echo esc_html( $irs_f( 'services_intro' ) ); ?></p><?php endif; ?>
		</div>

		<div class="irs-svc-grid" data-stagger>
			<?php foreach ( (array) $irs_services as $irs_svc ) :
				$irs_img = isset( $irs_svc['image'] ) ? $irs_svc['image'] : null;
				$irs_url = isset( $irs_svc['url'] ) && $irs_svc['url'] ? $irs_svc['url'] : '#';
				?>
				<article class="irs-svc-card irs-reveal">
					<div class="irs-svc-media">
						<?php if ( ! empty( $irs_svc['tag'] ) ) : ?>
							<span class="irs-svc-tag"><?php echo esc_html( $irs_svc['tag'] ); ?></span>
						<?php endif; ?>
						<?php if ( is_array( $irs_img ) && ! empty( $irs_img['url'] ) ) : ?>
							<img src="<?php echo esc_url( $irs_img['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $irs_img['alt'] ) ? $irs_img['alt'] : $irs_svc['title'] ); ?>" loading="lazy">
						<?php endif; ?>
						<h3><?php echo esc_html( $irs_svc['title'] ); ?></h3>
					</div>
					<div class="irs-svc-body">
						<p><?php echo esc_html( $irs_svc['text'] ); ?></p>
						<a class="irs-svc-more" href="<?php echo esc_url( $irs_url ); ?>">
							<?php esc_html_e( 'Learn More', 'siteorigin-corp' ); ?>
							<span class="irs-circle"><?php echo irs_icon( 'arrow' ); ?></span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $irs_f( 'services_cta_text' ) ) : ?>
			<div class="irs-section-cta irs-reveal">
				<p class="irs-section-cta-text"><?php echo esc_html( $irs_f( 'services_cta_text' ) ); ?></p>
				<a href="#contact" class="irs-btn irs-btn--primary irs-btn--lg irs-btn--glow"><?php echo esc_html( $irs_f( 'services_cta_label', 'Get A Free Quote' ) ); ?> <?php echo irs_icon( 'arrow' ); ?></a>
				<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-btn irs-btn--outline irs-btn--lg"><?php echo irs_icon( 'phone' ); ?> <?php echo esc_html( $irs_phone ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<!-- Core service tabs -->
<?php $irs_tabs = $irs_f( 'tabs', array() ); ?>
<?php if ( $irs_tabs ) : ?>
<section class="irs-section irs-has-wash">
	<div class="irs-container">
		<div class="irs-sec-head irs-sec-head--center irs-reveal">
			<?php if ( $irs_f( 'tabs_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'tabs_eyebrow' ) ); ?></span><?php endif; ?>
			<h2><?php echo esc_html( $irs_f( 'tabs_title' ) ); ?></h2>
			<?php if ( $irs_f( 'tabs_intro' ) ) : ?><p><?php echo esc_html( $irs_f( 'tabs_intro' ) ); ?></p><?php endif; ?>
		</div>

		<div class="irs-tabs" id="irs-tabs">
			<div class="irs-tabs-nav" role="tablist" aria-label="<?php esc_attr_e( 'Core services', 'siteorigin-corp' ); ?>">
				<?php foreach ( (array) $irs_tabs as $i => $irs_tab ) : ?>
					<button class="irs-tab-btn<?php echo 0 === $i ? ' irs-is-active' : ''; ?>" role="tab"
						id="irs-tabbtn-<?php echo (int) $i; ?>"
						aria-controls="irs-tab-<?php echo (int) $i; ?>"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $irs_tab['label'] ); ?></button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( (array) $irs_tabs as $i => $irs_tab ) :
				$irs_timg = isset( $irs_tab['image'] ) ? $irs_tab['image'] : null;
				?>
				<div class="irs-tab-panel<?php echo 0 === $i ? ' irs-is-active' : ''; ?>" id="irs-tab-<?php echo (int) $i; ?>" role="tabpanel" aria-labelledby="irs-tabbtn-<?php echo (int) $i; ?>">
					<div class="irs-split">
						<div class="irs-split-media" data-reveal="left">
							<?php if ( is_array( $irs_timg ) && ! empty( $irs_timg['url'] ) ) : ?>
								<img src="<?php echo esc_url( $irs_timg['url'] ); ?>" alt="<?php echo esc_attr( $irs_tab['heading'] ); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<div data-reveal="right">
							<h3><?php echo esc_html( $irs_tab['heading'] ); ?></h3>
							<p><?php echo esc_html( $irs_tab['text'] ); ?></p>
							<?php if ( ! empty( $irs_tab['points'] ) ) : ?>
								<ul class="irs-check-list">
									<?php foreach ( (array) $irs_tab['points'] as $irs_pt ) : ?>
										<li><?php echo irs_icon( 'check-c' ); ?> <?php echo esc_html( is_array( $irs_pt ) ? $irs_pt['text'] : $irs_pt ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( ! empty( $irs_tab['cta_label'] ) ) : ?>
								<a href="<?php echo esc_url( ! empty( $irs_tab['cta_url'] ) ? $irs_tab['cta_url'] : '#contact' ); ?>" class="irs-btn irs-btn--primary"><?php echo esc_html( $irs_tab['cta_label'] ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $irs_f( 'tabs_cta_text' ) ) : ?>
			<div class="irs-section-cta irs-reveal">
				<p class="irs-section-cta-text"><?php echo esc_html( $irs_f( 'tabs_cta_text' ) ); ?></p>
				<a href="#contact" class="irs-btn irs-btn--primary irs-btn--lg irs-btn--glow"><?php echo esc_html( $irs_f( 'tabs_cta_label', 'Start Your Matter' ) ); ?> <?php echo irs_icon( 'arrow' ); ?></a>
				<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-btn irs-btn--outline irs-btn--lg"><?php echo irs_icon( 'phone' ); ?> <?php echo esc_html( $irs_phone ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<!-- Why choose us -->
<?php $irs_steps = $irs_f( 'steps', array() ); ?>
<?php if ( $irs_steps ) : ?>
<section class="irs-section irs-section--dark irs-has-aurora irs-why">
	<?php $irs_why_bg = $irs_f( 'why_background' ); ?>
	<?php if ( is_array( $irs_why_bg ) && ! empty( $irs_why_bg['url'] ) ) : ?>
		<img class="irs-why-bg" src="<?php echo esc_url( $irs_why_bg['url'] ); ?>" alt="" aria-hidden="true">
	<?php endif; ?>
	<div class="irs-container">
		<div class="irs-sec-head irs-sec-head--center irs-reveal">
			<?php if ( $irs_f( 'why_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'why_eyebrow' ) ); ?></span><?php endif; ?>
			<h2><?php echo esc_html( $irs_f( 'why_title' ) ); ?></h2>
			<?php if ( $irs_f( 'why_intro' ) ) : ?><p><?php echo esc_html( $irs_f( 'why_intro' ) ); ?></p><?php endif; ?>
		</div>

		<div class="irs-step-grid" data-stagger>
			<?php foreach ( (array) $irs_steps as $i => $irs_step ) : ?>
				<div class="irs-step irs-reveal" data-n="<?php echo esc_attr( sprintf( '%02d', $i + 1 ) ); ?>">
					<div class="irs-step-ico"><?php echo irs_icon( ! empty( $irs_step['icon'] ) ? $irs_step['icon'] : 'check-c' ); ?></div>
					<span class="irs-step-num"><?php printf( esc_html__( 'Step %s', 'siteorigin-corp' ), esc_html( sprintf( '%02d', $i + 1 ) ) ); ?></span>
					<h4><?php echo esc_html( $irs_step['title'] ); ?></h4>
					<p><?php echo esc_html( $irs_step['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $irs_f( 'why_cta_text' ) ) : ?>
			<div class="irs-section-cta irs-reveal">
				<p class="irs-section-cta-text"><?php echo esc_html( $irs_f( 'why_cta_text' ) ); ?></p>
				<a href="#contact" class="irs-btn irs-btn--primary irs-btn--lg irs-btn--glow"><?php echo esc_html( $irs_f( 'why_cta_label', 'Get Started Today' ) ); ?> <?php echo irs_icon( 'arrow' ); ?></a>
				<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-btn irs-btn--ghost irs-btn--lg"><?php echo irs_icon( 'phone' ); ?> <?php echo esc_html( $irs_phone ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<!-- What is a skiptrace -->
<?php if ( $irs_f( 'about_title' ) ) : ?>
<section class="irs-section irs-has-wash">
	<div class="irs-container">
		<div class="irs-split irs-split--flip">
			<div class="irs-reveal">
				<?php if ( $irs_f( 'about_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'about_eyebrow' ) ); ?></span><?php endif; ?>
				<h2><?php echo esc_html( $irs_f( 'about_title' ) ); ?></h2>
				<?php echo wp_kses_post( wpautop( $irs_f( 'about_body' ) ) ); ?>

				<?php $irs_apts = $irs_f( 'about_points', array() ); ?>
				<?php if ( $irs_apts ) : ?>
					<ul class="irs-check-list">
						<?php foreach ( (array) $irs_apts as $irs_pt ) : ?>
							<li><?php echo irs_icon( 'check-c' ); ?> <?php echo esc_html( is_array( $irs_pt ) ? $irs_pt['text'] : $irs_pt ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="irs-hero-cta" style="margin-bottom:0">
					<?php irs_render_buttons( $irs_f( 'about_buttons', array() ) ); ?>
				</div>
			</div>

			<div class="irs-split-media" data-reveal="right">
				<?php $irs_aimg = $irs_f( 'about_image' ); ?>
				<?php if ( is_array( $irs_aimg ) && ! empty( $irs_aimg['url'] ) ) : ?>
					<img src="<?php echo esc_url( $irs_aimg['url'] ); ?>" alt="<?php echo esc_attr( $irs_f( 'about_title' ) ); ?>" loading="lazy">
				<?php endif; ?>
				<?php if ( $irs_f( 'about_badge_number' ) ) : ?>
					<div class="irs-exp-badge">
						<strong><?php echo esc_html( $irs_f( 'about_badge_number' ) ); ?></strong>
						<span><?php echo esc_html( $irs_f( 'about_badge_label' ) ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- FAQ -->
<?php $irs_faqs = $irs_f( 'faqs', array() ); ?>
<?php if ( $irs_faqs ) : ?>
<section class="irs-section irs-section--tint irs-edge-top irs-has-wash">
	<div class="irs-container">
		<div class="irs-faq-wrap">
			<div class="irs-reveal">
				<?php if ( $irs_f( 'faq_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'faq_eyebrow' ) ); ?></span><?php endif; ?>
				<h2><?php echo esc_html( $irs_f( 'faq_title' ) ); ?></h2>
				<?php if ( $irs_f( 'faq_intro' ) ) : ?><p><?php echo wp_kses_post( $irs_f( 'faq_intro' ) ); ?></p><?php endif; ?>
				<div class="irs-hero-cta" style="margin-bottom:0;margin-top:24px">
					<a href="#contact" class="irs-btn irs-btn--primary"><?php echo esc_html( $irs_f( 'faq_cta_label', 'Ask A Question' ) ); ?></a>
				</div>
			</div>

			<div class="irs-faq-list" data-stagger>
				<?php foreach ( (array) $irs_faqs as $irs_faq ) : ?>
					<div class="irs-faq-item irs-reveal">
						<button class="irs-faq-q"><?php echo esc_html( $irs_faq['question'] ); ?>
							<span class="irs-sign"><?php echo irs_icon( 'chevron' ); ?></span>
						</button>
						<div class="irs-faq-a"><p><?php echo esc_html( $irs_faq['answer'] ); ?></p></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- CTA band -->
<?php if ( $irs_f( 'band_title' ) ) : ?>
<section class="irs-section--tight irs-cta-band">
	<div class="irs-container">
		<div class="irs-cta-inner">
			<div>
				<h2><?php echo esc_html( $irs_f( 'band_title' ) ); ?></h2>
				<p><?php echo esc_html( $irs_f( 'band_text' ) ); ?></p>
			</div>
			<div class="irs-cta-actions">
				<a href="<?php echo esc_attr( $irs_phone_href ); ?>" class="irs-btn irs-btn--white irs-btn--lg irs-btn--glow"><?php echo irs_icon( 'phone' ); ?> <?php esc_html_e( 'Call Now', 'siteorigin-corp' ); ?></a>
				<a href="#contact" class="irs-btn irs-btn--ghost irs-btn--lg"><?php esc_html_e( 'Get Started', 'siteorigin-corp' ); ?></a>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Contact -->
<section class="irs-section irs-section--alt irs-has-wash" id="contact">
	<div class="irs-container">
		<div class="irs-sec-head irs-sec-head--center irs-reveal">
			<?php if ( $irs_f( 'contact_eyebrow' ) ) : ?><span class="irs-eyebrow"><?php echo esc_html( $irs_f( 'contact_eyebrow' ) ); ?></span><?php endif; ?>
			<h2><?php echo esc_html( $irs_f( 'contact_title', 'Reach Out To Us Today' ) ); ?></h2>
			<?php if ( $irs_f( 'contact_intro' ) ) : ?><p><?php echo esc_html( $irs_f( 'contact_intro' ) ); ?></p><?php endif; ?>
		</div>

		<div class="irs-contact-grid">
			<div class="irs-reveal">
				<h3><?php esc_html_e( 'Contact Information', 'siteorigin-corp' ); ?></h3>
				<?php if ( $irs_f( 'contact_info_intro' ) ) : ?><p><?php echo esc_html( $irs_f( 'contact_info_intro' ) ); ?></p><?php endif; ?>

				<div class="irs-info-list" data-stagger>
					<a class="irs-info-item irs-reveal" href="<?php echo esc_attr( $irs_phone_href ); ?>">
						<span class="irs-info-ico"><?php echo irs_icon( 'phone' ); ?></span>
						<div class="irs-info-text">
							<h4><?php esc_html_e( 'Phone', 'siteorigin-corp' ); ?></h4>
							<span class="irs-val"><?php echo esc_html( $irs_phone ); ?></span>
							<span class="irs-sub"><?php echo esc_html( irs_global( 'hours', 'Monday to Friday, business hours AEST' ) ); ?></span>
						</div>
					</a>
					<a class="irs-info-item irs-reveal" href="mailto:<?php echo esc_attr( irs_global( 'email', 'admin@irsgroup.com.au' ) ); ?>">
						<span class="irs-info-ico"><?php echo irs_icon( 'mail' ); ?></span>
						<div class="irs-info-text">
							<h4><?php esc_html_e( 'Email', 'siteorigin-corp' ); ?></h4>
							<span class="irs-val"><?php echo esc_html( irs_global( 'email', 'admin@irsgroup.com.au' ) ); ?></span>
							<span class="irs-sub"><?php esc_html_e( 'We reply to every enquiry we receive', 'siteorigin-corp' ); ?></span>
						</div>
					</a>
					<div class="irs-info-item irs-reveal">
						<span class="irs-info-ico"><?php echo irs_icon( 'pin' ); ?></span>
						<div class="irs-info-text">
							<h4><?php esc_html_e( 'Offices', 'siteorigin-corp' ); ?></h4>
							<span class="irs-val"><?php echo esc_html( irs_global( 'offices_short', 'Gold Coast QLD and Adelaide SA' ) ); ?></span>
							<span class="irs-sub"><?php esc_html_e( 'Field agents operating Australia wide', 'siteorigin-corp' ); ?></span>
						</div>
					</div>
				</div>

				<?php $irs_map = irs_global( 'map_embed_url', 'https://www.google.com/maps?q=Gold%20Coast%20QLD%20Australia&output=embed' ); ?>
				<?php if ( $irs_map ) : ?>
					<div class="irs-map-frame">
						<iframe src="<?php echo esc_url( $irs_map ); ?>"
							title="<?php esc_attr_e( 'IRS Group office location on Google Maps', 'siteorigin-corp' ); ?>"
							loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
					</div>
				<?php endif; ?>
			</div>

			<div class="irs-contact-form-card irs-reveal">
				<h3><?php esc_html_e( 'Send An Enquiry', 'siteorigin-corp' ); ?></h3>
				<?php
				$irs_contact_form = irs_global( 'contact_form_shortcode', irs_global( 'banner_form_shortcode', '' ) );
				if ( $irs_contact_form ) {
					echo '<div class="irs-cf7">' . do_shortcode( $irs_contact_form ) . '</div>';
				} else {
					get_template_part( 'template-parts/banner-form', null, array( 'heading' => '', 'sub' => '', 'reveal' => 'fade' ) );
				}
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
