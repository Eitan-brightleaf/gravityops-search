<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: GravityOps Search - Search and Display Gravity Forms Entries
 * Description: A shortcode to search and display Gravity Forms entries based on specified criteria and attributes.
 * Version: 1.0.5
 * Author: BrightLeaf Digital
 * Author URI: https://brightleafdigital.io/
 * Plugin URI: https://brightleafdigital.io/gravityops-search/
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
use GravityOps\Core\SuiteCore\SuiteCore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GRAVITYOPS_SEARCH_BASENAME', plugin_basename( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';

if ( file_exists( __DIR__ . '/vendor/GOS/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/GOS/autoload.php';
}

// Register this plugin with SuiteCore early so the latest provider can be selected.
add_action(
    'plugins_loaded',
    function () {
        $assets_base_url = '';
        if ( file_exists( __DIR__ . '/vendor/GOS/gravityops/core/assets/' ) ) {
            $assets_base_url = plugins_url( 'vendor/GOS/gravityops/core/assets/', __FILE__ );
        } else {
            $assets_base_url = plugins_url( 'vendor/gravityops/core/assets/', __FILE__ );
        }

	    SuiteCore::register(
            GRAVITYOPS_SEARCH_BASENAME,
            [
                'assets_base_url' => $assets_base_url,
            ]
        );
    },
    1
);

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
	    GFForms::include_addon_framework();
        require_once 'includes/class-gravityops-search.php';
        GFAddOn::register( 'GravityOps_Search' );
    },
    5
);
