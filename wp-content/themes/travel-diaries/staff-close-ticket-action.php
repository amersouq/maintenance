<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$ticketID = $_POST["ticketID"];
$comment = $_POST["comment"];
$staff_username = $_POST["staff_username"];
date_default_timezone_set("Asia/Riyadh");
//var_dump($_POST);die();
if ($ticketID && $comment && $staff_username) {
    $wpdb->update(
            'mju_tickets', // Table
            array(
        'status' => 4,
        'close_comment' => $comment
            ), // Array of key(col) => val(value to update to)
            array(
        'ticket_id' => $ticketID
            )
    );

    $inserted = $wpdb->insert(
        'mju_tickets_log', array(
            'ticket_id' => $ticketID,
            'action_date' => date('Y-m-d G:i:sa'),
            'status_from' => 2,
            'status_to' => 4,
            'changed_by' => $staff_username,
        )
    );
    
    wp_redirect(get_site_url() . '/staff-ticket-details?ticketID=' . $ticketID);
}