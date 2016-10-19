﻿<?php
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
$query = "SELECT * FROM mju_tickets LEFT JOIN mju_tickets_types ON mju_tickets.type = mju_tickets_types.type_id " . $queryStatus . " AND created_by='" . $logge_emp_username . "'";
$total = count($wpdb->get_results($query));
$items_per_page = 20;
$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;

$offset = ( $page * $items_per_page ) - $items_per_page;
$latest_tickets = $wpdb->get_results($query . " ORDER BY create_date DESC LIMIT ${offset}, ${items_per_page}");
//var_dump($latest_tickets);
///////

$emp_tickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE " . $queryStatus . " AND created_by='" . $logge_emp_username . "'");

function cmp($a, $b) {
    return $b->create_date > $a->create_date;
}

usort($emp_tickets, "cmp");
?>

<br />
<div class="no-print" id="segment" >
    
    <a href="emp-home?segment=0" class="<?php echo (!$segment || ($segment == 0)) ? "bordered-segment" : ""; ?>">طلبات مفتوحة</a> |
    <a href="emp-home?segment=1" class="<?php echo (($segment == 1)) ? "bordered-segment" : ""; ?>">طلبات مغلقة</a> | 
    <a href="add-ticket">تقديم طلب صيانة جديد</a>
</div>

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
                    <?php if(($segment == 1)){ ?><th style="width: 7%;"></th></td><?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($latest_tickets as $emp_ticket) {
                    $trClass="";
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
                    $ticketBuilding = $wpdb->get_results("SELECT * FROM mju_buildings LEFT JOIN mju_regions ON mju_buildings.building_region = mju_regions.region_id WHERE mju_buildings.building_id='".$emp_ticket->building."'");
                    $ticketBuilding = $ticketBuilding[0];
                    $completePlaceDesc = $ticketBuilding->region_name . " - " . $ticketBuilding->building_name;
                    ?>
                    <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';    ?>">

                        <!--<td><?php // echo $i; ?></td>-->
                        <td class="description_td"><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->title; ?></a></td>
                        <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $completePlaceDesc; ?></a></td>
                        <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->type_name; ?></a></td>
                        <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->create_date; ?></a></td>
                        <td><a href="<?php echo 'emp-ticket-details?ticketID=' . $emp_ticket->ticket_id; ?>"><?php echo $emp_ticket->jo_number ? $emp_ticket->jo_number:"-" ; ?></a></td>
                        <td><?php echo $currentStatus; ?></td>
                        <?php if(($segment == 1)){ ?><td><a href="<?php echo 'emp-ticket-reopen?ticketID=' . $emp_ticket->ticket_id; ?>">إعادة فتح الطلب</a></td><?php } ?>
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