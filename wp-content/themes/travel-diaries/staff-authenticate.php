<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;
if (!empty($_POST["username"]) && !empty($_POST["password"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $myrows = $wpdb->get_results("SELECT * FROM mju_staff_users WHERE username='" . $username . "'");

    if ($myrows && (count($myrows) > 0)) {
        foreach ($myrows as $row) {

            $insertedPassword = md5($password);
            $userPassword = $row->password;
            if ($insertedPassword === $userPassword) {
//                myEndSession();
                $_SESSION['staff_logged_in'] = true; //set you've logged in
                $_SESSION['staff_last_activity'] = time(); //your last activity was now, having logged in.
                $_SESSION['staff_expire_time'] = 15*60; //expire time in seconds: three hours (you must change this)
                $_SESSION["staff_user_details"] = $row;
                $_SESSION['staff_loggedin_time'] = time();
                wp_redirect(get_site_url() . '/staff-home?segment=5');
            } else {
                wp_redirect(get_site_url());
                var_dump("Login False");
            }
        }
    } else {
        wp_redirect(get_site_url());
    }
}

