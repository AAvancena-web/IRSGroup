<?php
/**
 * ACF field groups registered in PHP.
 *
 * Registering in code rather than the admin UI means the fields deploy with the
 * theme, need no JSON sync step, and cannot drift between environments. Field
 * values stay fully editable in the admin; only the definitions are code owned.
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a field array with a deterministic key.
 *
 * @param string $name  Field name.
 * @param string $label Field label.
 * @param string $type  Field type.
 * @param array  $extra Extra field settings.
 * @return array
 */
function irs_acf_field( $name, $label, $type = 'text', $extra = array() ) {
	return array_merge(
		array(
			'key'   => 'field_irs_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Repeater rows of { label, url, style } used for button groups.
 *
 * @param string $name Field name.
 * @param string $label Field label.
 * @return array
 */
function irs_acf_button_repeater( $name, $label ) {
	return irs_acf_field(
		$name,
		$label,
		'repeater',
		array(
			'layout'       => 'table',
			'button_label' => __( 'Add button', 'siteorigin-corp' ),
			'sub_fields'   => array(
				irs_acf_field( $name . '_label', __( 'Label', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( $name . '_url', __( 'URL', 'siteorigin-corp' ), 'text' ),
				irs_acf_field(
					$name . '_style',
					__( 'Style', 'siteorigin-corp' ),
					'select',
					array(
						'choices'       => array(
							'primary' => 'Primary (orange)',
							'ghost'   => 'Secondary (white, for dark backgrounds)',
							'outline' => 'Secondary (white, for light backgrounds)',
						),
						'default_value' => 'primary',
					)
				),
			),
		)
	);
}

/**
 * Simple repeater of a single text row.
 *
 * @param string $name  Field name.
 * @param string $label Field label.
 * @param string $sub   Sub field label.
 * @return array
 */
function irs_acf_text_repeater( $name, $label, $sub = 'Text' ) {
	return irs_acf_field(
		$name,
		$label,
		'repeater',
		array(
			'layout'       => 'table',
			'button_label' => __( 'Add row', 'siteorigin-corp' ),
			'sub_fields'   => array( irs_acf_field( $name . '_text', $sub, 'text' ) ),
		)
	);
}

/**
 * Register the global and homepage field groups.
 */
function irs_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ---------------------------------------------------------------
	 * Global content: header, footer and the banner form on every page
	 * --------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_irs_global',
			'title'    => __( 'IRS Global Content', 'siteorigin-corp' ),
			'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'irs-global' ) ) ),
			'fields'   => array(
				irs_acf_field( 'irs_tab_contact', __( 'Contact', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'phone', __( 'Phone number', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'email', __( 'Email address', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'hours', __( 'Opening hours line', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'offices_short', __( 'Offices (short)', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'topbar_note', __( 'Top bar note', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_tab_header', __( 'Header', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'header_logo', __( 'Header logo', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
				irs_acf_field( 'header_cta_label', __( 'Header button label', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'header_cta_url', __( 'Header button URL', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'mobile_cta_label', __( 'Floating mobile button label', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_tab_banner', __( 'Banner form', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'inner_banner_form', __( 'Show the enquiry form in the inner page banner', 'siteorigin-corp' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				irs_acf_field( 'banner_form_shortcode', __( 'Contact Form 7 shortcode', 'siteorigin-corp' ), 'text', array( 'instructions' => __( 'For example [contact-form-7 id="137"]. Leave blank to use the built in fallback markup.', 'siteorigin-corp' ) ) ),
				irs_acf_field( 'banner_form_heading', __( 'Form heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'banner_form_sub', __( 'Form subheading', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
				irs_acf_field( 'banner_form_note', __( 'Reassurance line under the form', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'banner_fallback_image', __( 'Fallback banner image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array', 'instructions' => __( 'Used on inner pages with no featured image.', 'siteorigin-corp' ) ) ),

				irs_acf_field( 'irs_tab_footer', __( 'Footer', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'footer_logo', __( 'Footer logo', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
				irs_acf_field( 'footer_about', __( 'Footer intro text', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3 ) ),
				irs_acf_field( 'footer_offices', __( 'Footer office addresses', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
				irs_acf_field( 'footer_menu_1_title', __( 'Footer column 2 title', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'footer_menu_2_title', __( 'Footer column 3 title', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'footer_contact_title', __( 'Footer column 4 title', 'siteorigin-corp' ), 'text' ),
				irs_acf_field(
					'social_links',
					__( 'Social links', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'     => 'table',
						'sub_fields' => array(
							irs_acf_field( 'social_network', __( 'Network', 'siteorigin-corp' ), 'select', array( 'choices' => array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn' ) ) ),
							irs_acf_field( 'social_url', __( 'URL', 'siteorigin-corp' ), 'text' ),
						),
					)
				),
				irs_acf_field( 'map_embed_url', __( 'Google Map embed URL', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'contact_form_shortcode', __( 'Contact section form shortcode', 'siteorigin-corp' ), 'text' ),
			),
		)
	);

	/* ---------------------------------------------------------------
	 * Homepage template fields
	 * --------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_irs_home',
			'title'    => __( 'IRS Homepage', 'siteorigin-corp' ),
			'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'template-home.php' ) ) ),
			'fields'   => array(
				irs_acf_field( 'irs_h_tab_hero', __( 'Banner', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'hero_background', __( 'Banner background image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
				irs_acf_field( 'hero_badge', __( 'Badge text', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'hero_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'hero_title_highlight', __( 'Heading highlight (coloured part)', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'hero_lead', __( 'Intro paragraph', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3 ) ),
				irs_acf_button_repeater( 'hero_buttons', __( 'Buttons', 'siteorigin-corp' ) ),
				irs_acf_text_repeater( 'hero_points', __( 'Trust points', 'siteorigin-corp' ), __( 'Point', 'siteorigin-corp' ) ),

				irs_acf_field( 'irs_h_tab_stats', __( 'Stats', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field(
					'stats',
					__( 'Stats', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'     => 'table',
						'sub_fields' => array(
							irs_acf_field( 'stats_number', __( 'Figure', 'siteorigin-corp' ), 'text', array( 'instructions' => __( 'Digits animate on scroll. A suffix such as + is kept.', 'siteorigin-corp' ) ) ),
							irs_acf_field( 'stats_label', __( 'Label', 'siteorigin-corp' ), 'text' ),
						),
					)
				),

				irs_acf_field( 'irs_h_tab_services', __( 'Services', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'services_eyebrow', __( 'Eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'services_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'services_intro', __( 'Intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3 ) ),
				irs_acf_field(
					'services',
					__( 'Service cards', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add service', 'siteorigin-corp' ),
						'instructions' => __( 'Ten cards fill two even rows of five on desktop.', 'siteorigin-corp' ),
						'sub_fields'   => array(
							irs_acf_field( 'services_image', __( 'Image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
							irs_acf_field( 'services_tag', __( 'Category chip', 'siteorigin-corp' ), 'text' ),
							irs_acf_field( 'services_title_row', __( 'Title', 'siteorigin-corp' ), 'text', array( 'name' => 'title' ) ),
							irs_acf_field( 'services_text', __( 'Short description', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3, 'name' => 'text' ) ),
							irs_acf_field( 'services_url', __( 'Link', 'siteorigin-corp' ), 'text', array( 'name' => 'url' ) ),
						),
					)
				),
				irs_acf_field( 'services_cta_text', __( 'Closing CTA line', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'services_cta_label', __( 'Closing CTA button label', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_h_tab_tabs', __( 'Core services', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'tabs_eyebrow', __( 'Eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'tabs_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'tabs_intro', __( 'Intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3 ) ),
				irs_acf_field(
					'tabs',
					__( 'Tabs', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add tab', 'siteorigin-corp' ),
						'sub_fields'   => array(
							irs_acf_field( 'tabs_label', __( 'Tab label', 'siteorigin-corp' ), 'text', array( 'name' => 'label' ) ),
							irs_acf_field( 'tabs_image', __( 'Image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array', 'name' => 'image' ) ),
							irs_acf_field( 'tabs_heading', __( 'Heading', 'siteorigin-corp' ), 'text', array( 'name' => 'heading' ) ),
							irs_acf_field( 'tabs_text', __( 'Body', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3, 'name' => 'text' ) ),
							irs_acf_field(
								'tabs_points',
								__( 'Checklist', 'siteorigin-corp' ),
								'repeater',
								array(
									'name'       => 'points',
									'layout'     => 'table',
									'sub_fields' => array( irs_acf_field( 'tabs_points_text', __( 'Point', 'siteorigin-corp' ), 'text', array( 'name' => 'text' ) ) ),
								)
							),
							irs_acf_field( 'tabs_cta_label_row', __( 'Button label', 'siteorigin-corp' ), 'text', array( 'name' => 'cta_label' ) ),
							irs_acf_field( 'tabs_cta_url_row', __( 'Button URL', 'siteorigin-corp' ), 'text', array( 'name' => 'cta_url' ) ),
						),
					)
				),
				irs_acf_field( 'tabs_cta_text', __( 'Closing CTA line', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'tabs_cta_label', __( 'Closing CTA button label', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_h_tab_why', __( 'Why choose us', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'why_background', __( 'Background image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
				irs_acf_field( 'why_eyebrow', __( 'Eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'why_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'why_intro', __( 'Intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
				irs_acf_field(
					'steps',
					__( 'Process steps', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'     => 'block',
						'sub_fields' => array(
							irs_acf_field( 'steps_icon', __( 'Icon', 'siteorigin-corp' ), 'select', array( 'name' => 'icon', 'choices' => array( 'experience' => 'Experience', 'find' => 'Find', 'verify' => 'Verify', 'report' => 'Report' ) ) ),
							irs_acf_field( 'steps_title', __( 'Title', 'siteorigin-corp' ), 'text', array( 'name' => 'title' ) ),
							irs_acf_field( 'steps_text', __( 'Text', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3, 'name' => 'text' ) ),
						),
					)
				),
				irs_acf_field( 'why_cta_text', __( 'Closing CTA line', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'why_cta_label', __( 'Closing CTA button label', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_h_tab_about', __( 'About skiptracing', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'about_eyebrow', __( 'Eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'about_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'about_body', __( 'Body', 'siteorigin-corp' ), 'textarea', array( 'rows' => 5 ) ),
				irs_acf_text_repeater( 'about_points', __( 'Checklist', 'siteorigin-corp' ), __( 'Point', 'siteorigin-corp' ) ),
				irs_acf_button_repeater( 'about_buttons', __( 'Buttons', 'siteorigin-corp' ) ),
				irs_acf_field( 'about_image', __( 'Image', 'siteorigin-corp' ), 'image', array( 'return_format' => 'array' ) ),
				irs_acf_field( 'about_badge_number', __( 'Badge figure', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'about_badge_label', __( 'Badge label', 'siteorigin-corp' ), 'text' ),

				irs_acf_field( 'irs_h_tab_faq', __( 'FAQ', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'faq_eyebrow', __( 'Eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'faq_title', __( 'Heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'faq_intro', __( 'Intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 3 ) ),
				irs_acf_field( 'faq_cta_label', __( 'Button label', 'siteorigin-corp' ), 'text' ),
				irs_acf_field(
					'faqs',
					__( 'Questions', 'siteorigin-corp' ),
					'repeater',
					array(
						'layout'     => 'block',
						'sub_fields' => array(
							irs_acf_field( 'faqs_question', __( 'Question', 'siteorigin-corp' ), 'text', array( 'name' => 'question' ) ),
							irs_acf_field( 'faqs_answer', __( 'Answer', 'siteorigin-corp' ), 'textarea', array( 'rows' => 4, 'name' => 'answer' ) ),
						),
					)
				),

				irs_acf_field( 'irs_h_tab_band', __( 'CTA band and contact', 'siteorigin-corp' ), 'tab' ),
				irs_acf_field( 'band_title', __( 'CTA band heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'band_text', __( 'CTA band text', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'contact_eyebrow', __( 'Contact eyebrow', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'contact_title', __( 'Contact heading', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'contact_intro', __( 'Contact intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
				irs_acf_field( 'contact_info_intro', __( 'Contact info paragraph', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
			),
		)
	);

	/* Per page control for the inner banner form. */
	acf_add_local_field_group(
		array(
			'key'      => 'group_irs_page',
			'title'    => __( 'IRS Banner', 'siteorigin-corp' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ) ),
			'fields'   => array(
				irs_acf_field( 'banner_title', __( 'Banner heading override', 'siteorigin-corp' ), 'text' ),
				irs_acf_field( 'banner_text', __( 'Banner intro', 'siteorigin-corp' ), 'textarea', array( 'rows' => 2 ) ),
				irs_acf_field( 'hide_banner_form', __( 'Hide the enquiry form in this page banner', 'siteorigin-corp' ), 'true_false', array( 'ui' => 1 ) ),
			),
		)
	);
}
add_action( 'acf/init', 'irs_register_acf_fields' );
