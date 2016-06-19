<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Project_Donations
 * @subpackage Project_Donations/includes
 * @author     Your Name <email@example.com>
 */
class Project_Donations {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Project_Donations_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $Project_Donations    The string used to uniquely identify this plugin.
	 */
	protected $Project_Donations;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->Project_Donations = 'project-donations';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Project_Donations_Loader. Orchestrates the hooks of the plugin.
	 * - Project_Donations_i18n. Defines internationalization functionality.
	 * - Project_Donations_Admin. Defines all hooks for the admin area.
	 * - Project_Donations_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-loader.php';

		/**
		 * Project Donation Classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-process.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-project.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-donation.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-campaign.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-project-donations-i18n.php';

		/**
		 * Custom meta box 2 class for metaboxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/vendor/CMB2/init.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-project-donations-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-project-donations-metaboxes.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-project-donations-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-project-donations-shortcodes.php';


		$this->loader = new Project_Donations_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Project_Donations_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Project_Donations_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Project_Donations_Admin( $this->get_Project_Donations(), $this->get_version() );
		$metaboxes = new Project_Donations_Metaboxes( $this->get_Project_Donations(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'projects_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'donations_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'campaign_taxonomy' );

		$this->loader->add_action( 'admin_init', $metaboxes, 'init' );
		$this->loader->add_action( 'admin_menu', $metaboxes, 'add_options_page' );
		$this->loader->add_action( 'cmb2_admin_init', $metaboxes, 'add_options_page_metabox' );

		$this->loader->add_action( 'cmb2_admin_init', $metaboxes, 'projects_metabox' );
		$this->loader->add_action( 'cmb2_render_transaction_details', $metaboxes, 'transaction_details', 10, 5 );
		$this->loader->add_filter( 'cmb2_sanitize_transaction_details', $metaboxes, 'sm_cmb2_sanitize_text_number', 10, 2 );

		$this->loader->add_action( 'cmb2_admin_init', $metaboxes, 'donations_metabox' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Project_Donations_Public( $this->get_Project_Donations(), $this->get_version() );
		$shortcodes = new Project_Donations_Shortcodes( $this->get_Project_Donations(), $this->get_version() );
		$payments = new Project_Donations_Process_Donation( $this->get_Project_Donations(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'append_donation_form' );

		//Process Payments
		$this->loader->add_action( 'rest_api_init', $payments, 'register_routes' );

		//Shortcodes
		$this->loader->add_action( 'init', $shortcodes, 'register_shortcodes' );
		/**
		 * Action instead of template tag.
		 *
		 * do_action( 'course_list' );
		 *
		 * @link 	http://nacin.com/2010/05/18/rethinking-template-tags-in-plugins/
		 */
		$this->loader->add_action( 'donation_form', $shortcodes, 'get_paypal_form' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_Project_Donations() {
		return $this->Project_Donations;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Project_Donations_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
