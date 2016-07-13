<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

class Project_Donations_Metaboxes
{
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
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $prefix;

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key;

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id;

	/**
	 * Options Page title
	 * @var string
	 */
	protected $settings_title;

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Myprefix_Admin
	 **/
	private static $instance = null;

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
		$this->settings_title = __( 'Project Donations', $this->Project_Donations );
		$this->prefix = 'wppd';
		$this->key = $this->prefix . '_options';
		$this->metabox_id = $this->key . '_mb';


	}

	/**
	 * Gets a number of posts and displays them as options
	 * @param  array $query_args Optional. Overrides defaults.
	 * @return array             An array of options that matches the CMB2 options array
	 */
	public function get_project_options( $query_args ) {

	    $args = wp_parse_args( $query_args, array(
	        'post_type'   => 'post',
	        'numberposts' => 10,
	    ) );

	    $posts = get_posts( $args );

	    $post_options = array();

			$post_options[0] = "General";

	    if ( $posts ) {
	        foreach ( $posts as $post ) {
	          $post_options[ $post->ID ] = $post->post_title;
	        }
	    }

	    return $post_options;
	}

	/**
	 * Gets 5 posts for your_post_type and displays them as options
	 * @return array An array of options that matches the CMB2 options array
	 */
	public function get_projects() {
	    return $this->get_project_options( array( 'post_type' => 'projects', 'numberposts' => 99 ) );
	}

	/**
	 * Conditionally displays a message if the $post_id is 2
	 *
	 * @param  array             $field_args Array of field parameters
	 * @param  CMB2_Field object $field      Field object
	 */
	public function transaction_details( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
			$donation = new Donation($object_id);
			$details = '<table class="form-table">';
			foreach( $donation->getTransaction() as $k => $v ) {
				$k = ucfirst(str_replace('_', ' ', $k));
				$v = urldecode($v);
				$details .= "<tr><td class=\"row-title\">$k</td>";
				$details .= "<td><em>$v</em></td></tr>";
			}
			$details .= '</table>';
			echo $details;

	}

	public function sm_cmb2_sanitize_text_number( $null, $new ) {
	    $new = preg_replace( "/[^0-9]/", "", $new );

	    return $new;
	}
	/**
	 * Hook in and add a metabox to projects. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function donations_metabox() {

		$prefix = $this->prefix . '_donation_';

		$mb = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Project', $this->Project_Donations ),
			'object_types'  => array( 'donations' ), // Post type
		) );

		$mb->add_field( array(
			'name'             => __( 'Project', $this->Project_Donations ),
			'id'               => $prefix . 'project',
			'type'             => 'radio',
			'show_option_none' => false,
			'options_cb' => array($this, 'get_projects'),
		) );

		$txn = new_cmb2_box( array(
			'id'            => $prefix . 'txn',
			'title'         => __( 'Transaction Details', $this->Project_Donations ),
			'object_types'  => array( 'donations' ), // Post type
		) );

		$txn->add_field( array(
			'name'             => __( 'Amount', $this->Project_Donations ),
			'id'               => $prefix . 'amount',
			'type'             => 'text_money',
		) );

		$txn->add_field( array(
			'name'             => __( 'Transaction Response', $this->Project_Donations ),
			'id'               => $prefix . 'response',
			'type'             => 'transaction_details',
		) );

	}

	/**
	 * Hook in and add a metabox to projects. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function projects_metabox() {

		$prefix = $this->prefix . '_project_';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$metabox = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Donations', $this->Project_Donations ),
			'object_types'  => array( 'projects' ), // Post type
		) );

		$metabox->add_field( array(
			'name'             => __( 'Amount', $this->Project_Donations ),
			'desc'						 => __( 'If no amount is specified, any amount can be given.', $this->Project_Donations ),
			'id'               => $prefix . 'donation_amount',
			'type'             => 'text_money',
		) );

		$metabox->add_field( array(
			'name'             => __( 'Type', $this->Project_Donations ),
			'id'               => $prefix . 'donation_type',
			'type'             => 'radio',
			'options' 				 => array(
				'standard' => "Standard one-time donation",
				'monthly' => "Monthly one-time donation",
			),
		) );

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$mailchimp = new_cmb2_box( array(
			'id'            => $prefix . 'mailchimp',
			'title'         => __( 'Mailchimp', $this->Project_Donations ),
			'object_types'  => array( 'projects' ), // Post type
		) );

		$mailchimp->add_field( array(
			'name'             => __( 'List ID', $this->Project_Donations ),
			'desc'						 => __( 'Enter the Mailchimp list ID emails should be added to after a donation.', $this->Project_Donations ),
			'id'               => $prefix . 'mailchimp_list_id',
			'type'             => 'text',
		) );


	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page( 'options-general.php', $this->settings_title, $this->settings_title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'Paypal Email', $this->Project_Donations ),
			'id'   => 'paypal_email',
			'type' => 'text_email',
		) );

		$cmb->add_field( array(
			'name' => __( 'Sandbox Mode', $this->Project_Donations ),
			'id'   => 'sandbox',
			'type' => 'checkbox',
		) );

		$cmb->add_field( array(
			'name' => __( 'Mailchimp API Key', $this->Project_Donations ),
			'id'   => 'mailchimp_api',
			'type' => 'text',
		) );

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'myprefix' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}


}
