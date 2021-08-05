<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/snakehead007/prijscalculatie
 * @since             1.0.0
 * @package           TPX Prijscalculatie 
 *
 * @wordpress-plugin
 * Plugin Name:       TPX Prijscalculatie 
 * Plugin URI:        https://wordpress.org/plugins/TPX-prijscalculatie
 * Description:       Provide prizing and add a page to create qoutes.
 * Version:           1.0.0
 * Author:            Karel De Smet
 * Author URI:        http://karel.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       TPX Prijscalculatie 
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
	global $wpdb;
	$items = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_items",ARRAY_A);
	$workshops = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_workshops",ARRAY_A);
	$content = "<div id='TPX_prijscalculatie'></div><script>let items=".json_encode($items).";let workshops=".json_encode($workshops).";prijscalculatie_init();</script>";
	return $content;
}

function prijzentabel(){
	add_menu_page("Prijzentabel aanpassen","TPX Prijzentabel","manage_options","prijzentabel","prijzentabel_page","",200);
}

function prijzentabel_page(){
	global $wpdb;

	if( isset( $_POST['prijzentabel_item_add_submit'] ) ) {
		$wpdb->insert("wp_items",array(
			"naam"=>$_POST["naam"],
			"winkelprijs_pp" =>$_POST["winkelprijs_pp"],
			"winstmarge"=>$_POST["winstmarge"]
		));
	}else if( isset( $_POST['prijzentabel_workshop_add_submit'] ) ) {
		$wpdb->insert("wp_workshops",array(
			"naam"=>$_POST["naam"],
			"min_prijs" =>$_POST["min_prijs"],
			"prijs_pp"=>$_POST["prijs_pp"],
			"winstmarge"=>$_POST["winstmarge"]
		));
	}else if(isset( $_POST['prijzentabel_item_delete_submit'] )){
		$wpdb->delete("wp_items",array(
			"ID"=>$_POST["ID"]
		));
	}else if(isset( $_POST['prijzentabel_item_edit_submit'] )){
		
	}else if(isset( $_POST['prijzentabel_workshop_delete_submit'] )){
		$wpdb->delete("wp_workshops",array(
			"ID"=>$_POST["ID"]
		));
	}else if(isset( $_POST['prijzentabel_workshop_edit_submit'] )){
		
	}
	$items = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_items",ARRAY_A);
	if(count($items)==0){
		$wpdb->get_results('CREATE TABLE `exampledb`.`wp_TPX_prijscalculatie_items` ( `ID` INT NOT NULL AUTO_INCREMENT , `naam` TEXT NOT NULL , `winkelprijs_pp` DOUBLE NOT NULL , `winstmarge` DOUBLE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;');
		$items = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_items",ARRAY_A);
	}
	$workshops = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_workshops",ARRAY_A);
	if(count($workshops)==0){
		$wpdb->get_results('CREATE TABLE `exampledb`.`wp_TPX_prijscalculatie_workshops` ( `ID` INT NOT NULL AUTO_INCREMENT , `naam` TEXT NOT NULL , `min_prijs` DOUBLE NOT NULL , `prijs_pp` DOUBLE NOT NULL ,`winstmarge` DOUBLE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;');
		$workshops = $wpdb->get_results("SELECT * FROM wp_TPX_prijscalculatie_workshops",ARRAY_A);
	}
	
	?>
		<h1>Activities</h1>
		<table style="width:100%;text-align:center">
			<thead style="background-color:#000000;color:#ffffff;font-weight:500">
			<tr>
				<th>Code</th>
				<th>Naam</th>
				<th>Winkelprijs/pp</th>
				<th>Winstmarge</th>
				<th>Dienstprijs</th>
				<th>Sponsorbijdrage</th>
				<th>Edit</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<form action="" method="post">
					<td>
						/
					</td>
					<td>
						<input style="width=100%" type="text" name="naam" id="i_naam" placeholder="naam van dit item" required/>
					</td>
					<td>
						<input style="width=100%" type="number" step="0.01" oninput="new_item()" name="winkelprijs_pp" id="i_winkelprijs_pp" placeholder="winkelprijs per persoon" required/>
					</td>
					<td>
						<input style="width=100%" type="number" step="0.01" oninput="new_item()" name="winstmarge" id="i_winstmarge" placeholder="winstmarge in %" required/>
					</td>
					<td id="i_dienstprijs"></td>
					<td id="i_sponsorbijdrage"></td>
					<td>
					<?= get_submit_button( 'Add', null, 'prijzentabel_item_add_submit' ) ?>
					</td>
				</form>
			</tr>
			<script>
				function new_item(){
					let winkelprijs_pp = document.getElementById("i_winkelprijs_pp").value;
					let winstmarge = document.getElementById("i_winstmarge").value;
					let dienstprijs = document.getElementById("i_dienstprijs");
					let sponsorbijdrage = document.getElementById("i_sponsorbijdrage");
						console.log(winkelprijs_pp,winstmarge);
					if(winkelprijs_pp!="" && winstmarge!=""){
						dienstprijs.innerText= Math.round(Number(winkelprijs_pp)+(Number(winkelprijs_pp)*Number(winstmarge)/100.0))+" EUR";
						sponsorbijdrage.innerText=Math.round(Number(winkelprijs_pp)+(Number(winkelprijs_pp)*Number(winstmarge)/100.0)) - Number(winkelprijs_pp)+" EUR" ;
					}else{
						dienstprijs.innerText="";
						sponsorbijdrage.innerText="";
					}
				}
			</script>
			<?php
			foreach($items as $item){
				$winkelprijs_pp = intval($item["winkelprijs_pp"]);
				$winstmarge = intval($item["winstmarge"]);
				$dienstprijs = round($winkelprijs_pp+($winkelprijs_pp*$winstmarge/100), 0);
				$sponsorbijdrage = $dienstprijs-$winkelprijs_pp;
				$ID = intval($item["ID"]);
				?>
				<tr style="<?= (($ID % 2 == 1)?"background-color:#CCCCCC":"") ?>">
					<td>A-<?= $item["ID"]?></td>
					<td><?= $item["naam"] ?></td>
					<td><?= $item["winkelprijs_pp"] ?> EUR</td>
					<td><?= $item["winstmarge"] ?>%</td>
					<td><?= $dienstprijs ?> EUR</td>
					<td><?= $sponsorbijdrage ?> EUR</td>
					<td>
						<form action="" method="post">
							<input type="text" style="display:none;" name="ID" value="<?= $item["ID"]?>" />
							<?= get_submit_button( 'Delete', null, 'prijzentabel_item_delete_submit' ) ?>
						</form>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<hr />
		<h1>Workshops</h1>
		<table style="width:100%;text-align:center">
			<thead style="background-color:#000000;color:#ffffff;font-weight:500">
			<tr>
				<th>Code</th>
				<th>Naam</th>
				<th>Prijs per persoon</th>
				<th>Minimum prijs</th>
				<th>Winstmarge</th>
				<th>Dienstprijs</th>
				<th>Sponsorbijdrage</th>
				<th>Edit</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<form action="" method="post">
					<td>
						/
					</td>
					<td>
						<input style="width=100%" type="text" name="naam" id="ws_naam" placeholder="naam van deze workshop" required/>
					</td>
					<td>
						<input style="width=100%" type="number" step="0.01" oninput="new_workshop()" name="prijs_pp" id="ws_prijs_pp" placeholder="winkelprijs per persoon" required/>
					</td>
					<td>
						<input style="width=100%" type="number" step="0.01" oninput="new_workshop()" name="min_prijs" id="ws_min_prijs" placeholder="minimum prijs" required/>
					</td>
					<td>
						<input style="width=100%" type="number" step="0.01 oninput="new_workshop()" name="winstmarge" id="ws_winstmarge" placeholder="winstmarge in %" required/>
					</td>
					<td id="ws_dienstprijs"></td>
					<td id="ws_sponsorbijdrage"></td>
					<td>
					<?= get_submit_button( 'Add', null, 'prijzentabel_workshop_add_submit' ) ?>
					</td>
				</form>
			</tr>
			<script>
				function new_workshop(){
					let prijs_pp = Number(document.getElementById("ws_prijs_pp").value);
					let min_prijs = Number(document.getElementById("ws_min_prijs").value)
					let prijs = (prijs_pp>min_prijs)?prijs_pp:min_prijs;
					let winstmarge = Number(document.getElementById("ws_winstmarge").value);
					let dienstprijs = document.getElementById("ws_dienstprijs");
					let sponsorbijdrage = document.getElementById("ws_sponsorbijdrage");
					if(min_prijs!="" && winstmarge!=""){
						dienstprijs.innerText= Math.round(prijs+(prijs*winstmarge/100.0))+" EUR";
						sponsorbijdrage.innerText=Math.round(prijs+(prijs*winstmarge/100.0)) -prijs +" EUR" ;
					}else{
						dienstprijs.innerText="";
						sponsorbijdrage.innerText="";
					}
				}
			</script>
			<?php
			foreach($workshops as $workshop){
				$min_prijs = intval($workshop["min_prijs"]);
				$prijs_pp = intval($workshop["prijs_pp"]);
				$prijs = ($prijs_pp>$min_prijs)?$prijs_pp:$min_prijs;
				$winstmarge = intval($workshop["winstmarge"]);
				$dienstprijs = round($prijs+($prijs*$winstmarge/100), 0);
				$sponsorbijdrage = $dienstprijs-$prijs;
				$ID = intval($workshop["ID"]);
				?>
				<tr style="<?= (($ID % 2 == 1)?"background-color:#CCCCCC":"") ?>">
					<td>WS-<?= $workshop["ID"]?></td>
					<td><?= $workshop["naam"] ?></td>
					<td><?= $workshop["min_prijs"] ?> EUR</td>
					<td><?= $workshop["prijs_pp"] ?> EUR/pp</td>
					<td><?= $workshop["winstmarge"] ?>%</td>
					<td><?= $dienstprijs ?> EUR</td>
					<td><?= $sponsorbijdrage ?> EUR</td>
					<td>
						<form action="" method="post">
							<input type="text" style="display:none;" name="ID" value="<?= $workshop["ID"]?>" />
							<?= get_submit_button( 'Delete', null, 'prijzentabel_workshop_delete_submit' ) ?>
						</form>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php
}

add_shortcode('prijscalculatie_formulier','prijscalculatie_formulier');
add_action("admin_menu","prijzentabel");
// add_action("wp_head","prijzentabel_item_add");
?>