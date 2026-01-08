<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: GravityOps Search - Search and Display Gravity Forms Entries
 * Description: A shortcode to search and display Gravity Forms entries based on specified criteria and attributes.
 * Version: 1.0.4
 * Author: BrightLeaf Digital
 * Author URI: https://brightleafdigital.io/
 * Plugin URI: https://brightleafdigital.io/gravityops-search/
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
use function GravityOps\Core\Admin\gravityops_shell;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( file_exists( __DIR__ . '/vendor/GOS/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/GOS/autoload.php';
}

// Instantiate this plugin's copy of the AdminShell early so provider negotiation can happen on plugins_loaded.
add_action(
    'plugins_loaded',
    function () {
	    gravityops_shell();
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

// Ensure GravityOps shared assets resolve when library is vendor-installed in this plugin.
add_filter(
    'gravityops_assets_base_url',
    function ( $url ) {
        if ( $url ) {
            return $url;
        }

        if ( file_exists( __DIR__ . '/vendor/GOS/gravityops/core/assets/' ) ) {
            return plugins_url( 'vendor/GOS/gravityops/core/assets/', __FILE__ );
        }

        return plugins_url( 'vendor/gravityops/core/assets/', __FILE__ );
    }
);
