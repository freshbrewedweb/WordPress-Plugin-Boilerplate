<?php
/**
 * Project instances.
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

    $this->post = get_post($project_id);
    $this->id = $this->post->ID;
    $this->name = $this->post->post_title;
    $this->slug = $this->post->post_name;
    $this->key = 'wppd_project_';

	}

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

}
