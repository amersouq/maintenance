<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$regionID = $_GET["regionID"];
$region_buildings = $wpdb->get_results("SELECT * FROM mju_buildings WHERE building_region='".$regionID."'");
//var_dump($region_buildings);
//$selectElement = '<select id="building" name="building">';
$selectElement = '<option value="0" >أخرى</option>';
foreach ($region_buildings as $building) {
    $selectElement .= '<option value="' . $building->building_id . '" >' . $building->building_name . '</option>';
}
//$selectElement .= "</select>";

echo $selectElement;