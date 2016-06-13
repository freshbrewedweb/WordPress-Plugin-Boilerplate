<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
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
 * @author     Your Name <email@example.com>
 */
class Project_Donations_Public {

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Project_Donations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Project_Donations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Project_Donations, plugin_dir_url( __FILE__ ) . 'css/project-donations-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Project_Donations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Project_Donations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->Project_Donations, plugin_dir_url( __FILE__ ) . 'js/project-donations-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Append the donation form to project content.
	 * @return [type] [description]
	 */
	public function append_donation_form( $content ) {
		if( get_post_type() == "projects" ) {
			$form = apply_filters( 'donation_form', get_the_ID() );
			$content .= $form;
		}
		return $content;
	}

}
