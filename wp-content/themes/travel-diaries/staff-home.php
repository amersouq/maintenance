<?php
/*
  Template Name: staff-home
 */
checkStaffLoginUserInit();
get_header();

global $wpdb;
$logged_staff_user_details = $_SESSION['staff_user_details'];
$segment = $_GET["segment"];

$queryStatus = 0;
switch ($segment) {
    case 0:
        $currentStatus = "مفتوحة";
        $queryStatus = "WHERE mju_tickets.status = '0'";
        break;
    case 1:
        $currentStatus = "مستلمة";
        $queryStatus = "WHERE mju_tickets.status = '1'";
        break;
    case 2:
        $currentStatus = "تحت التنفيذ";
        $queryStatus = "WHERE mju_tickets.status = '2'";
        break;
    case 3:
        $currentStatus = "معاد فتحها";
        $queryStatus = "WHERE mju_tickets.status = '3'";
        break;
    case 4:
        $currentStatus = "مغلقة";
        $queryStatus = "WHERE mju_tickets.status = '4'";
        break;
    case 5:
        $currentStatus = "الكل";
        $queryStatus = "WHERE mju_tickets.status < '5'";
        break;
    default:
        break;
}
if($_GET["emp_username"])
    $queryStatus .= " AND created_by = '".$_GET["emp_username"]."'";
if($_GET["building"])
    $queryStatus .= " AND building = '".$_GET["building"]."'";
//get total number of records from database
//Get page number from Ajax
if (isset($_POST["page"])) {
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if (!is_numeric($page_number)) {
        die('Invalid page number!');
    } //incase of invalid page number
} else {
    $page_number = 1; //if there's no page number, set it to 1
}

//get total number of records from database
$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus;
$get_total_rows = count($wpdb->get_results($query)); //hold total records in variable
//break records into pages
$item_per_page = 10;
$total_pages = ceil($get_total_rows / $item_per_page);
//position of records
if ($item_per_page > $get_total_rows)
    $page_position = 0;
else
    $page_position = intval(abs((($page_number - 1) * $item_per_page)));
//Limit our results within a specified range. 
$latest_tickets = $wpdb->get_results($query . " ORDER BY create_date DESC LIMIT ${page_position}, ${item_per_page}");

//$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus;
//$total = count($wpdb->get_results($query));
//$items_per_page = 20;
//$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
//$offset = ( $page * $items_per_page ) - $items_per_page;
//$latest_tickets = $wpdb->get_results($query . " ORDER BY create_date DESC LIMIT ${offset}, ${items_per_page}");
///////
$opened_tickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE opened = '0'");
?>
<div id="alert_container">
    <div class="alert alert-success">
        <span class="closebtn">&times;</span>
        <strong>تم إضافة الطلب بنجاح.</strong> 
    </div>
</div>
<div class="no-print">
    <a href="staff-emps">عرض الموظفين</a> | <a href="staff-buildings">عرض المباني</a> | <a href="staff-reports">تقارير</a> | <a href="staff-change-password">إعدادات</a>
</div>

<div id="new_tickets_div">
    <?php echo (count($opened_tickets) > 0) ? "لديك <span class='label label-warning'>" . count($opened_tickets) . "</span> طلب جديد" : ""; ?>
</div>
<br />
<div class="no-print" id="segment" >
    <a href="<?php echo 'staff-home?segment=5'; ?>" data-segment="5" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=5&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 5)) ? "bordered-segment" : ""; ?>">الكل</a> | 
    <a href="<?php echo 'staff-home?segment=0'; ?>" data-segment="0" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=0&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 0)) ? "bordered-segment" : ""; ?>">طلبات مفتوحة</a> | 
    <a href="<?php echo 'staff-home?segment=1'; ?>" data-segment="1" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=1&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 1)) ? "bordered-segment" : ""; ?>">طلبات مستلمة</a> | 
    <a href="<?php echo 'staff-home?segment=2'; ?>" data-segment="2" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=2&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 2)) ? "bordered-segment" : ""; ?>">طلبات تحت التنفيذ</a> | 
    <a href="<?php echo 'staff-home?segment=3'; ?>" data-segment="3" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=3&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 3)) ? "bordered-segment" : ""; ?>">طلبات معاد فتحها</a> | 
    <a href="<?php echo 'staff-home?segment=4'; ?>" data-segment="4" data-url="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=4&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>" class="filter_list <?php echo (($segment == 4)) ? "bordered-segment" : ""; ?>">طلبات مغلقة</a>
</div>
<div id="ajax_container">
    <input id="hiddenURL" type="hidden" value="<?php echo get_template_directory_uri() . '/staff-tickets-list.php?segment=' . $segment . '&building='.$_GET["building"].'&emp_username='.$_GET["emp_username"]; ?>">
    <div class="col-xs-12 col-md-12">
        <?php
        if ($latest_tickets && (count($latest_tickets) > 0)) {
            ?>
            <table class="one_employee_manpower" id="one_employee_manpower_print">
                <thead>
                    <tr><th colspan="11">طلبات الصيانة ( <?php echo $currentStatus; ?> )</th></tr>
                    <tr>
                        <th style="width: 3%;"></th>
                        <th style="width: 15%;">الوصف</th>
                        <th  style="width: 10%;">المنشئ</th>
                        <th  style="width: 13%;">المكان</th>
                        <th style="width: 5%;">النوع</th>
                        <th  style="width: 10%;">تاريخ فتح الطلب</th>
                        <th  style="width: 5%;">أمر العمل</th>
                        <th style="width: 3%;">الحالة</th>
                        <th style="width: 7%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($latest_tickets as $emp_ticket) {
                        $trClass = "";
                        switch ($emp_ticket->status) {
                            case 0:
                                $currentStatus = "مفتوح";
                                break;
                            case 1:
                                $currentStatus = "مستلم";
                                break;
                            case 2:
                                $currentStatus = "تحت التنفيذ";
                                $trClass = "under_progress_ticker_tr";
                                break;
                            case 3:
                                $currentStatus = "أعيد فتحه";
                                $trClass = "reopen_ticker_tr";
                                break;
                            case 4:
                                $currentStatus = "تم إنجاز العمل";
                                break;
                            default:
                                break;
                        }
                        $ticketBuilding = $wpdb->get_results("SELECT * FROM mju_buildings LEFT JOIN mju_regions ON mju_buildings.building_region = mju_regions.region_id WHERE mju_buildings.building_id='" . $emp_ticket->building . "'");
                        $ticketBuilding = $ticketBuilding[0];
                        $completePlaceDesc = $ticketBuilding->region_name . " - " . $ticketBuilding->building_name;
                        ?>
                        <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';           ?>">

                                                                <!--<td><?php // echo $i;      ?></td>-->
                            <td><?php echo ($emp_ticket->opened == 0) ? "<span class='label label-warning new_ticket_label'>جديد</span>" : "<img style='opacity:0.5;' width='20' height='20' src='".get_site_url() ."/wp-content/uploads/2016/10/green-icon-mark-round-check-correct.png'>"; ?></td>
                            <td class="description_td"><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->title; ?></a></td>
                            <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->created_by_name ? $emp_ticket->created_by_name : $emp_ticket->created_by; ?></a></td>
                            <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $completePlaceDesc; ?></a></td>
                            <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->type_name; //$ticket_type[0]->name;      ?></a></td>
                            <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->create_date; ?></a></td>
                            <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->jo_number ? $emp_ticket->jo_number : "-"; ?></a></td>
                            <td><?php echo $currentStatus; ?></td>
                            <td><?php if ($emp_ticket->status != 4) { ?><a href="<?php echo 'staff-close-ticket?ticketID=' . $emp_ticket->ticket_id; ?>">إغلاق الطلب</a> | <?php } ?><a href="<?php echo 'staff-edit-ticket?ticketID=' . $emp_ticket->ticket_id; ?>">تعديل الطلب</a></td>
                        </tr>


                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
            <br />
            <div class="custom_pag_container">
                <?php
//                echo paginate_links(array(
//                    'base' => add_query_arg('cpage', '%#%'),
//                    'format' => '',
//                    'prev_text' => __('السابق', 'textdomain'),
//                    'next_text' => __('التالي', 'textdomain'),
//                    'total' => ceil($total / $items_per_page),
//                    'current' => $page
//                ));
                if ($get_total_rows > $item_per_page) {
                    echo '<div align="center">';
                    echo paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
                    echo '</div>';
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <h4>لا يوجد طلبات</h4>
            <?php
        }
        ?>

    </div> 
</div>
<!--<div id='loadingmessage' style='display:none'>
    <img width="40px" height="40px" src="<?php // echo get_template_directory_uri() . '/images/loading_150.gif'; ?>"/>
</div>-->
<div id="loadingmessage">
    <p>
        <img id="img-load" style="width: 40px; height: 40px" src="<?php echo get_template_directory_uri() . '/images/loading_150.gif'; ?>">
        جاري التحميل
        
    </p>
</div>

<?php get_footer(); ?>	