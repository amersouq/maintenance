<?php
/*
  Template Name: staff-buildings
 */
checkStaffLoginUserInit();
get_header();

global $wpdb;
$logged_staff_user_details = $_SESSION['staff_user_details'];

$emp_username = $_GET["emp_username"];

$buildings = $wpdb->get_results("SELECT  * FROM mju_buildings");
$opened_tickets = $wpdb->get_results("SELECT * FROM mju_tickets WHERE opened = '0'");
?>
<div class="no-print">
    <a href="staff-home?segment=5">الرئيسية</a>
</div>
<div id="new_tickets_div">
    <?php echo (count($opened_tickets) > 0) ? "لديك <span class='label label-warning'>".count($opened_tickets)."</span> طلب جديد" : ""; ?>
</div>
<br />

<div class="col-xs-12 col-md-12">
    <?php
    if ($buildings && (count($buildings) > 0)) {
        ?>
        <table class="one_employee_manpower" id="one_employee_manpower_print">
            <thead>
                <tr style="font-size: 20px;">
                    <th style="width: 2%;">الرقم</th>
                    <th style="width: 15%;">المبنى</th>
                    <th  style="width: 10%;">عدد الطلبات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($buildings as $building) {
                    $empTickets = $wpdb->get_results("SELECT  * FROM mju_tickets WHERE building = '".$building->building_id."' ");
                    ?>
                    <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';       ?>">

                        <td><?php echo $i; ?></td>
                        <td style="font-size: 20px;"><a href="<?php echo 'staff-home?segment=5&building=' . $building->building_id; ?>"><?php echo $building->building_name; ?></a></td>
                        <td style="font-size: 20px;"><a href="<?php echo 'staff-home?segment=5&building=' . $building->building_id; ?>"><?php echo count($empTickets) ? count($empTickets) : "لا يوجد"; ?></a></td>
                    </tr>


                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <?php
    } else {
        ?>
        <h4>لا يوجد طلبات</h4>
        <?php
    }
    ?>

</div> 

<?php get_footer(); ?>	