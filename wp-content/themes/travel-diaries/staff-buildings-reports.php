<?php
/*
  Template Name: staff-buildings-reports
 */
checkStaffLoginUserInit();

get_header();
global $wpdb;

$selected_building = $_GET["buildingID"] ? $_GET["buildingID"]  : 1;
$selected_building_name = $wpdb->get_results("SELECT * FROM mju_buildings WHERE building_id = '".$selected_building."'");
$selected_building_name = $selected_building_name[0]->building_name;

$todayDateMonth = date('m');
$todayDateMonthStr = date('M');
$todayDateYear = date('Y');
$startDateStr = "1-" . $todayDateMonth . "-" . $todayDateYear;
$startDate = date("Y-m-d G:i:sa", strtotime($startDateStr));
$endDate = date("Y-m-d G:i:sa");
$previousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -1 month"));
$previousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -1 month"));
//Double Previous Month
$doublePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -2 month"));
//Tripple Previous Month
$triplePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -3 month"));

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

/* Selected Building Data */
$currentMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $selected_building . "' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");

//Previous Month
$previousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $selected_building . "' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

//Double Previous Month
$doublePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -2 month"));
$doublePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $selected_building . "' AND (create_date BETWEEN '" . $doublePreviousMonthStartDate . "' AND  '" . $doublePreviousMonthEndDate . "')");
//var_dump($doublePreviousMonthTicketsAll);
//Tripple Previous Month
$triplePreviousMonthStartDate = date('Y-m-d G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthEndDate = date('Y-m-t G:i:sa', strtotime(date('Y-m') . " -3 month"));
$triplePreviousMonthTicketsAll = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='" . $selected_building . "' AND (create_date BETWEEN '" . $triplePreviousMonthStartDate . "' AND  '" . $triplePreviousMonthEndDate . "')");

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

/*Selected Building Tickets Types Details*/
$ticketsTypesDataArray = array();
$ticketsTypesDataArray[0][0] = "Type";
$ticketsTypesDataArray[0][1] = "Number Of Tickets";

$previousMonthTicketsTypesDataArray = array();
$previousMonthTicketsTypesDataArray[0][0] = "Type";
$previousMonthTicketsTypesDataArray[0][1] = "Number Of Tickets";

$tickets_types = $wpdb->get_results("SELECT * FROM mju_tickets_types");
$i = 1;
foreach ($tickets_types as $type) {
    $currentTicket = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$selected_building."' AND type='" . $type->type_id . "' AND (create_date BETWEEN '" . $startDate . "' AND  '" . $endDate . "')");
    $previousMonthTicket = $wpdb->get_results("SELECT * FROM mju_tickets WHERE building='".$selected_building."' AND type='" . $type->type_id . "' AND (create_date BETWEEN '" . $previousMonthStartDate . "' AND  '" . $previousMonthEndDate . "')");

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
?>
<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <!--Current Month Buildings Report-->
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawBuildingsData);
            var buildingsDataArray = <?php echo json_encode($ticketsTypesDataArray); ?>;
            console.log(buildingsDataArray);
            function drawBuildingsData() {
                var data = google.visualization.arrayToDataTable(buildingsDataArray);

                var options = {
                    title: 'طلبات  <?php echo $selected_building_name ?> لشهر <?php echo $todayMonthArabic ?>',
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
            var previousMonthBuildingsData = <?php echo json_encode($previousMonthTicketsTypesDataArray); ?>;
            console.log(previousMonthBuildingsData);
            function drawPreviousBuildingsData() {
                var data = google.visualization.arrayToDataTable(previousMonthBuildingsData);

                var options = {
                    title: 'طلبات  <?php echo $selected_building_name ?> لشهر <?php echo $prevMonthArabic ?>',
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

        <script type="text/javascript">
            google.charts.load('current', {'packages': ['bar']});
            google.charts.setOnLoadCallback(drawMonthsTicketsData);
            var monthsTicketsData = <?php echo json_encode($FullMonthsTicketsArray); ?>;
            console.log(monthsTicketsData);
            function drawMonthsTicketsData() {
                var data = google.visualization.arrayToDataTable(monthsTicketsData);
                var options = {
                    title: 'طلبات   <?php echo $selected_building_name; ?>',
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
                    vAxis: {
                        title: 'عدد الطلبات'
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('currentSelectedBuildingReportDiv'));
                chart.draw(data, options);
            }
        </script>

    </head>
    <body>
        <input name="buildings_url" type="hidden" id="buildings_url" value="staff-buildings-reports">
        <div class="no-print">
            <a href="staff-home?segment=5">الرئيسية</a> | <a href="staff-reports">التقارير</a>
        </div>
        <div class="container-fluid">
            <h2>التقارير</h2>
            <br />
            إختر المبنى: <select id="building_report_select" name="type">  
                <!--<option value="0" >إختر</option>-->
                <?php
                foreach ($buildings as $building) {
                    ?>
                    <option value="<?php echo $building->building_id; ?>" <?php echo ($selected_building == $building->building_id) ? "selected" : "";  ?>><?php echo $building->building_name; ?></option>
                    <?php
                }
                ?>
            </select>
            
            <div class="row reports-page">
                <div class="col-xs-12 col-md-12">

                    <div class="">

                        <div class="chart_container first_chart_row">
                            <div id="currentSelectedBuildingReportDiv" class="chart_div_building"></div>
                        </div>
                        <div class="chart_container first_chart_row">
                            <div id="currentBuildingsReportDiv" class="chart_div"></div>
                            <div id="previousMonthBuildingsReportDiv" class="chart_div"></div>
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
                    <div id="loadingmessage" style="clear: both;">
                        <p>
                            <img id="img-load" style="width: 40px; height: 40px" src="<?php echo get_template_directory_uri() . '/images/loading_150.gif'; ?>">
                            جاري التحميل

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php get_footer(); ?>	
    </body>
</html>