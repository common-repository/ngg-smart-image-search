<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://r-fotos.de/wordpress-plugins
 * @since             1.0.0
 * @package           NGG_Smart_Image_Search
 *
 * @wordpress-plugin
 * Plugin Name:       NGG Smart Image Search
 * Plugin URI:        https://r-fotos.de/wordpress-plugins/ngg-smart-image-search
 * Description:       This plugin provides a customizable smart image search and display function for images in NextGEN galleries.
 * Version:           3.2.1
 * Requires at least: 5.5.4 
 * Requires PHP: 			7.0
 * Author:            Harald R&ouml;h
 * Author URI:        https://r-fotos.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ngg-smart-image-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// First check if is_plugin_active() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';        
}

// check that beta version of base plugin is deactivated before activating this version
if ( is_plugin_active( 'ngg-smart-image-search-beta/ngg-smart-image-search.php') ) {
  			$hr_SIS_warning = '<div class="notice notice-warning is-dismissible"><p>';
       	$hr_SIS_warning.= '<b>' . __( 'Error:', 'ngg-smart-image-search' ) . ' </b>';
       	$hr_SIS_warning.=  __( 'Plugin <i>NGG Smart Image Search Beta</i> must be deactivated first before activating its productive version again.', 'ngg-smart-image-search' );
       	$hr_SIS_warning.= '<br /></p></div>';
       	echo $hr_SIS_warning;
       	exit();
    }


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ngg-smart-image-search-activator.php
 */
function activate_ngg_smart_image_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ngg-smart-image-search-activator.php';
	NGG_Smart_Image_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ngg-smart-image-search-deactivator.php
 */
function deactivate_ngg_smart_image_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ngg-smart-image-search-deactivator.php';
	NGG_Smart_Image_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ngg_smart_image_search' );
register_deactivation_hook( __FILE__, 'deactivate_ngg_smart_image_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ngg-smart-image-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ngg_smart_image_search() {

	$plugin = new NGG_Smart_Image_Search();
	$plugin->run();

}
run_ngg_smart_image_search();
