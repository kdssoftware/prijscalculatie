<?php
global $wpdb;
if(array_key_exists("prijzentabel_item_add_submit",$_POST)){
    $wpdb->insert("wp_items",array(
        "naam"=>$_POST["naam"],
        "winkelprijs_pp" =>$_POST["winkelprijs_pp"],
        "winstmarge"=>$_POST["winstmarge"]
    ));
}

header("Location: ".$_SERVER['HTTP_REFERER']);