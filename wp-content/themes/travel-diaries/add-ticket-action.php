<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$subject = $_POST["subject"];
$description = $_POST["description"];
$place = $_POST["place"];
$room = $_POST["room"];
$type = $_POST["type"];
$emp_username = $_POST["emp_username"];
$emp_name = $_POST["emp_name"];
$emp_phone = $_POST["phone"];
$ticketBuilding = $_POST["building"];

date_default_timezone_set("Asia/Riyadh");
$date = date('Y-m-d G:i:sa');

if ($subject && $description && $place && $room && $type && $emp_name) {
    $inserted = $wpdb->insert(
        'mju_tickets', array(
            'title' => $subject,
            'description' => $description,
            'building' => $ticketBuilding ? $ticketBuilding : 0,
            'place' => $place,
            'room_number' => $room,
            'type' => $type,
            'create_date' => $date,
            'created_by' => $emp_username,
            'created_by_name' => $emp_name,
            'creator_phone' => $emp_phone,
            'status' => 0
        )
    );
    $lastid = $wpdb->insert_id;
//    var_dump($lastid);die();
    $inserted = $wpdb->insert(
        'mju_tickets_log', array(
            'ticket_id' => $lastid,
            'action_date' => $date,
            'status_to' => 0,
            'changed_by' => $emp_username,
        )
    );
    wp_redirect( get_site_url() . '/emp-home' );
}