<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/snakehead007/prijscalculatie
 * @since      1.0.0
 *
 * @package    TPX prijscalculatie
 * @subpackage tpx-prijscalculatie/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TPX prijscalculatie
 * @subpackage tpx-prijscalculatie/includes
 * @author     Karel De Smet <snakehead007@pm.me>
 */
class prijscalculatie_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$items = $wpdb->get_results("SELECT * FROM wp_tpx-prijscalculatie_items LIMIT 1",ARRAY_A);
		if(count($items)==0){
			$wpdb->get_results('CREATE TABLE `exampledb`.`wp_tpx-prijscalculatie_items` ( `ID` INT NOT NULL AUTO_INCREMENT , `naam` TEXT NOT NULL , `winkelprijs_pp` DOUBLE NOT NULL , `winstmarge` DOUBLE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;');
		}
		$workshops = $wpdb->get_results("SELECT * FROM wp_tpx-prijscalculatie_workshops LIMIT 1",ARRAY_A);
		if(count($workshops)==0){
			$wpdb->get_results('CREATE TABLE `exampledb`.`wp_tpx-prijscalculatie_workshops` ( `ID` INT NOT NULL AUTO_INCREMENT , `naam` TEXT NOT NULL , `min_prijs` DOUBLE NOT NULL , `prijs_pp` DOUBLE NOT NULL ,`winstmarge` DOUBLE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;');
		}
		$packages = $wpdb->get_results("SELECT * FROM wp_tpx-prijscalculatie_packages LIMIT 1",ARRAY_A);
		if(count($packages)==0){
			$wpdb->get_results('CREATE TABLE `exampledb`.`wp_tpx-prijscalculatie_packages` ( `ID` INT NOT NULL AUTO_INCREMENT , `naam` TEXT NOT NULL , `active` BOOLEAN DEFAULT NULL , `items` TEXT, PRIMARY KEY (`ID`)) ENGINE = InnoDB;');
		}
	}

}
