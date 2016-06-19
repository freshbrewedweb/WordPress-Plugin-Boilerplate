<?php
/**
 * Campaign Object
 *
 * @link       https://freshbrewedweb.com
 * @since      1.0.0
 *
 * @package    Project_Donations
 * @subpackage Project_Donations/Project
 */
class Campaign
{
  /**
   * The project ID
   * @var int
   */
  private $id;

  /**
   * The post type
   * @var WP_Post
   */
  private $taxonomy;

  /**
   * The post type
   * @var WP_Post
   */
  private $term;

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
   * The Project Slug
   * @var str
   */
  private $donations = array();

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $Project_Donations       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $campaign_id ) {

    $this->taxonomy = 'campaign';
    $this->term = get_term($campaign_id, $this->taxonomy);
    $this->id = $this->term->term_id;
    $this->name = $this->term->name;
    $this->slug = $this->term->slug;

	}

  /**
   * Get Campaign Name
   * @return str name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get Campaign Name
   * @return str name
   */
  public function getWPTerm() {
    return $this->term;
  }

  /**
   * Get All Projects under this campaign
   * @return array of WP_Post of post type projects
   */
  public function getProjects( $active = TRUE ) {
    $args = array(
      'post_type' => 'projects',
      'orderby' => 'menu_order',
      'order' => 'ASC',
      'tax_query' => array(
      		array(
      			'taxonomy' => $this->taxonomy,
      			'field'    => 'id',
      			'terms'    => $this->id,
      		),
      	),
      'posts_per_page' => -1,
    );

    if( !$active )
      $args['post_status'] = 'any';

    $projects = new WP_Query( $args );

    return $projects->get_posts();
  }

  /**
   * Get Campaign Name
   * @return str name
   */
  public function donations() {
    global $wpdb;

    foreach( $this->getProjects( FALSE ) as $project ) {
      $donations = $wpdb->get_results(
        'SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = "wppd_donation_project" AND meta_value = ' . $project->ID,
        OBJECT
      );
      if( !empty($donations) ) {
        foreach( $donations as $donation ) {
          $donation = get_post_meta($donation->post_id, 'wppd_donation_amount', TRUE);
          $this->donations[] = $donation;
        }
      }
    }

    return $this->donations;
  }


}
