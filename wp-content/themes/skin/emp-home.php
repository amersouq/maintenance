<?php
/*
  Template Name: emp-home
 */
get_header();
global $wpdb;


$wsUsername = $_GET["wsUsername"];
$wsPassword = $_GET["wsPassword"];
$emp_username = $_GET["emp_username"];
$emp_name = $_GET["emp_name"];

if (!$wsUsername || !$wsPassword || !$emp_name || !$emp_username) {
    echo 'wrong auth';
} else {
    //Check Web Service Authentication
    $ws_auth = $wpdb->get_results("SELECT * FROM mju_ws_auth WHERE group='1'");
    var_dump($ws_auth);
}
$ws_auth = $wpdb->get_results("SELECT * FROM mju_tickets ");
var_dump($ws_auth);
var_dump("????");
$text = utf8_encode("????");
echo $text;
echo "???";
?>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<div class="no-print" id="segment" >
    <a href="<?php echo get_site_url() . '/emp-home?segment=0'; ?>" class="<?php echo (!$segment || ($segment == 0)) ? "bordered-segment" : ""; ?>">????</a> |
    <a href="<?php echo get_site_url() . '/emp-home?segment=1'; ?>" class="<?php echo (($segment == 1)) ? "bordered-segment" : ""; ?>">????? ??????</a> | 
    <a href="<?php echo get_site_url() . '/emp-home?segment=2'; ?>" class="<?php echo (($segment == 2)) ? "bordered-segment" : ""; ?>">????? ?????</a> | 
    <br />
</div>   
NOw:
<p dir="rtl" lang="ar" style="color:black;font-size:20px;">? ?? ??? ???? ????</p>
<p dir="rtl" lang="ar" style="color:black;font-size:20px;">aaaa</p>

<?php get_footer(); ?>	