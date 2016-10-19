<?php
/*
  Template Name: staff-close-ticket
 */
checkStaffLoginUserInit();
global $wpdb;

get_header();

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

$staff_user_details = $_SESSION["staff_user_details"];

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
                    <form id="" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/staff-close-ticket-action.php'; ?>">
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
                        التعليق: <input id="comment" type="text" placeholder="التعليق" name="comment" required />
                        <input name="staff_username" type="hidden" value="<?php echo $staff_user_details->username; ?>">
                        <input name="ticketID" type="hidden" value="<?php echo $ticketID; ?>" />
                        <input  type="submit" value="إغلاق"/>
                    </form>
                </div>
            </div>
        </div>				

    </div>

</div>

<?php get_footer(); ?>
    

