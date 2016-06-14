<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://freshbrewedweb.com
 * @since             0.1.0
 * @package           Project_Donations
 *
 * @wordpress-plugin
 * Plugin Name:       Project Donations
 * Plugin URI:        https://freshbrewedweb.com
 * Description:       A WordPress plugin to create flexible projects and fund them through Paypal donations.
 * Version:           0.1.0
 * Author:            Fresh Brewed Web
 * Author URI:        https://freshbrewedweb.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       project-donations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-project-donations-activator.php
 */
function activate_Project_Donations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-project-donations-activator.php';
	Project_Donations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-project-donations-deactivator.php
 */
function deactivate_Project_Donations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-project-donations-deactivator.php';
	Project_Donations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Project_Donations' );
register_deactivation_hook( __FILE__, 'deactivate_Project_Donations' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-project-donations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Project_Donations() {

	$plugin = new Project_Donations();
	$plugin->run();

}
run_Project_Donations();
