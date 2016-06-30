<?php
/**
 * Project Object
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/Project
 */
class Project
{
  /**
   * The project ID
   * @var int
   */
  protected $Project_Donations;

  /**
   * The project ID
   * @var int
   */
  private $id;

  /**
   * The WP post oject
   * @var WP_Post
   */
  private $post;

  /**
   * The Project Name
   * @var str
   */
  private $name;

  /**
   * The Project Slug
   * @var str
   */
  private $slug;

  /**
   * Key for retrieving post meta
   * @var str
   */
  private $key;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $Project_Donations       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $project_id ) {

    $this->Project_Donations = 'project-donations';
    $this->post = get_post($project_id);
    $this->id = $this->post->ID;
    $this->name = $this->post->post_title;
    $this->slug = $this->post->post_name;
    $this->key = 'wppd_project_';

	}

  /**
	 * Get Project Donation option from the options table
	 *
	 * @return [type] [description]
	 */
	private function option( $key ) {
		$option = get_option( $this->key );
		return $option[$key];
	} // register_shortcodes()

  /**
   * Get the project post meta
   * @param  [type] $name [description]
   * @return [type]       [description]
   */
  private function meta( $name ) {
    return get_post_meta( $this->id, $this->key . $name, TRUE );
  }

  /**
   * Get ID of Project
   * @return int
   */
  public function getID() {
    return $this->id;
  }

  /**
   * Get Name of Project
   * @return str
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get Donation Amount
   * @return str
   */
  public function getDonationAmount() {
    if( $this->meta('donation_amount') ) {
      return $this->meta('donation_amount');
    } else {
      return false;
    }
  }

  /**
   * Get Donation Type
   * @return str
   */
  public function getDonationType() {
    return $this->meta('donation_type');
  }

  /**
   * Get Description
   * @return str
   */
  public function getDescription() {
    return apply_filters('the_content', $this->post->post_content);
  }

  /**
   * Get Short Description
   * @return str
   */
  public function getShortDescription() {
    return $this->post->post_excerpt;
  }

  /**
   * Get Donation Type
   * @return str
   */
  public function updateMeta() {
    return $this->meta('donation_type');
  }

  /**
   * Output Paypal Form HTML
   * @return [type] [description]
   */
  public function getPaypalForm( $args = [] ) {
    $html = '';
    $html .= '
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="'. $this->option('paypal_email') .'">
        <input type="hidden" name="item_name" value="'. $this->name .'">
        <input type="hidden" name="item_number" value="'. $this->id .'">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="notify_url" value="'. get_home_url() .'/wp-json/project-donations/paypal">
        <input type="hidden" name="currency_code" value="USD">
    ';

    foreach( $args as $key => $val ) {
      $html .= '<input type="hidden" name="'.$key.'" value="'.$val.'">';
    }

    if( $this->getDonationType() == "monthly" ) {
      $html .= '<input type="hidden" name="cmd" value="_xclick-subscriptions">';
    } else {
      $html .= '<input type="hidden" name="cmd" value="_xclick">';
    }

    $html .= '<div class-="form-group">';

    if( $this->getDonationAmount() ) {
      $html .= '<input type="hidden" name="amount" value="' . $this->getDonationAmount() . '">';
      $donate_text = ' ' . money_format('%.2n', $this->getDonationAmount() );
    } else {
      $html .= '<input type="number" class="form-control" name="amount">';
      $donate_text = '';
    }

    $html .= '<button type="submit" name="submit" class="btn btn-primary">' . __('Donate', $this->Project_Donations) . $donate_text.'</button>';

    $html .= '</div>';

    $html .= '</form>';

    return $html;
  }


}
