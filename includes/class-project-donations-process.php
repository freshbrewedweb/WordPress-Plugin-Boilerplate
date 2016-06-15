<?php

use wadeshuler\paypalipn\IpnListener;

class Project_Donations_Process_Donation
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
	private $option_key;

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
    $this->prefix = 'wppd';
		$this->option_key = $this->prefix . '_options';

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
   * Register Routes as Endpoints for APIs
   * @return [type] [description]
   */
  public function register_routes() {
    register_rest_route( $this->Project_Donations, 'paypal', array(
        'methods' => 'POST',
        'callback' => array($this,'paypal')
      )
    );

    register_rest_route( $this->Project_Donations, 'stripe', array(
        'methods' => 'POST',
        'callback' => array($this,'stripe')
      )
    );
  }

  /**
   * Process Paypal IPN
   * @return [type] [description]
   */
  public function paypal() {

    $errors = array();
    $listener = new IpnListener();

    $logTime = date('Y-m-d H:i:s', time());

    if( $this->option('sandbox') == "on" )
      $listener->use_sandbox = true;

    if( isset($_POST['payment_status']) && $_POST['payment_status'] !== "Completed" )
      $errors[$logTime][] = "Payment not completed.";

    if( isset($_POST['receiver_email']) && $_POST['receiver_email'] !== $this->option('paypal_email') )
      $errors[$logTime][] = "Source ({$_POST['receiver_email']}) is not " . $this->option('paypal_email');

    // Valid IPN
    if ( empty($errors) && $verified = $listener->processIpn() && isset($_POST['txn_id']) ) {

      $transactionRawData = $listener->getRawPostData();      // raw data from PHP input stream
      $transactionData = $listener->getPostData();            // POST data array
	  $content = array();
	  foreach( $transactionData as $data ) {
		  $item = explode("=", $data);
		  $content[$item[0]] = $item[1];
	  }
	  $post = wp_insert_post( array(
	    'post_title' => $_POST['txn_id'] . '(' . $_POST['payer_email'] . ' to ' . $_POST['item_name'] . ')' ,
	    'post_content' => json_encode($content),
	    'post_type' => 'donations',
	    'post_status' => 'publish'
	  ) );

      if( $post ) {

        $donation = new Donation($post->ID);
        $donation->setAmount($_POST['mc_gross']);
        $donation->setProject($_POST['item_number']);

        // //Add to mailchimp list
        // $mail = new Mailchimp('9c4d1330f441bd2bdf5b0e496e4a4425-us9');
        // $lists = new Mailchimp_Lists( $mail );
        // $list_id = '5e9cf25115';
        //
        // $subscriber = $lists->subscribe( $list_id, array(
        //   'email' => htmlentities($donation['email']),
        // ));
        //
        // if( !empty( $subscriber['leid'] ) ) {
        //   $donation["subscribed_to_mail"] = 1;
        // } else {
        //   $donation["subscribed_to_mail"] = NULL;
        // }

        file_put_contents(plugin_dir_path( __FILE__ ) . 'logs/success.log', print_r(array($logTime, $transactionData), TRUE) . PHP_EOL, LOCK_EX | FILE_APPEND);

        return TRUE;

      } else {
        $errors[$logtime][] = "Failed to save donation (Transaction ID: {$_POST['txn_id']}).";
      }


    } else {
        // Invalid IPN
        $errors[$logTime][] = "Invalid IPN: " . $listener->getErrors();
    }

    if( count($errors) > 0 ) {
      file_put_contents(plugin_dir_path( __FILE__ ) . 'logs/errors.log', print_r($errors, TRUE) . PHP_EOL, LOCK_EX | FILE_APPEND);

	  return $errors;

    }
  }

  /**
   * Process Stripe payment
   * @return [type] [description]
   */
  public function stripe() {
    return "Process stripe";
  }


}
