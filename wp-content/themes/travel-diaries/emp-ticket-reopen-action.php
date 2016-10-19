<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$ticketID = $_POST["ticketID"];
$comment = $_POST["comment"];
$emp_username = $_POST["emp_username"];
date_default_timezone_set("Asia/Riyadh");
//var_dump($_POST);die();
if ($ticketID && $comment && $emp_username) {
    $wpdb->update(
            'mju_tickets', // Table
            array(
        'status' => 3,
        'reopen_comment' => $comment
            ), // Array of key(col) => val(value to update to)
            array(
        'ticket_id' => $ticketID
            )
    );

    $inserted = $wpdb->insert(
        'mju_tickets_log', array(
            'ticket_id' => $ticketID,
            'action_date' => date('Y-m-d G:i:sa'),
            'status_from' => 4,
            'status_to' => 3,
            'changed_by' => $emp_username,
        )
    );
    
    wp_redirect(get_site_url() . '/emp-ticket-details?ticketID=' . $ticketID);
}