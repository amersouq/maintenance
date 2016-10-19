<?php
/*
  Template Name: emp-home-auth
 */
$wsUsername = $_GET["wsUsername"];
$wsPassword = $_GET["wsPassword"];
$emp_username = $_GET["emp_username"];
$emp_name = $_GET["emp_name"];
$date = $_GET["date"];
$current_date = date('Y-m-d');
if ($date == $current_date) {
    if ($wsUsername && $wsPassword && $emp_name && $emp_username && $date) {
        $webServiceAuthDetails = $wpdb->get_results("SELECT * FROM mju_ws_auth WHERE id='1'");
        $webServiceAuthDetails = $webServiceAuthDetails[0];
        if (($wsUsername == $webServiceAuthDetails->username) && ($wsPassword == $webServiceAuthDetails->password)) {
//            session_unset();
//            session_destroy();
            $_SESSION['logged_in'] = true; //set you've logged in
            $_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
            $_SESSION['expire_time'] = 15 * 60 * 10; //expire time in seconds: three hours (you must change this)
            $_SESSION["emp_username"] = $emp_username;
            $_SESSION["emp_name"] = $emp_name;
            $_SESSION['loggedin_time'] = time();
            echo'<script> window.location="emp-home"; </script> ';
            die();
        } 
    } 
} 
echo'<script> window.location="wrong-emp-login"; </script> ';
die();
?>
	