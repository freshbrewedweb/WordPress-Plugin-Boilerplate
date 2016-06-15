<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/admin
 * @author     Your Name <email@example.com>
 */
class Project_Donations_Admin {

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
	 * @param      string    $Project_Donations       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Project_Donations, $version ) {

		$this->Project_Donations = $Project_Donations;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->Project_Donations, plugin_dir_url( __FILE__ ) . 'css/project-donations-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->Project_Donations, plugin_dir_url( __FILE__ ) . 'js/project-donations-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create Project custom post type
	 *
	 * @since    1.0.0
	 */
	public function projects_post_type() {

		$labels = array(
			'name'                  => _x( 'Projects', $this->Project_Donations ),
			'singular_name'         => _x( 'Project', $this->Project_Donations ),
			'menu_name'             => __( 'Projects', $this->Project_Donations ),
			'name_admin_bar'        => __( 'Project', $this->Project_Donations ),
			'archives'              => __( 'Project Archives', $this->Project_Donations ),
			'parent_item_colon'     => __( 'Parent Project:', $this->Project_Donations ),
			'all_items'             => __( 'All Projects', $this->Project_Donations ),
			'add_new_item'          => __( 'Add New Project', $this->Project_Donations ),
			'add_new'               => __( 'Add New', $this->Project_Donations ),
			'new_item'              => __( 'New Project', $this->Project_Donations ),
			'edit_item'             => __( 'Edit Project', $this->Project_Donations ),
			'update_item'           => __( 'Update Project', $this->Project_Donations ),
			'view_item'             => __( 'View Project', $this->Project_Donations ),
			'search_items'          => __( 'Search Projects', $this->Project_Donations ),
			'not_found'             => __( 'Not found', $this->Project_Donations ),
			'not_found_in_trash'    => __( 'Not found in Trash', $this->Project_Donations ),
			'featured_image'        => __( 'Featured Image', $this->Project_Donations ),
			'set_featured_image'    => __( 'Set featured image', $this->Project_Donations ),
			'remove_featured_image' => __( 'Remove featured image', $this->Project_Donations ),
			'use_featured_image'    => __( 'Use as featured image', $this->Project_Donations ),
			'insert_into_item'      => __( 'Insert into class', $this->Project_Donations ),
			'uploaded_to_this_item' => __( 'Uploaded to this class', $this->Project_Donations ),
			'items_list'            => __( 'Project', $this->Project_Donations ),
			'items_list_navigation' => __( 'Project navigation', $this->Project_Donations ),
			'filter_items_list'     => __( 'Filter classroom', $this->Project_Donations ),
		);
		$rewrite = array(
			'slug'                  => 'projects',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Project', $this->Project_Donations ),
			'description'           => __( 'Project', $this->Project_Donations ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', ),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-screenoptions',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'projects',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
		);
		register_post_type( 'projects', $args );

	}

	/**
	 * Create Donation custom post type
	 *
	 * @since    1.0.0
	 */
	public function donations_post_type() {

		$labels = array(
			'name'                  => _x( 'Donations', $this->Project_Donations ),
			'singular_name'         => _x( 'Donation', $this->Project_Donations ),
			'menu_name'             => __( 'Donations', $this->Project_Donations ),
			'name_admin_bar'        => __( 'Donation', $this->Project_Donations ),
			'archives'              => __( 'Donation Archives', $this->Project_Donations ),
			'parent_item_colon'     => __( 'Parent Donation:', $this->Project_Donations ),
			'all_items'             => __( 'All Donations', $this->Project_Donations ),
			'add_new_item'          => __( 'Add New Donation', $this->Project_Donations ),
			'add_new'               => __( 'Add New', $this->Project_Donations ),
			'new_item'              => __( 'New Donation', $this->Project_Donations ),
			'edit_item'             => __( 'Edit Donation', $this->Project_Donations ),
			'update_item'           => __( 'Update Donation', $this->Project_Donations ),
			'view_item'             => __( 'View Donation', $this->Project_Donations ),
			'search_items'          => __( 'Search Donations', $this->Project_Donations ),
			'not_found'             => __( 'Not found', $this->Project_Donations ),
			'not_found_in_trash'    => __( 'Not found in Trash', $this->Project_Donations ),
			'featured_image'        => __( 'Featured Image', $this->Project_Donations ),
			'set_featured_image'    => __( 'Set featured image', $this->Project_Donations ),
			'remove_featured_image' => __( 'Remove featured image', $this->Project_Donations ),
			'use_featured_image'    => __( 'Use as featured image', $this->Project_Donations ),
			'insert_into_item'      => __( 'Insert into donation', $this->Project_Donations ),
			'uploaded_to_this_item' => __( 'Uploaded to this donation', $this->Project_Donations ),
			'items_list'            => __( 'Donation', $this->Project_Donations ),
			'items_list_navigation' => __( 'Donation navigation', $this->Project_Donations ),
			'filter_items_list'     => __( 'Filter donations', $this->Project_Donations ),
		);
		$args = array(
			'label'                 => __( 'Donation', $this->Project_Donations ),
			'description'           => __( 'Donations', $this->Project_Donations ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'custom-fields', 'page-attributes'),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-heart',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'post',
		);
		register_post_type( 'donations', $args );

	}

	/**
	 * Create Campaign taxonomy for Projects post type
	 *
	 * @since    1.0.0
	 */
	 // Register Custom Taxonomy
	 public function campaign_taxonomy() {

	 	$labels = array(
	 		'name'                       => _x( 'Campaigns', 'Taxonomy General Name', 'wp-classroom' ),
	 		'singular_name'              => _x( 'Campaign', 'Taxonomy Singular Name', 'wp-classroom' ),
	 		'menu_name'                  => __( 'Campaign', 'wp-classroom' ),
	 		'all_items'                  => __( 'All Campaigns', 'wp-classroom' ),
	 		'parent_item'                => __( 'Parent Campaign', 'wp-classroom' ),
	 		'parent_item_colon'          => __( 'Parent Campaign:', 'wp-classroom' ),
	 		'new_item_name'              => __( 'New Campaign Name', 'wp-classroom' ),
	 		'add_new_item'               => __( 'Add New Campaign', 'wp-classroom' ),
	 		'edit_item'                  => __( 'Edit Campaign', 'wp-classroom' ),
	 		'update_item'                => __( 'Update Campaign', 'wp-classroom' ),
	 		'view_item'                  => __( 'View Campaign', 'wp-classroom' ),
	 		'separate_items_with_commas' => __( 'Separate campaigns with commas', 'wp-classroom' ),
	 		'add_or_remove_items'        => __( 'Add or remove campaigns', 'wp-classroom' ),
	 		'choose_from_most_used'      => __( 'Choose from the most used', 'wp-classroom' ),
	 		'popular_items'              => __( 'Popular Campaigns', 'wp-classroom' ),
	 		'search_items'               => __( 'Search Campaigns', 'wp-classroom' ),
	 		'not_found'                  => __( 'Not Found', 'wp-classroom' ),
	 		'no_terms'                   => __( 'No Campaigns', 'wp-classroom' ),
	 		'items_list'                 => __( 'Campaign list', 'wp-classroom' ),
	 		'items_list_navigation'      => __( 'Campaign list navigation', 'wp-classroom' ),
	 	);
		$rewrite = array(
			'slug'                       => 'campaign',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
	 	$args = array(
	 		'labels'                     => $labels,
	 		'hierarchical'               => true,
	 		'public'                     => true,
	 		'show_ui'                    => true,
	 		'show_admin_column'          => true,
	 		'show_in_nav_menus'          => true,
	 		'show_tagcloud'              => true,
	 		'rewrite'              			 => $rewrite,
	 	);
	 	register_taxonomy( 'campaign', array( 'projects' ), $args );

	 }


}
