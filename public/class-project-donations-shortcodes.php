<?php

/**
 * WP Shortcodes
 *
 * @link       https://freshbrewedweb.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/public
 * @author     Greg Hunt <freshbrewedweb@gmail.com>
 */
class Project_Donations_Shortcodes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Project_Donations    The ID of this plugin.
	 */
	private $Project_Donations;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $option_key = 'wppd_options';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $Project_Donations       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Project_Donations, $version ) {

		$this->Project_Donations = $Project_Donations;
		$this->version = $version;

	}

	/**
	 * Get Project Donation option from the options table
	 *
	 * @return [type] [description]
	 */
	private function option( $key ) {
		$option = get_option( $this->option_key );
		return $option[$key];
	} // register_shortcodes()

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {
		add_shortcode( 'donation_form', array( $this, 'donation_form_shortcode' ) );
	} // register_shortcodes()


	/**
	 * Donation Form Shortcode
	 */
	 public function donation_form_shortcode( $atts ) {
		 if( !isset($atts['project_id']) && get_post_type('projects') ) {
			 $project_id = get_the_ID();
		 } elseif( isset($atts['project_id']) ) {
			 $project_id = $atts['project_id'];
		 }
		 $form = $this->get_paypal_form( $project_id );
		 return $form;
	 }

	/**
	 * Create Paypal Express Form
	 */
	 public function get_paypal_form( $project_id ) {
		 $project = new Project($project_id);

		 $html = '';

		 $paypal_email = $this->option('paypal_email');
		 $meta = get_post_meta($project->ID);

     $html .= '
       <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
         <input type="hidden" name="cmd" value="_xclick">
         <input type="hidden" name="business" value="'. $paypal_email .'">
         <input type="hidden" name="item_name" value="'. $project->getName() .'">
         <input type="hidden" name="item_number" value="'. $project->getID() .'">
         <input type="hidden" name="quantity" value="1">
         <input type="hidden" name="no_note" value="1">
         <input type="hidden" name="notify_url" value="https://valhallamovement.com/projects/sustainability-learning-center/ipn/index.php">
         <input type="hidden" name="currency_code" value="USD">
     ';

		 $html .= '<div class-="form-group">';

		 if( $project->getDonationType() == "monthly" ) {
			 $html .= '<input type="hidden" name="cmd" value="_xclick-subscriptions">';
		 }

		 if( $project->getDonationAmount() ) {
			 $html .= '<input type="hidden" name="amount" value="' . $project->getDonationAmount() . '">';
			 $donate_text = ' ' . money_format('%.2n', $project->getDonationAmount() );
		 } else {
			 $html .= '<input type="number" class="form-control" name="amount">';
			 $donate_text = '';
		 }

     $html .= '<button type="submit" name="submit" class="btn btn-primary">Donate'.$donate_text.'</button>';

		 $html .= '</div>';

     $html .= '</form>';

		 return $html;

	 }

}
