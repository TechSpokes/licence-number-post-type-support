<?php
/**
 * Licence Number Post Type Support by TechSpokes Inc.
 *
 * @package     TechSpokes\LicenceNumberPostTypeSupport
 * @author      TechSpokes Inc.
 * @copyright   2022 TechSpokes Inc. https://techspokes.com
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: Licence Number Post Type Support by TechSpokes Inc.
 * Plugin URI:  https://github.com/TechSpokes/licence-number-post-type-support.git?utm_source=wordpress&utm_medium=plugin&utm_campaign=licence-number-post-type-support&utm_content=plugin-link
 * Description: Adds post type support "licence-number" to WordPress.
 * Version:     0.0.2
 * Author:      TechSpokes Inc.
 * Author URI:  https://techspokes.com?utm_source=wordpress&utm_medium=plugin&utm_campaign=licence-number-post-type-support&utm_content=author-link
 * Text Domain: licence-number-post-type-support
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

// do not load this file directly
defined( 'ABSPATH' ) or die( sprintf( 'Please do not load %s directly', __FILE__ ) );

// load namespace
require_once( dirname( __FILE__ ) . '/autoload.php' );

// load plugin text domain
add_action( 'plugins_loaded', function () {

	load_plugin_textdomain(
		'licence-number-post-type-support',
		false,
		basename( dirname( __FILE__ ) ) . '/languages'
	);
}, 10, 0 );

// load the plugin
add_action( 'plugins_loaded', array( 'TechSpokes\LicenceNumberPostTypeSupport\Core', 'getInstance' ), 10, 0 );

