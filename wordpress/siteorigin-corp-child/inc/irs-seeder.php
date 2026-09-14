<?php
/**
 * One time content seeder.
 *
 * Populates the ACF fields for the homepage template and the global options
 * page with the approved redesign content, so the site matches the static build
 * the moment the template is assigned. No seeder admin page is created: it runs
 * once on admin_init, guarded by an option, and is safe to leave in place.
 *
 * Re-run manually with: /wp-admin/?irs_reseed=1 (administrators only).
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IRS_SEED_VERSION', '1.0.0' );
define( 'IRS_SEED_OPTION', 'irs_seed_version' );

/**
 * Find the page that should receive the homepage content.
 *
 * Prefers a page already using the template, then the configured static front
 * page. Returns 0 when neither exists yet, so the seeder simply waits.
 *
 * @return int
 */
function irs_seed_target_page() {
	$templated = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'template-home.php',
		)
	);
	if ( ! empty( $templated ) ) {
		return (int) $templated[0];
	}

	$front = (int) get_option( 'page_on_front' );
	return $front ? $front : 0;
}

/**
 * Find an existing Contact Form 7 form to use in the banner.
 *
 * @return string Shortcode, or an empty string when CF7 is not available.
 */
function irs_seed_find_cf7() {
	if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
		return '';
	}
	$forms = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);
	return empty( $forms ) ? '' : '[contact-form-7 id="' . (int) $forms[0] . '"]';
}

/**
 * Look up an attachment id by the tail of its file name, so seeded images point
 * at the media library rather than hard coded URLs.
 *
 * @param string $filename For example "homepage-banner-bg-img-1".
 * @return array|string ACF image array, or an empty string when not found.
 */
function irs_seed_image( $filename ) {
	global $wpdb;

	$id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta}
			 WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s
			 ORDER BY post_id DESC LIMIT 1",
			'%' . $wpdb->esc_like( $filename ) . '%'
		)
	);

	return $id ? (int) $id : '';
}

/**
 * The seed payload. Mirrors the approved static build.
 *
 * @return array
 */
function irs_seed_data() {
	$phone = '07 5518 8460';

	return array(
		'options' => array(
			'phone'                 => $phone,
			'email'                 => 'admin@irsgroup.com.au',
			'hours'                 => 'Monday to Friday, business hours AEST',
			'offices_short'         => 'Gold Coast QLD and Adelaide SA',
			'topbar_note'           => 'Australia wide coverage, offices in Gold Coast QLD and Adelaide SA',
			'header_logo'           => irs_seed_image( 'site-logo' ),
			'header_cta_label'      => 'Instant Quote',
			'header_cta_url'        => home_url( '/contact-us/' ),
			'mobile_cta_label'      => 'Call Now',
			'inner_banner_form'     => 1,
			'banner_form_shortcode' => irs_seed_find_cf7(),
			'banner_form_heading'   => 'Reach Out To Us Today',
			'banner_form_sub'       => 'Tell us what you need and our team will come back to you with a clear price and a plan.',
			'banner_form_note'      => 'Confidential and obligation free',
			'banner_fallback_image' => irs_seed_image( 'Service-banner' ),
			'footer_logo'           => irs_seed_image( 'footer-logo' ),
			'footer_about'          => 'IRS Group has head offices in Adelaide, South Australia and the Gold Coast, Queensland, with licensed field agents operating Australia wide.',
			'footer_offices'        => "Gold Coast, Queensland\nAdelaide, South Australia",
			'footer_menu_1_title'   => 'Quick Links',
			'footer_menu_2_title'   => 'Services',
			'footer_contact_title'  => 'Contact Information',
			'map_embed_url'         => 'https://www.google.com/maps?q=Gold%20Coast%20QLD%20Australia&output=embed',
			'social_links'          => array(
				array( 'network' => 'facebook', 'url' => 'https://www.facebook.com/irsgroupaus' ),
				array( 'network' => 'instagram', 'url' => 'https://www.instagram.com/ir.solutions/' ),
				array( 'network' => 'linkedin', 'url' => 'https://www.linkedin.com/company/international-recovery-solutions-pty-ltd-irs-/' ),
			),
		),

		'home' => array(
			'hero_background'      => irs_seed_image( 'homepage-banner-bg-img-1' ),
			'hero_badge'           => 'Trusted for over 25 years',
			'hero_title'           => 'We Find People, Serve Documents And',
			'hero_title_highlight' => 'Recover What You Are Owed',
			'hero_lead'            => 'IRS Group is your one stop shop for debt recovery. From Australia wide skiptracing and in depth locates to process serving, field calls, repossessions and collections, our licensed agents deliver results quickly, discreetly and within the law.',
			'hero_buttons'         => array(
				array( 'label' => 'Get A Free Quote', 'url' => '#contact', 'style' => 'primary' ),
				array( 'label' => $phone, 'url' => 'tel:0755188460', 'style' => 'ghost' ),
			),
			'hero_points'          => array(
				array( 'text' => 'Australia wide field network' ),
				array( 'text' => 'Licensed and fully compliant' ),
				array( 'text' => 'Bulk and single file capacity' ),
			),

			'stats' => array(
				array( 'number' => '25+', 'label' => 'Years locating people and recovering assets' ),
				array( 'number' => '10', 'label' => 'Specialist services under one roof' ),
				array( 'number' => '8', 'label' => 'States and territories covered' ),
				array( 'number' => '2', 'label' => 'Head offices, Gold Coast and Adelaide' ),
			),

			'services_eyebrow'   => 'Our Services',
			'services_title'     => 'Field Agents You Can Trust',
			'services_intro'     => 'Professional field agents are crucial in the locating and recovery process for debts and assets. Their expertise ensures effective tracing, minimises losses and lifts recovery rates, giving businesses and individuals real peace of mind.',
			'services_cta_text'  => 'Not sure which service fits your matter?',
			'services_cta_label' => 'Get A Free Quote',
			'services'           => array(
				array( 'image' => irs_seed_image( 'Skiptracing-Locates' ), 'tag' => 'Skiptracing', 'title' => 'Skiptracing Locates', 'text' => 'Address verification, phone numbers and emails sourced fast through advanced databases and decades of investigative technique.', 'url' => home_url( '/skiptracing-locates/' ) ),
				array( 'image' => irs_seed_image( 'Searches-1' ), 'tag' => 'Skiptracing', 'title' => 'Searches', 'text' => 'A deep range of search options giving you access to the information that moves your file forward.', 'url' => home_url( '/skip-trace-search/' ) ),
				array( 'image' => irs_seed_image( 'Investigations-In-Depth-Locations' ), 'tag' => 'Investigations', 'title' => 'In Depth Locations', 'text' => 'Traditional investigative technique combined with modern technology to find subjects who keep a deliberately low profile.', 'url' => home_url( '/investigations-in-depth-locations/' ) ),
				array( 'image' => irs_seed_image( 'International-Skiptracing' ), 'tag' => 'Global', 'title' => 'International Skiptracing', 'text' => 'Strong ties with international partners keep your matter moving when the subject has left the country.', 'url' => home_url( '/international-skiptracing/' ) ),
				array( 'image' => irs_seed_image( 'Bulk-Skiptracing-1' ), 'tag' => 'Volume', 'title' => 'High Volume Skiptracing', 'text' => 'Bulk ledgers traced with precision and speed, returned in a format that drops straight into your system.', 'url' => home_url( '/high-volume-skiptracing/' ) ),
				array( 'image' => irs_seed_image( 'Process-Serving' ), 'tag' => 'Field Services', 'title' => 'Process Serving', 'text' => 'Default notices, claims, family law and divorce documents served accurately, with affidavits returned promptly.', 'url' => home_url( '/process-serving/' ) ),
				array( 'image' => irs_seed_image( 'Field-Calls' ), 'tag' => 'Field Services', 'title' => 'Field Calls', 'text' => 'Metropolitan and remote doorstep contact to collect payments, information, photographs or specific items.', 'url' => home_url( '/field-calls/' ) ),
				array( 'image' => irs_seed_image( 'Repossessions' ), 'tag' => 'Recovery', 'title' => 'Repossessions', 'text' => 'Vehicles, property, machinery and livestock recovered by licensed agents who follow the legislation exactly.', 'url' => home_url( '/repossessions/' ) ),
				array( 'image' => irs_seed_image( 'Lockouts-Evictions' ), 'tag' => 'Recovery', 'title' => 'Lockouts and Evictions', 'text' => 'Lockouts conducted under the National Credit Code, supported by detailed property condition reports.', 'url' => home_url( '/lockouts-evictions/' ) ),
				array( 'image' => irs_seed_image( 'Collections' ), 'tag' => 'Collections', 'title' => 'Debt Collection', 'text' => 'A straightforward approach with transparent pricing, so you keep what is collected on your account.', 'url' => home_url( '/debt-collections/' ) ),
			),

			'tabs_eyebrow'   => 'IRS Group Services',
			'tabs_title'     => 'One Team For Every Stage Of Recovery',
			'tabs_intro'     => 'As part of our dedication to providing clients with all of their debt recovery needs, IRS Group proudly incorporates Australia wide skiptracing services into our already well established company.',
			'tabs_cta_text'  => 'Ready to get your file moving?',
			'tabs_cta_label' => 'Start Your Matter',
			'tabs'           => array(
				array(
					'label' => 'Skiptracing', 'image' => irs_seed_image( 'one-stop-shop-img' ),
					'heading' => 'The One Stop Shop For All Your Debt Recovery Needs',
					'text' => 'IRS Group is known in the industry for our skiptracing and locating skills. For over 25 years we have been the go to place for locating hard to find persons of interest without breaking the budget.',
					'points' => array(
						array( 'text' => 'Skilled lateral thinkers from varied investigative backgrounds' ),
						array( 'text' => 'Sophisticated technology paired with a personal approach' ),
						array( 'text' => 'Professional, respectful and discreet with every traced subject' ),
					),
					'cta_label' => 'Talk To A Skiptracer', 'cta_url' => '#contact',
				),
				array(
					'label' => 'Process Serving', 'image' => irs_seed_image( 'process-serving-box-img' ),
					'heading' => 'Safe And Legal Document Handling Assurance',
					'text' => 'For over 25 years our professional process serving has established itself as a leader in the field. Our team delivers a wide range of legal documents efficiently and accurately.',
					'points' => array(
						array( 'text' => 'Default notices, claims, family law and divorce documents' ),
						array( 'text' => 'Thorough understanding of state by state legal requirements' ),
						array( 'text' => 'Affidavits prepared and returned promptly' ),
					),
					'cta_label' => 'Book A Service', 'cta_url' => '#contact',
				),
				array(
					'label' => 'Field Calls', 'image' => irs_seed_image( 'home-left' ),
					'heading' => 'Comprehensive Field Call Assistance',
					'text' => 'IRS Group is your partner for field services across Australia, covering metropolitan areas and remote regions. Our extensive network guarantees dependable support wherever you are located.',
					'points' => array(
						array( 'text' => 'Court documents, letters of demand and default notices' ),
						array( 'text' => 'Collection of payments, information, photographs and items' ),
						array( 'text' => 'Qualified agents who report back with clear evidence' ),
					),
					'cta_label' => 'Request A Field Call', 'cta_url' => '#contact',
				),
				array(
					'label' => 'Repossessions', 'image' => irs_seed_image( 'car-repo-img' ),
					'heading' => 'Qualified Agents For Repossessions In Australia',
					'text' => 'We understand the complexities involved in reclaiming assets. Our highly skilled mercantile agents conduct repossession work professionally and ethically, adhering to all legislation with integrity.',
					'points' => array(
						array( 'text' => 'Vehicles, property, machinery, office equipment and livestock' ),
						array( 'text' => 'Full compliance with the National Credit Code' ),
						array( 'text' => 'Detailed condition reports on every recovery' ),
					),
					'cta_label' => 'Start A Recovery', 'cta_url' => '#contact',
				),
			),

			'why_background' => irs_seed_image( 'background_why' ),
			'why_eyebrow'    => 'Why Choose Us',
			'why_title'      => 'Do Not Know Where To Start? Here Is How We Help',
			'why_intro'      => 'Every file follows the same disciplined process, so you always know what stage your matter is at and what comes next.',
			'why_cta_text'   => 'Put 25 years of locating experience on your file.',
			'why_cta_label'  => 'Get Started Today',
			'steps'          => array(
				array( 'icon' => 'experience', 'title' => 'Experienced', 'text' => 'With over 25 years in the field we provide quality service and support across all debt recovery needs.' ),
				array( 'icon' => 'find', 'title' => 'Find', 'text' => 'We locate some of your toughest skips quickly and cost effectively while staying discreet and respectful.' ),
				array( 'icon' => 'verify', 'title' => 'Verify', 'text' => 'We share our skills, expertise and knowledge, examining every detail and leaving nothing unnoticed.' ),
				array( 'icon' => 'report', 'title' => 'Report', 'text' => 'You receive a clear report with everything we found, so your next decision is an informed one.' ),
			),

			'about_eyebrow'      => 'About Skiptracing',
			'about_title'        => 'What Is A Skiptrace',
			'about_body'         => "Skiptracing is the process of locating individuals whose contact information is unknown or out of date. Used across debt collection, real estate, legal matters and law enforcement, it draws on a wide range of tools and databases to establish a person's whereabouts.\n\nThat can mean a current residential address, a working phone number, an email, an employer or other public record information that lets you make contact and move your matter forward.",
			'about_points'       => array(
				array( 'text' => 'Address verification and reverse locates' ),
				array( 'text' => 'Full locates on hard to find subjects' ),
				array( 'text' => 'Bulk ledger tracing for lenders and agencies' ),
			),
			'about_buttons'      => array(
				array( 'label' => 'Learn More About Us', 'url' => home_url( '/who-we-are/' ), 'style' => 'primary' ),
				array( 'label' => 'Self Service Debt Collection', 'url' => 'https://app.icollecthq.com/a/irs-group/signup', 'style' => 'outline' ),
			),
			'about_image'        => irs_seed_image( 'who-we-are-large-back-img' ),
			'about_badge_number' => '25+',
			'about_badge_label'  => 'Years in the field',

			'faq_eyebrow'   => 'Common Questions',
			'faq_title'     => 'Answers Before You Book',
			'faq_intro'     => 'If your question is not covered here, call us on ' . $phone . ' and speak with someone who works on these files every day.',
			'faq_cta_label' => 'Ask A Question',
			'faqs'          => array(
				array( 'question' => 'How long does a skiptrace take?', 'answer' => 'Turnaround depends on the depth of the search. A straightforward address verification is usually far quicker than an in depth investigation on a subject who is deliberately keeping a low profile. We will give you a realistic timeframe when we quote your file.' ),
				array( 'question' => 'Do you cover all of Australia?', 'answer' => 'Yes. We have head offices on the Gold Coast in Queensland and in Adelaide, South Australia, with a field network covering metropolitan and remote areas nationally. We also work with international partners when a subject has left the country.' ),
				array( 'question' => 'Is skiptracing legal?', 'answer' => 'It is, when it is done properly. Our work relies on lawful sources, licensed agents and strict compliance with privacy and credit legislation, including the National Credit Code where it applies.' ),
				array( 'question' => 'Can you handle bulk files?', 'answer' => 'Absolutely. High volume skiptracing is one of our specialities. Send us your ledger and we will scope the work, agree pricing and return results in a structured format that drops straight back into your system.' ),
				array( 'question' => 'What does it cost?', 'answer' => 'Pricing depends on the service and the complexity of the matter. Send through the details using the form on this page and we will come back with a clear, itemised quote with no hidden extras.' ),
			),

			'band_title'         => 'Schedule A Complimentary Consultation Today',
			'band_text'          => 'Speak with our friendly team on ' . $phone . ' if you have questions or need clarification.',
			'contact_eyebrow'    => 'Get In Touch',
			'contact_title'      => 'Reach Out To Us Today',
			'contact_intro'      => 'Send us the details of your matter and our team will respond with a clear plan and an obligation free quote.',
			'contact_info_intro' => 'Our lines are open during business hours and every enquiry is handled by a real person, not an automated queue.',
		),
	);
}

/**
 * Write the seed values. Idempotent: safe to run more than once.
 *
 * @param bool $force Overwrite fields that already hold a value.
 * @return array Result summary.
 */
function irs_run_seeder( $force = false ) {
	if ( ! function_exists( 'update_field' ) ) {
		return array( 'ok' => false, 'message' => 'Advanced Custom Fields is not active.' );
	}

	$page = irs_seed_target_page();
	if ( ! $page ) {
		return array( 'ok' => false, 'message' => 'No page is using the IRS Homepage template yet, and no static front page is set.' );
	}

	$data    = irs_seed_data();
	$written = 0;
	$skipped = 0;

	foreach ( $data['options'] as $key => $value ) {
		if ( '' === $value || array() === $value ) {
			continue;
		}
		if ( ! $force && '' !== (string) get_field( $key, 'option' ) && null !== get_field( $key, 'option' ) && get_field( $key, 'option' ) ) {
			$skipped++;
			continue;
		}
		update_field( $key, $value, 'option' );
		$written++;
	}

	foreach ( $data['home'] as $key => $value ) {
		if ( '' === $value || array() === $value ) {
			continue;
		}
		$existing = get_field( $key, $page );
		if ( ! $force && ! empty( $existing ) ) {
			$skipped++;
			continue;
		}
		update_field( $key, $value, $page );
		$written++;
	}

	update_option( IRS_SEED_OPTION, IRS_SEED_VERSION );

	return array(
		'ok'      => true,
		'page'    => $page,
		'written' => $written,
		'skipped' => $skipped,
		'message' => sprintf( 'IRS seeder finished: %d fields written, %d left alone on "%s".', $written, $skipped, get_the_title( $page ) ),
	);
}

/**
 * Run once automatically, and honour a manual re-run request.
 */
function irs_maybe_seed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$force = false;

	if ( isset( $_GET['irs_reseed'] ) ) {
		$force = true;
	} elseif ( get_option( IRS_SEED_OPTION ) === IRS_SEED_VERSION ) {
		return;
	}

	$result = irs_run_seeder( $force );
	set_transient( 'irs_seed_notice', $result, 60 );
}
add_action( 'admin_init', 'irs_maybe_seed' );

/**
 * Show the outcome once, in the admin.
 */
function irs_seed_notice() {
	$result = get_transient( 'irs_seed_notice' );
	if ( ! $result ) {
		return;
	}
	delete_transient( 'irs_seed_notice' );

	printf(
		'<div class="notice notice-%s is-dismissible"><p><strong>IRS redesign:</strong> %s</p></div>',
		esc_attr( $result['ok'] ? 'success' : 'warning' ),
		esc_html( $result['message'] )
	);
}
add_action( 'admin_notices', 'irs_seed_notice' );
