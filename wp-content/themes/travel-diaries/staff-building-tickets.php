<?php
/*
  Template Name: staff-building-tickets.php
 */
checkStaffLoginUserInit();
get_header();

global $wpdb;
$logged_staff_user_details = $_SESSION['staff_user_details'];
$segment = $_GET["segment"];
$emp_username = $_GET["emp_username"];
$building = $_GET["building"];

$queryStatus = 0;
switch ($segment) {
    case 0:
        $currentStatus = "مفتوحة";
        $queryStatus = "WHERE mju_tickets.status = '0' AND building = '".$building."' ";
        break;
    case 1:
        $currentStatus = "مستلمة";
        $queryStatus = "WHERE mju_tickets.status = '1' AND building = '".$building."' ";
        break;
    case 2:
        $currentStatus = "تحت التنفيذ";
        $queryStatus = "WHERE mju_tickets.status = '2' AND building = '".$building."' ";
        break;
    case 3:
        $currentStatus = "معاد فتحها";
        $queryStatus = "WHERE mju_tickets.status = '3' AND building = '".$building."' ";
        break;
    case 4:
        $currentStatus = "مغلقة";
        $queryStatus = "WHERE mju_tickets.status = '4' AND building = '".$building."' ";
        break;
    default:
        case 5:
        $currentStatus = "الكل";
        $queryStatus = "WHERE building = '".$building."' ";
        break;
        break;
}
$building_details = $wpdb->get_results("SELECT * FROM mju_buildings WHERE building_id = '".$building."'");
$building_details = $building_details[0];
//////
$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus;
$total = count($wpdb->get_results($query));
$items_per_page = 20;
$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;

$offset = ( $page * $items_per_page ) - $items_per_page;
$latest_tickets = $wpdb->get_results($query . " ORDER BY create_date DESC LIMIT ${offset}, ${items_per_page}");
//var_dump($latest_tickets);
///////
$opened_tickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE opened = '0'");
?>
<div class="no-print">
    <a href="staff-home?segment=5">الرئيسية</a> | <a href="staff-buildings">عرض المباني</a>
</div>
<h4 dir="rtl" style="direction: rtl;"><?php echo "طلبات  &nbsp;&nbsp;" . $building_details->building_name; ?></h4>
<div id="new_tickets_div">
    <?php echo (count($opened_tickets) > 0) ? "لديك <span class='label label-warning'>".count($opened_tickets)."</span> طلب جديد" : ""; ?>
</div>
<br />
<div class="no-print" id="segment" >
    <a href="<?php echo 'staff-building-tickets?segment=5&building='.$building; ?>" class="<?php echo (($segment == 5)) ? "bordered-segment" : ""; ?>">الكل</a> | 
    <a href="<?php echo 'staff-building-tickets?segment=0&building='.$building; ?>" class="<?php echo (($segment == 0)) ? "bordered-segment" : ""; ?>">طلبات مفتوحة</a> | 
    <a href="<?php echo 'staff-building-tickets?segment=1&building='.$building; ?>" class="<?php echo (($segment == 1)) ? "bordered-segment" : ""; ?>">طلبات مستلمة</a> | 
    <a href="<?php echo 'staff-building-tickets?segment=2&building='.$building; ?>" class="<?php echo (($segment == 2)) ? "bordered-segment" : ""; ?>">طلبات تحت التنفيذ</a> | 
    <a href="<?php echo 'staff-building-tickets?segment=3&building='.$building; ?>" class="<?php echo (($segment == 3)) ? "bordered-segment" : ""; ?>">طلبات معاد فتحها</a> | 
    <a href="<?php echo 'staff-building-tickets?segment=4&building='.$building; ?>" class="<?php echo (($segment == 4)) ? "bordered-segment" : ""; ?>">طلبات مغلقة</a>
</div>

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
                    <th style="width: 3%;">النوع</th>
                    <th  style="width: 10%;">تاريخ فتح الطلب</th>
                    <th  style="width: 5%;">أمر العمل</th>
                    <th style="width: 4%;">الحالة</th>
                    <th style="width: 7%;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($latest_tickets as $emp_ticket) {
                    $trClass="";
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
                    $ticketBuilding = $wpdb->get_results("SELECT * FROM mju_buildings LEFT JOIN mju_regions ON mju_buildings.building_region = mju_regions.region_id WHERE mju_buildings.building_id='".$emp_ticket->building."'");
                    $ticketBuilding = $ticketBuilding[0];
                    $completePlaceDesc = $ticketBuilding->region_name . " - " . $ticketBuilding->building_name;
                    ?>
                    <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';       ?>">

                        <!--<td><?php // echo $i; ?></td>-->
                        <td><?php echo ($emp_ticket->opened == 0)? "<span class='label label-warning new_ticket_label'>جديد</span>" : ""; ?></td>
                        <td class="description_td"><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->title; ?></a></td>
                        <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->created_by_name ? $emp_ticket->created_by_name:$emp_ticket->created_by; ?></a></td>
                        <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $completePlaceDesc; ?></a></td>
                        <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->type_name;//$ticket_type[0]->name; ?></a></td>
                        <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->create_date; ?></a></td>
                        <td><a href="<?php echo 'staff-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->jo_number ? $emp_ticket->jo_number:"-" ; ?></a></td>
                        <td><?php echo $currentStatus; ?></td>
                        <td><?php if($emp_ticket->status != 4){ ?><a href="<?php echo 'staff-close-ticket?ticketID=' . $emp_ticket->ticket_id; ?>">إغلاق الطلب</a> | <?php } ?><a href="<?php echo 'staff-edit-ticket?ticketID=' . $emp_ticket->ticket_id; ?>">تعديل الطلب</a></td>
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
            echo paginate_links(array(
                'base' => add_query_arg('cpage', '%#%'),
                'format' => '',
                'prev_text' => __('السابق', 'textdomain'),
                'next_text' => __('التالي', 'textdomain'),
                'total' => ceil($total / $items_per_page),
                'current' => $page
            ));
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

<?php get_footer(); ?>	