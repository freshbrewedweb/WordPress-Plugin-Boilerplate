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
		 if( !isset($atts['project_id']) && get_post_type() == 'projects' ) {
			 global $post;
			 $project_id = $post->ID;
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
		 return $project->getPaypalForm();
	 }

}
