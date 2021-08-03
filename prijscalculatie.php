<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/snakehead007/prijscalculatie
 * @since             1.0.0
 * @package           prijscalculatie
 *
 * @wordpress-plugin
 * Plugin Name:       Prijscalulatie Transplantouz
 * Plugin URI:        https://github.com/snakehead007/prijscalculatie
 * Description:       Provide prizing and add a page to create qoutes.
 * Version:           1.0.0
 * Author:            Karel De Smet
 * Author URI:        http://karel.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prijscalculatie
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'prijscalculatie_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-prijscalculatie-activator.php
 */
function activate_prijscalculatie() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prijscalculatie-activator.php';
	prijscalculatie_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-prijscalculatie-deactivator.php
 */
function deactivate_prijscalculatie() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prijscalculatie-deactivator.php';
	prijscalculatie_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_prijscalculatie' );
register_deactivation_hook( __FILE__, 'deactivate_prijscalculatie' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-prijscalculatie.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_prijscalculatie() {

	$plugin = new prijscalculatie();
	$plugin->run();

}
run_prijscalculatie();


function prijscalculatie_formulier(){
	
	return $content;
}

add_shortcode('prijscalculatie_formulier','prijscalculatie_formulier');