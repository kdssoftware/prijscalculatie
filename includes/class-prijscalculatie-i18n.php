<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/snakehead007/prijscalculatie
 * @since      1.0.0
 *
 * @package    TPX prijscalculatie
 * @subpackage tpx-prijscalculatie/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    TPX prijscalculatie
 * @subpackage tpx-prijscalculatie/includes
 * @author     Karel De Smet <snakehead007@pm.me>
 */
class prijscalculatie_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'prijscalculatie',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
