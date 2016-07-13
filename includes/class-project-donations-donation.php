<?php
/**
 * Donation Object
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/Project
 */
class Donation
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
	public function __construct( $donation_id ) {

    $this->post = get_post($donation_id);
    $this->id = $this->post->ID;
    $this->name = $this->post->post_title;
    $this->slug = $this->post->post_name;
    $this->key = 'wppd_donation_';

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
  public function getAmount() {
    if( $this->meta('amount') ) {
      return $this->meta('amount');
    } else {
      return false;
    }
  }

  /**
   * Get Donation Amount
   * @return str
   */
  public function getTransaction() {
    return json_decode($this->post->post_content, TRUE);
  }

  /**
   * Get Donation Type
   * @return str
   */
  public function getProjects() {
    if( $this->meta('project') ) {
      return $this->meta('project');
    } else {
      return false;
    }
  }

  /**
   * Update donation post meta
   * @return str
   */
  private function updateMeta( $name, $value ) {
    if ( ! add_post_meta( $this->ID, $this->key . $name, $value, true ) ) {
       return update_post_meta( $this->ID, $this->key . $name, $value );
    }

    return false;
  }

  /**
   * Set Donation Amount in post meta
   */
  public function setAmount( $amount ) {
    if( $this->updateMeta('amount', $amount) )
      return $amount;
  }

  /**
   * Set the associated project ID
   */
  public function setProject( $project_id ) {
    if( $this->updateMeta('project', $project_id) )
      return $project_id;
  }

}
