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
$previousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -1 month"));
$previousMonthOpen = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '0' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");
$previousMonthClose = $wpdb->get_results("SELECT * FROM mju_tickets WHERE status = '4' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");
$previousMonthUnderProcess = $wpdb->get_results("SELECT * FROM mju_tickets WHERE 1 < status < 4  AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

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
    $previousMonthTicket = $wpdb->get_results("SELECT * FROM mju_tickets WHERE type='" . $type->type_id . "' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

    $ticketsTypesDataArray[$i][0] = $type->type_name;
    $ticketsTypesDataArray[$i][1] = count($currentTicket);

    $previousMonthTicketsTypesDataArray[$i][0] = $type->type_name;
    $previousMonthTicketsTypesDataArray[$i][1] = count($previousMonthTicket);
    $i ++;
}

$todayMonthArabic = getArabicDate($todayDateMonthStr);
$prevMonthArabic = getArabicDate(date('M', strtotime(date('Y-m') . " -1 month")));

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

/* Months Compare Tickets */
//Current Month
$currentMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");

//Previous Month
$previousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

//Double Previous Month
$doublePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE (create_date BETWEEN '" . $doublePreviousMonthStartDate . "' AND  '" . $doublePreviousMonthEndDate . "')");

//Tripple Previous Month
$triplePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE (create_date BETWEEN '" . $triplePreviousMonthStartDate . "' AND  '" . $triplePreviousMonthEndDate . "')");

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


/* Buildings report */
$buildingsDataArray = array();
$buildingsDataArray[0][0] = "Building";
$buildingsDataArray[0][1] = "Number Of Tickets";

$previousMonthBuildingsDataArray = array();
$previousMonthBuildingsDataArray[0][0] = "Building";
$previousMonthBuildingsDataArray[0][1] = "Number Of Tickets";

$buildings = $wpdb->get_results("SELECT * FROM mju_buildings");
$i = 1;
foreach ($buildings as $building) {
    $currentMonthBuildingTickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $building->building_id . "' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
    $buildingsDataArray[$i][0] = $building->building_name;
    $buildingsDataArray[$i][1] = count($currentMonthBuildingTickets);

    $previousMonthBuildingTickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $building->building_id . "' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");
    $previousMonthBuildingsDataArray[$i][0] = $building->building_name;
    $previousMonthBuildingsDataArray[$i][1] = count($previousMonthBuildingTickets);
    $i ++;
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
                    document.getElementById("ticketsStatusReportImage").value = chart.getImageURI();
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
                    document.getElementById("ticketsStatusReportImagePreviousMonth").value = chart.getImageURI();
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
                    document.getElementById("ticketsTypesCurrentMonthImage").value = chart.getImageURI();
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
                    document.getElementById("ticketsTypesPreviousMonthImage").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>
        
        <!--Current Month Buildings Report-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawBuildingsData);
            var buildingsDataArray = <?php echo json_encode($buildingsDataArray); ?>;
            console.log(buildingsDataArray);
            function drawBuildingsData() {
                var data = google.visualization.arrayToDataTable(buildingsDataArray);

                var options = {
                    title: 'الطلبات حسب المباني لشهر <?php echo $todayMonthArabic; ?>',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('currentBuildingsReportDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("ticketsTypesCurrentMonthImage").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

        <!--Previous Month Buildings Report-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawPreviousBuildingsData);
            var previousMonthBuildingsData = <?php echo json_encode($previousMonthBuildingsDataArray); ?>;
            console.log(previousMonthBuildingsData);
            function drawPreviousBuildingsData() {
                var data = google.visualization.arrayToDataTable(previousMonthBuildingsData);

                var options = {
                    title: 'الطلبات حسب المباني لشهر <?php echo $prevMonthArabic; ?>',
                    is3D: true,
                    pieSliceText: 'value',
                    backgroundColor: 'transparent',
                    chartArea: {width: '100%', height: '80%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('previousMonthBuildingsReportDiv'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
//                    document.getElementById("ticketsTypesPreviousMonthImage").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

        <!--Months Report-->
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['bar']});
            google.charts.setOnLoadCallback(drawMonthsTicketsData);
            var monthsTicketsData = <?php echo json_encode($FullMonthsTicketsArray); ?>;
            function drawMonthsTicketsData() {
                var data = google.visualization.arrayToDataTable(monthsTicketsData);

                var options = {
                    title: 'طلبات الصيانة لاخر 3 شهور',
//                    width: 900,
                    backgroundColor: 'transparent',
                    chartArea: {width: '60%', height: '70%'},
                    annotations: {
                        alwaysOutside: true,
                        textStyle: {
                            fontSize: 14,
                            color: '#000',
                            auraColor: 'none'
                        }
                    },
//                    hAxis: {
//                        title: 'Month',
//                    },
                    vAxis: {
                        title: 'عدد الطلبات'
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('monthsCompareReport'));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
                    document.getElementById("monthsTicketsImage").value = chart.getImageURI();
                });
                chart.draw(data, options);
            }
        </script>

    </head>
    <body>
        <div class="no-print">
            <a href="staff-home?segment=5">الرئيسية</a> | <a href="staff-buildings-reports">تقارير مفصلة عن المباني</a>
        </div>
        <div class="container-fluid">
            <h2>التقارير</h2>
            <div class="row reports-page">
                <div class="col-xs-12 col-md-12">
                    <div class="">


                        <div class="chart_container first_chart_row">
                            <div id="currentMonthTicketsStatusDiv" class="chart_div"></div>
                            <div id="previousMonthTicketsStatusDiv" class="chart_div"></div>
                        </div>
                        <div class="chart_container first_chart_row">
                            <div id="currentMonthTypesDiv" class="chart_div"></div>
                            <div id="previousMonthTypesDiv" class="chart_div"></div>
                        </div>
                        <div class="chart_container first_chart_row">
                            <div id="currentBuildingsReportDiv" class="chart_div"></div>
                            <div id="previousMonthBuildingsReportDiv" class="chart_div"></div>
                        </div>
                        <div class="chart_container first_chart_row">
                            <div id="monthsCompareReport" style="width: 90%;border-style: solid;border-color: rgba(0, 0, 0, 0.24);border-width: 2px;"></div>
                        </div>
                    </div>
                    <form id="" class="no-print" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/send-reports.php'; ?>">
                        <input id="ticketsStatusReportImage" type="hidden" placeholder="name" name="ticketsStatusReportImage" />
                        <input id="ticketsStatusReportImagePreviousMonth" type="hidden" placeholder="name" name="ticketsStatusReportImagePreviousMonth" />
                        <input id="ticketsTypesCurrentMonthImage" type="hidden" placeholder="name" name="ticketsTypesCurrentMonthImage" />
                        <input id="ticketsTypesPreviousMonthImage" type="hidden" placeholder="name" name="ticketsTypesPreviousMonthImage" />
                        <input id="monthsTicketsImage" type="hidden" placeholder="name" name="monthsTicketsImage" />
                        <input  type="submit" value="Send Email"/>
                    </form>
                </div>
            </div>
        </div>
        <?php get_footer(); ?>	
    </body>
</html>

