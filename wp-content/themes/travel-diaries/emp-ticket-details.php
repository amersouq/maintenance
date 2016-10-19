<?php
/*
  Template Name: emp-ticket-details
 */
checkLoginUser();

get_header();
global $wpdb;

$ticketID = $_GET["ticketID"];
$ticket_details = $wpdb->get_results("SELECT * FROM mju_tickets WHERE ticket_id='" . $ticketID . "'");
$ticket_details = $ticket_details[0];
$status = "";
switch ($ticket_details->status) {
    case 0:
        $status = "مفتوح";
        break;
    case 1:
        $status = "تم الإستلام";
        break;
    case 2:
        $status = "تحت التنفيذ";
        break;
    case 3:
        $status = "أعيد فتح الطلب";
        break;
    case 4:
        $status = "تم إنهاء العمل";
        break;

    default:
        break;
}
$ticket_type = $wpdb->get_results("SELECT * FROM mju_tickets_types WHERE type_id='" . $ticket_details->type . "'");
$ticket_log = $wpdb->get_results("SELECT * FROM mju_tickets_log WHERE ticket_id='" . $ticket_details->ticket_id . "'");

$ticketBuilding = $wpdb->get_results("SELECT * FROM mju_buildings LEFT JOIN mju_regions ON mju_buildings.building_region = mju_regions.region_id WHERE mju_buildings.building_id='" . $ticket_details->building . "'");
$ticketBuilding = $ticketBuilding[0];
$completePlaceDesc = $ticketBuilding->region_name . " - " . $ticketBuilding->building_name;
?>

<div class="no-print">
    <a href="emp-home">الرئيسية</a> | <a href="" id="print_ticket_href" >طباعة</a><?php if ($ticket_details->status == 4) { ?> | <a class="no-print" href="<?php echo 'emp-ticket-reopen?ticketID=' . $ticket_details->ticket_id; ?>">إعادة فتح</a> <?php } ?>
</div>

<div class="col-xs-12 col-md-12 ">
    <h3><?php echo $ticket_details->title; ?></h3>

    <div class="ticket_details">
        <div class="">
            <div class=""><label class="title_label"> الوصف: </label>&nbsp;&nbsp;<?php echo" " . $ticket_details->description; ?></div>
        </div>	
        <br />
        <div class="">
            <div class=""><label class="title_label"> الحالة: </label>&nbsp;&nbsp;<?php echo $status; ?></div>
        </div>
        <br />
        <div class="">
            <div class=""><label class="title_label"> رقم أمر العمل: </label>&nbsp;&nbsp;<?php echo $ticket_details->jo_number; ?></div>
        </div>
        <br />
        <div class="">
            <div class=""><label class="title_label"> المبنى: </label>&nbsp;&nbsp;<?php echo $completePlaceDesc; ?></div>
        </div>
        <br />
        <div class="">
            <div class=""><label class="title_label"> المكان: </label>&nbsp;&nbsp;<?php echo $ticket_details->place; ?></div>
        </div>
        <br />
        <div class="">
            <div class=""><label class="title_label"> الغرفة: </label>&nbsp;&nbsp;<?php echo $ticket_details->room_number; ?></div>
        </div>
        <div class="">
            <div class=""><label class="title_label"> دورة الطلب: </label>&nbsp;&nbsp;
                <?php
                foreach ($ticket_log as $record) {
                    if (($record->status_from == 0) && ($record->status_to == 0))
                        echo '<br />*' . 'تم فتح الطلب بواسطة : ' . $record->changed_by . '&nbsp;&nbsp; بتاريخ : ' . $record->action_date;
                    else if (($record->status_from == 4) && ($record->status_to == 3))
                        echo '<br />*' . 'تم إعادة فتح الطلب بواسطة : ' . $record->changed_by . '&nbsp;&nbsp; بتاريخ : ' . $record->action_date;
                    else if ($record->status_to == 4)
                        echo '<br />*' . 'تم إغلاق الطلب بواسطة عضو بإدارة التشغيل و الصيانة' . '&nbsp;&nbsp; بتاريخ : ' . $record->action_date;
                }
                ?>
            </div>
        </div>
    </div>
</div>