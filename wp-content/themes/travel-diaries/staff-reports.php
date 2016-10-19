<?php
/*
  Template Name: staff-reports
 */

checkStaffLoginUserInit();

get_header();
global $wpdb;

/* Tickets Status of Current Month */
$todayDateMonth = date('m');
$todayDateMonthStr = date('M');
$todayDateYear = date('Y');
$startDateStr = "1-" . $todayDateMonth . "-" . $todayDateYear;
$startDate = date("Y-m-d G:i:sa", strtotime($startDateStr));
$endDate = date("Y-m-d G:i:sa");
$currentMonthOpen = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '0' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
$currentMonthClose = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '4' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
$currentMonthUnderProcess = $wpdb->get_results("SELECT * FROM mju_tickets WHERE (status BETWEEN '1' AND '3')  AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
$currentMonthOpenCount = count($currentMonthOpen);
$currentMonthCloseCount = count($currentMonthClose);
$currentMonthUnderProcessCount = count($currentMonthUnderProcess);
$currentMonthTicketsStatusArray = array();
$currentMonthTicketsStatusArray[0][0] = "Status";
$currentMonthTicketsStatusArray[0][1] = "Number Of Tickets";
$currentMonthTicketsStatusArray[1][0] = "مفتوح";
$currentMonthTicketsStatusArray[1][1] = $currentMonthOpenCount;
$currentMonthTicketsStatusArray[2][0] = "تحت التنفيذ";
$currentMonthTicketsStatusArray[2][1] = $currentMonthUnderProcessCount;
$currentMonthTicketsStatusArray[3][0] = "منتهي";
$currentMonthTicketsStatusArray[3][1] = $currentMonthCloseCount;

/* Previous Month */
$previousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -1 month"));
$prevEndDate = new DateTime( date("Y-m-d G:i:sa") );
$prevEndDate = date('Y-m-d G:i:sa', strtotime('last day of previous month'));
$prevMonthDateMonth = date('M', strtotime('last day of previous month'));
$previousMonthOpen = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '0' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $prevEndDate . "')");
$previousMonthClose = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '4' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $prevEndDate . "')");
$previousMonthUnderProcess = $wpdb->get_results("SELECT * FROM mju_tickets WHERE 1 < status < 4  AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $prevEndDate . "')");

$previousMonthOpenCount = count($previousMonthOpen);
$previousMonthCloseCount = count($previousMonthClose);
$previousMonthUnderProcessCount = count($previousMonthUnderProcess);
$previousMonthTicketsStatusArray = array();
$previousMonthTicketsStatusArray[0][0] = "Status";
$previousMonthTicketsStatusArray[0][1] = "Number Of Tickets";
$previousMonthTicketsStatusArray[1][0] = "مفتوح";
$previousMonthTicketsStatusArray[1][1] = $previousMonthOpenCount;
$previousMonthTicketsStatusArray[2][0] = "تحت التنفيذ";
$previousMonthTicketsStatusArray[2][1] = $previousMonthUnderProcessCount;
$previousMonthTicketsStatusArray[3][0] = "منتهي";
$previousMonthTicketsStatusArray[3][1] = $previousMonthCloseCount;

/* Maintenance type reports */
$ticketsTypesDataArray = array();
$ticketsTypesDataArray[0][0] = "Type";
$ticketsTypesDataArray[0][1] = "Number Of Tickets";

$previousMonthTicketsTypesDataArray = array();
$previousMonthTicketsTypesDataArray[0][0] = "Type";
$previousMonthTicketsTypesDataArray[0][1] = "Number Of Tickets";

$tickets_types = $wpdb->get_results("SELECT * FROM mju_tickets_types");
$i = 1;
foreach ($tickets_types as $type) {
    $currentTicket = $wpdb->get_results("SELECT * FROM mju_tickets WHERE type='" . $type->type_id . "' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
    $previousMonthTicket = $wpdb->get_results("SELECT * FROM mju_tickets WHERE type='" . $type->type_id . "' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $prevEndDate . "')");
    echo count($currentTicket) . '-';
    echo count($previousMonthTicket) . '-';
    $ticketsTypesDataArray[$i][0] = $type->type_name;
    $ticketsTypesDataArray[$i][1] = count($currentTicket);

    $previousMonthTicketsTypesDataArray[$i][0] = $type->type_name;
    $previousMonthTicketsTypesDataArray[$i][1] = count($previousMonthTicket);
    $i ++;
}

$todayMonthArabic = getArabicDate($todayDateMonthStr);
$prevMonthArabic = getArabicDate($prevMonthDateMonth);

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
?>
<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!--Current Month Tickets Status-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawCurrentMonthTicketsStatusArray);
            var currentMonthTicketsStatusArray = <?php echo json_encode($currentMonthTicketsStatusArray); ?>;
//            console.log(currentMonthTicketsStatusArray);
            function drawCurrentMonthTicketsStatusArray() {
                var data = google.visualization.arrayToDataTable(currentMonthTicketsStatusArray);

                var options = {
                    title: 'حالة الطلبات لشهر <?php echo $todayMonthArabic; ?>',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('currentMonthTicketsStatusDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("manpowerCompanies").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

        <!--Previous Month Tickets Status-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawPreviousMonthTicketsStatusArray);
            var previousMonthTicketsStatusArray = <?php echo json_encode($previousMonthTicketsStatusArray); ?>;
//            console.log(previousMonthTicketsStatusArray);
            function drawPreviousMonthTicketsStatusArray() {
                var data = google.visualization.arrayToDataTable(previousMonthTicketsStatusArray);

                var options = {
                    title: 'حالة الطلبات لشهر<?php echo $prevMonthArabic; ?> ',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('previousMonthTicketsStatusDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("manpowerCompanies").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

        <!--Current Month Types Report-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawTypesData);
            var typesData = <?php echo json_encode($ticketsTypesDataArray); ?>;
            console.log(typesData);
            function drawTypesData() {
                var data = google.visualization.arrayToDataTable(typesData);

                var options = {
                    title: 'أنواع الطلبات لشهر <?php echo $todayMonthArabic; ?>',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('currentMonthTypesDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("sitesManpowerA").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

        <!--Previous Month Types Report-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawPreviousMonthTypesData);
            var previousMonthTypesData = <?php echo json_encode($previousMonthTicketsTypesDataArray); ?>;
            console.log(previousMonthTypesData);
            function drawPreviousMonthTypesData() {
                var data = google.visualization.arrayToDataTable(previousMonthTypesData);

                var options = {
                    title: 'أنواع الطلبات لشهر <?php echo $prevMonthArabic; ?>',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('previousMonthTypesDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("sitesManpowerA").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

    </head>
    <body>
        <div class="no-print">
            <a href="staff-home?segment=5">الرئيسية</a>
        </div>
        <div class="container-fluid">
            <h2>التقارير</h2>
            <div class="row reports-page">
                <div class="col-xs-12 col-md-12">
                    <div class="">

                        <button id="absence_print_btn" class="print-link no-print" onclick="jQuery('#absence_stats_div').print();">Print</button>

                        <div id='companies_count' class="chart_container first_chart_row">
                            <div id="currentMonthTicketsStatusDiv" class="chart_div"></div>
                            <div id="previousMonthTicketsStatusDiv" class="chart_div"></div>
                            <!--<div style="width:100%; text-align: center;">SBTMC: <?php // echo $allSbtmcCount;  ?></div>-->
                            <!--<div style="width:100%; text-align: center;">Mahara: <?php // echo $allMaharaCount;  ?></div>-->
                        </div>
                        <div id='companies_count' class="chart_container first_chart_row">
                            <div id="currentMonthTypesDiv" class="chart_div"></div>
                            <div id="previousMonthTypesDiv" class="chart_div"></div>
                            <!--<div style="width:100%; text-align: center;">SBTMC: <?php // echo $allSbtmcCount;  ?></div>-->
                            <!--<div style="width:100%; text-align: center;">Mahara: <?php // echo $allMaharaCount;  ?></div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php get_footer(); ?>	
    </body>
</html>

