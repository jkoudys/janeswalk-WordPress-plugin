<?php
/**
 * Embedded Jane's Walks
 *
 * Fetches Jane's Walk data directly from Jane's Walk's site, so
 * what you show on your blog is always up to date with what's on
 * our servers. The best way to avoid dates + meeting places going
 * out of sync between your blog and the walk, or host your own
 * site listing walks with your own theme.
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou Software 
 *
 * @wordpress-plugin
 * Plugin Name:       janeswalk 
 * Plugin URI:        http://janeswalk.org
 * Description:       Fetch remote Jane's Walks 
 * Version:           1.0.0
 * Author:            Joshua Koudys
 * Author URI:        http://qaribou.com
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/jkoudys/janeswalk-WordPress-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-janeswalk.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'JanesWalk', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'JanesWalk', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'JanesWalk', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-janeswalk-admin.php' );
	add_action( 'plugins_loaded', array( 'Plugin_Name_Admin', 'get_instance' ) );

}
