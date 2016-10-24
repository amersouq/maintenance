<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$buildingID = $_GET["buildingID"];

$todayDateMonth = date('m');
$todayDateMonthStr = date('M');
$todayDateYear = date('Y');
$startDateStr = "1-" . $todayDateMonth . "-" . $todayDateYear;
$startDate = date("Y-m-d G:i:sa", strtotime($startDateStr));
$endDate = date("Y-m-d G:i:sa");
$previousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -1 month"));
$previousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -1 month"));

//Current Month
$currentMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$buildingID."' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");

//Previous Month
$previousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$buildingID."' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

//Double Previous Month
$doublePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$buildingID."' AND (create_date BETWEEN '" . $doublePreviousMonthStartDate . "' AND  '" . $doublePreviousMonthEndDate . "')");
//var_dump($doublePreviousMonthTicketsAll);
//Tripple Previous Month
$triplePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$buildingID."' AND (create_date BETWEEN '" . $triplePreviousMonthStartDate . "' AND  '" . $triplePreviousMonthEndDate . "')");

function getArabicDate($englishDateMonth) {
    if ($englishDateMonth == "Jan")
        $prevMonthArabic = "يناير";
    if ($englishDateMonth == "Feb")
        $prevMonthArabic = "فبراير";
    if ($englishDateMonth == "Mar")
        $prevMonthArabic = "مارس";
    if ($englishDateMonth == "Apr")
        $prevMonthArabic = "أبريل";
    if ($englishDateMonth == "May")
        $prevMonthArabic = "مايو";
    if ($englishDateMonth == "Jun")
        $prevMonthArabic = "يونيو";
    if ($englishDateMonth == "Jul")
        $prevMonthArabic = "يوليو";
    if ($englishDateMonth == "Aug")
        $prevMonthArabic = "أغسطس";
    if ($englishDateMonth == "Sep")
        $prevMonthArabic = "سبتمبر";
    if ($englishDateMonth == "Oct")
        $prevMonthArabic = "أكتوبر";
    if ($englishDateMonth == "Nov")
        $prevMonthArabic = "نوفمبر";
    if ($englishDateMonth == "Dec")
        $prevMonthArabic = "ديسمبر";
    return $prevMonthArabic;
}

$FullMonthsTicketsArray = array();
$FullMonthsTicketsArray[0][0] = 'Month';
$FullMonthsTicketsArray[0][1] = 'الطلبات';
$FullMonthsTicketsArray[0][2] = array(type => 'string', role => 'annotation');
$FullMonthsTicketsArray[1][0] = getArabicDate(date('M', strtotime(date('Y-m') . " -3 month")));
$FullMonthsTicketsArray[1][1] = count($triplePreviousMonthTicketsAll);
$FullMonthsTicketsArray[1][2] = count($triplePreviousMonthTicketsAll);
$FullMonthsTicketsArray[2][0] = getArabicDate(date('M', strtotime(date('Y-m') . " -2 month")));
$FullMonthsTicketsArray[2][1] = count($doublePreviousMonthTicketsAll);
$FullMonthsTicketsArray[2][2] = count($doublePreviousMonthTicketsAll);
$FullMonthsTicketsArray[3][0] = getArabicDate(date('M', strtotime(date('Y-m') . " -1 month")));
$FullMonthsTicketsArray[3][1] = count($previousMonthTicketsAll);
$FullMonthsTicketsArray[3][2] = count($previousMonthTicketsAll);
$FullMonthsTicketsArray[4][0] = getArabicDate(date('M', strtotime(date('Y-m'))));
$FullMonthsTicketsArray[4][1] = count($currentMonthTicketsAll);
$FullMonthsTicketsArray[4][2] = count($currentMonthTicketsAll);

echo json_encode($FullMonthsTicketsArray);