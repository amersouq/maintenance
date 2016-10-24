<?php
/*
  Template Name: emp-home
 */
checkLoginUser();

get_header();
global $wpdb;
//var_dump($_SESSION);
$logge_emp_username = $_SESSION['emp_username'];
$logge_emp_name = $_SESSION['emp_name'];
$segment = $_GET["segment"];

$queryStatus = 0;
switch ($segment) {
    case 0:
        $currentStatus = "مفتوحة";
        $queryStatus = "WHERE mju_tickets.status<'4'";
        break;
    case 1:
        $currentStatus = "مغلقة";
        $queryStatus = "WHERE mju_tickets.status='4'";
        break;


    default:
        break;
}
//////
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
$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus . " AND created_by='" . $logge_emp_username . "'";
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
//var_dump($latest_tickets);
//////
//$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus . " AND created_by='" . $logge_emp_username . "'";
//$total = count($wpdb->get_results($query));
//$items_per_page = 20;
//$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
//
//$offset = ( $page * $items_per_page ) - $items_per_page;
//$latest_tickets = $wpdb->get_results($query . " ORDER BY create_date DESC LIMIT ${offset}, ${items_per_page}");

$emp_tickets = $wpdb->get_results("SELECT * FROM mju_tickets " . $queryStatus . " AND created_by='" . $logge_emp_username . "'");

function cmp($a, $b) {
    return $b->create_date > $a->create_date;
}

usort($emp_tickets, "cmp");
$page_url = get_template_directory_uri() . '/emp-tickets-list.php?segment=' . $segment;
?>

<div id="alert_container">
    <div class="alert alert-success">
        <span class="closebtn">&times;</span>
        <strong>تم إضافة الطلب بنجاح.</strong> 
    </div>
</div>
<div class="no-print emp_segment" id="segment" >

    <a id="all_tickets_link" href="emp-home?segment=0" data-segment="0" data-url="<?php echo get_template_directory_uri() . '/emp-tickets-list.php?segment=0'; ?>" class="emp_filter_list <?php echo (!$segment || ($segment == 0)) ? "bordered-segment" : ""; ?>">طلبات مفتوحة</a> |
    <a href="emp-home?segment=1" data-segment="1" data-url="<?php echo get_template_directory_uri() . '/emp-tickets-list.php?segment=1'; ?>" class="emp_filter_list <?php echo (($segment == 1)) ? "bordered-segment" : ""; ?>">طلبات مغلقة</a> | 
    <a id="add_ticket_link" href="add-ticket">تقديم طلب صيانة جديد</a>
</div>
<br />
<div id="ajax_container">
    <input id="hiddenURL" type="hidden" value="<?php echo get_template_directory_uri() . '/emp-tickets-list.php?segment=' . $segment; ?>">
    <input id="hiddenURLAddTicket" type="hidden" value="<?php echo get_template_directory_uri() . '/add-ticket-template.php'; ?>">
    <div class="col-xs-12 col-md-12">
        <?php
        if ($latest_tickets && (count($latest_tickets) > 0)) {
            ?>
            <table class="one_employee_manpower" id="one_employee_manpower_print">
                <thead>
                    <tr><th colspan="11">طلبات الصيانة ( <?php echo $currentStatus; ?> )</th></tr>
                    <tr>
                        <!--<th style="width: 3%;">الرقم</th>-->
                        <th style="width: 15%;">الوصف</th>
                        <th  style="width: 10%;">المكان</th>
                        <th style="width: 5%;">النوع</th>
                        <th  style="width: 5%;">تاريخ فتح الطلب</th>
                        <th  style="width: 5%;">أمر العمل</th>
                        <th style="width: 3%;">الحالة</th>
                        <?php // if (($segment == 1)) { ?><!--<th style="width: 7%;"></th></td><?php // } ?>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($latest_tickets as $emp_ticket) {
                        $trClass = "";
//                    var_dump($emp_ticket);
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
                        <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';            ?>">

                                                                                        <!--<td><?php // echo $i;         ?></td>-->
                            <td class="description_td"><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->title; ?></a></td>
                            <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $completePlaceDesc; ?></a></td>
                            <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->type_name; ?></a></td>
                            <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->create_date; ?></a></td>
                            <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->jo_number ? $emp_ticket->jo_number : "-"; ?></a></td>
                            <td><?php echo $currentStatus; ?></td>
                            <?php // if (($segment == 1)) { ?><!--<td><a href="<?php // echo 'emp-ticket-reopen?ticketID=' . $emp_ticket->ticket_id; ?>">إعادة فتح الطلب</a></td><?php // } ?>-->
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
                echo '<div align="center">';
                echo paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
                echo '</div>';
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
<!--<div class="alert alert-success" id="myAlert">
    <a href="#" class="close">&times;</a>
    <strong>Success!</strong> This alert box could indicate a successful or positive action.
  </div>-->
<div id="loadingmessage">
    <p>
        <img id="img-load" src="<?php echo get_template_directory_uri() . '/images/loading_150.gif'; ?>">
        جاري التحميل

    </p>
</div>

<?php get_footer(); ?>