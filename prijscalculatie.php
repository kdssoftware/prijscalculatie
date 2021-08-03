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
	
	return $content;
}

function prijzentabel(){
	add_menu_page("Prijzentabel aanpassen","TPX Prijzentabel","manage_options","prijzentabel","prijzentabel_page","",200);
}


function prijzentabel_page(){
	global $wpdb;
	$items = $wpdb->get_results("SELECT * FROM wp_items",ARRAY_A);
	?>
		<table style="width:100%;text-align:center">
			<thead style="background-color:#000000;color:#ffffff;font-weight:500">
			<tr>
				<th>Code</th>
				<th>Item-naam</th>
				<th>Winkelprijs/pp</th>
				<th>Winstmarge</th>
				<th>Dienstprijs</th>
				<th>Sponsorbijdrage</th>
				<th>Edit </th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<form action="<?=get_admin_url()."admin-post.php"?>" method="POST">
					<td>
						/
					</td>
					<td>
						<input style="width=100%" type="text" name="naam" id="naam" placeholder="naam van dit item" required/>
					</td>
					<td>
						<input style="width=100%" type="number" oninput="new_item()" name="winkelprijs_pp" id="winkelprijs_pp" placeholder="winkelprijs per persoon" required/>
					</td>
					<td>
						<input style="width=100%" type="number" oninput="new_item()" name="winstmarge" id="winstmarge" placeholder="winstmarge in %" required/>
					</td>
					<td id="dienstprijs"></td>
					<td id="sponsorbijdrage"></td>
					<td>
						<input type="submit" name="prijzentabel_item_add_submit" value="Add"/>
					</td>
				</form>
			</tr>
			<script>
				function new_item(){
					let winkelprijs_pp = document.getElementById("winkelprijs_pp").value;
					let winstmarge = document.getElementById("winstmarge").value;
					let dienstprijs = document.getElementById("dienstprijs");
					let sponsorbijdrage = document.getElementById("sponsorbijdrage");
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
					<td><?= $item["ID"]?></td>
					<td><?= $item["naam"] ?></td>
					<td><?= $item["winkelprijs_pp"] ?> EUR</td>
					<td><?= $item["winstmarge"] ?>%</td>
					<td><?= $dienstprijs ?> EUR</td>
					<td><?= $sponsorbijdrage ?> EUR</td>
					<td>
						<button>Delete</button>
						<button>Edit</button>
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