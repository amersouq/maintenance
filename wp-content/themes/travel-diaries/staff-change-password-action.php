<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$userID = $_POST["userID"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];
if ($password == $confirm_password) {
    if ($userID) {
        $changed = $wpdb->update(
                'mju_staff_users', // Table
                array(
            'password' => md5($password),
                ), // Array of key(col) => val(value to update to)
                array(
            'id' => $userID
                )
        );
        var_dump(true);
    }else{
        var_dump(false);
    }
}else{
    var_dump(false);
}
