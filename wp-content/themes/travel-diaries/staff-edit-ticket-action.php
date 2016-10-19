<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$ticketID = $_POST["ticketID"];
$status = $_POST["status"];
$staff_username = $_POST["staff_username"];
$wo_num = $_POST["jo_num"];
date_default_timezone_set("Asia/Riyadh");
//var_dump($_POST);die();
$wpdb->update(
        'mju_tickets', // Table
        array(
    'status' => $status,
    'jo_number' => $wo_num ? $wo_num : 0,
        ), // Array of key(col) => val(value to update to)
        array(
    'ticket_id' => $ticketID
        )
);

wp_redirect(get_site_url() . '/staff-ticket-details?ticketID=' . $ticketID);
