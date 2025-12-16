<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: GravityOps Search - Search and Display Gravity Forms Entries
 * Description: A shortcode to search and display Gravity Forms entries based on specified criteria and attributes.
 * Version: 1.0.2
 * Author: BrightLeaf Digital
 * Author URI: https://brightleafdigital.io/
 * Plugin URI: https://brightleafdigital.io/gravityops-search/
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
    'admin_notices',
    function () {
        if ( is_admin() && current_user_can( 'activate_plugins' ) && ! class_exists( 'GFForms' ) ) {
            echo '<div class="notice notice-warning"><p>GravityOps Search requires Gravity Forms. Please install and activate Gravity Forms to use this plugin.</p></div>';
        }
    }
);


add_action(
    'gform_loaded',
    function () {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
	    require_once __DIR__ . '/vendor-prefixed/autoload.php';
	    GFForms::include_addon_framework();
        require_once 'includes/class-gravityops-search.php';
        GFAddOn::register( 'GravityOps_Search' );
    },
    5
);
