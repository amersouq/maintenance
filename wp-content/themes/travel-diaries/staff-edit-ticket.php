<?php
/*
  Template Name: staff-edit-ticket
 */
checkStaffLoginUserInit();

get_header();
global $wpdb;
$ticketID = $_GET["ticketID"];

$staff_user_details = $_SESSION["staff_user_details"];
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

$ticketBuilding = $wpdb->get_results("SELECT * FROM mju_buildings LEFT JOIN mju_regions ON mju_buildings.building_region = mju_regions.region_id WHERE mju_buildings.building_id='" . $ticket_details->building . "'");
$ticketBuilding = $ticketBuilding[0];
$completePlaceDesc = $ticketBuilding->region_name . " - " . $ticketBuilding->building_name;

/* Change ststus to openned */
$wpdb->update(
        'mju_tickets', // Table
        array(
    'opened' => 1,
        ), // Array of key(col) => val(value to update to)
        array(
    'ticket_id' => $ticketID
        )
);
?>
<a href="staff-home?segment=5">الرئيسية</a> | <a href="<?php echo "staff-ticket-details?ticketID=" . $ticketID; ?>">الرجوع الى الطلب</a>
<br /><br />
<div class="container-fluid">

    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="manpower-page">
                <div id="add_man_form">
                    <form id="" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/staff-edit-ticket-action.php'; ?>">

                        <div class="ticket_details">
                            <div class="">
                                <div class=""><label class="title_label"> وصف المشكلة: </label>&nbsp;&nbsp;<?php echo" " . $ticket_details->description; ?></div>
                            </div>	
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> الحالة: </label>&nbsp;&nbsp;<?php echo $status; ?></div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> المنشئ: </label>&nbsp;&nbsp;<?php echo $ticket_details->created_by_name ? $ticket_details->created_by_name : $ticket_details->created_by; ?></div>
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
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> الجوال: </label>&nbsp;&nbsp;<?php echo $ticket_details->creator_phone ? $ticket_details->creator_phone : "لا يوجد"; ?></div>
                            </div>
                            <br />
                            حالة الطلب: &nbsp;&nbsp;
                            <select id="type" name="status">  
                                <option value="0" <?php echo $ticket_details->status == 0 ? 'selected' : '' ?>>مفتوح</option>
                                <option value="1" <?php echo $ticket_details->status == 1 ? 'selected' : '' ?>>تم الإستلام</option>
                                <option value="2" <?php echo $ticket_details->status == 2 ? 'selected' : '' ?>>تحت التنفيذ</option>
                                <option value="3" <?php echo $ticket_details->status == 3 ? 'selected' : '' ?>>معاد فتحه</option>
                                <option value="4" <?php echo $ticket_details->status == 4 ? 'selected' : '' ?>>تم إنجاز العمل</option>
                            </select>
                            <br />
                            رقم أمر العمل: <input id="jo_num" type="text" placeholder="WO.1366" name="jo_num" value="<?php echo $ticket_details->jo_number; ?>" />
                            <input name="staff_username" type="hidden" value="<?php echo $staff_user_details->username; ?>">
                            <input name="ticketID" type="hidden" value="<?php echo $ticketID; ?>" />
                            <input  type="submit" value="حفظ"/>
                    </form>
                </div>
            </div>
        </div>				

    </div>

</div>

<?php get_footer(); ?>
    

