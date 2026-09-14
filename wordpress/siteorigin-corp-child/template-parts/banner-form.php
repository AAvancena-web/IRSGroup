<?php
/**
 * Shared enquiry form card.
 *
 * Rendered on the right of the homepage hero and in the inner page banner, so
 * both come from one source. Drops in a Contact Form 7 shortcode when one is
 * configured, otherwise falls back to a static markup version that matches the
 * design (useful before the form is wired up).
 *
 * @package siteorigin-corp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$irs_heading   = isset( $args['heading'] ) ? $args['heading'] : irs_global( 'banner_form_heading', 'Reach Out To Us Today' );
$irs_sub       = isset( $args['sub'] ) ? $args['sub'] : irs_global( 'banner_form_sub', 'Tell us what you need and our team will come back to you with a clear price and a plan.' );
$irs_shortcode = irs_global( 'banner_form_shortcode', '' );
$irs_reveal    = isset( $args['reveal'] ) ? $args['reveal'] : 'right';
?>
<div class="irs-form-card" data-reveal="<?php echo esc_attr( $irs_reveal ); ?>">
	<h2><?php echo esc_html( $irs_heading ); ?></h2>
	<?php if ( $irs_sub ) : ?>
		<p class="irs-form-sub"><?php echo esc_html( $irs_sub ); ?></p>
	<?php endif; ?>

	<?php if ( $irs_shortcode ) : ?>
		<div class="irs-cf7">
			<?php echo do_shortcode( $irs_shortcode ); ?>
		</div>
	<?php else : ?>
		<form class="irs-form-grid" method="post" novalidate>
			<div class="irs-field">
				<label for="irs-bf-name"><?php esc_html_e( 'Name', 'siteorigin-corp' ); ?> <span class="irs-req">*</span></label>
				<input type="text" id="irs-bf-name" name="name" placeholder="<?php esc_attr_e( 'Full name', 'siteorigin-corp' ); ?>" required>
			</div>
			<div class="irs-field">
				<label for="irs-bf-email"><?php esc_html_e( 'Email', 'siteorigin-corp' ); ?> <span class="irs-req">*</span></label>
				<input type="email" id="irs-bf-email" name="email" placeholder="you@company.com.au" required>
			</div>
			<div class="irs-field">
				<label for="irs-bf-phone"><?php esc_html_e( 'Phone', 'siteorigin-corp' ); ?> <span class="irs-req">*</span></label>
				<input type="tel" id="irs-bf-phone" name="phone" placeholder="04XX XXX XXX" required>
			</div>
			<div class="irs-field">
				<label for="irs-bf-service"><?php esc_html_e( 'Service', 'siteorigin-corp' ); ?> <span class="irs-req">*</span></label>
				<select id="irs-bf-service" name="service" required>
					<option value=""><?php esc_html_e( 'Select a service', 'siteorigin-corp' ); ?></option>
					<?php
					$irs_services = irs_global(
						'service_options',
						array(
							array( 'label' => 'Skiptracing Services' ),
							array( 'label' => 'Skiptracing Locates' ),
							array( 'label' => 'Searches' ),
							array( 'label' => 'Investigations, In Depth Locations' ),
							array( 'label' => 'International Skiptracing' ),
							array( 'label' => 'High Volume Skiptracing' ),
							array( 'label' => 'Process Serving' ),
							array( 'label' => 'Field Calls' ),
							array( 'label' => 'Repossessions' ),
							array( 'label' => 'Lockouts and Evictions' ),
							array( 'label' => 'Debt Collection' ),
						)
					);
					foreach ( (array) $irs_services as $irs_option ) {
						$irs_label = is_array( $irs_option ) ? $irs_option['label'] : $irs_option;
						echo '<option>' . esc_html( $irs_label ) . '</option>';
					}
					?>
				</select>
			</div>
			<div class="irs-field irs-field--full">
				<label for="irs-bf-msg"><?php esc_html_e( 'How can we help', 'siteorigin-corp' ); ?></label>
				<textarea id="irs-bf-msg" name="message" placeholder="<?php esc_attr_e( 'Give us a short summary of your matter', 'siteorigin-corp' ); ?>"></textarea>
			</div>
			<div class="irs-field irs-field--full">
				<label class="irs-consent">
					<input type="checkbox" name="consent" required>
					<span><?php esc_html_e( 'I agree to IRS Group contacting me about my enquiry and accept the privacy policy.', 'siteorigin-corp' ); ?></span>
				</label>
			</div>
			<div class="irs-field irs-field--full">
				<button type="submit" class="irs-btn irs-btn--primary irs-btn--block irs-btn--lg irs-btn--glow">
					<?php esc_html_e( 'Send My Enquiry', 'siteorigin-corp' ); ?>
				</button>
			</div>
		</form>
	<?php endif; ?>

	<p class="irs-form-note">
		<?php echo irs_icon( 'lock' ); ?>
		<?php echo esc_html( irs_global( 'banner_form_note', 'Confidential and obligation free' ) ); ?>
	</p>
</div>
