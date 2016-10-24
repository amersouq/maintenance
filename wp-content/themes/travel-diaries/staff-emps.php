<?php
/*
  Template Name: staff-emps
 */
checkStaffLoginUserInit();
get_header();

global $wpdb;
$logged_staff_user_details = $_SESSION['staff_user_details'];

$emp_username = $_GET["emp_username"];

$query = "SELECT  * FROM mju_tickets GROUP BY created_by" ;
$total = count($wpdb->get_results($query));
$items_per_page = 20;
$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;

$offset = ( $page * $items_per_page ) - $items_per_page;
$emps = $wpdb->get_results($query . " LIMIT ${offset}, ${items_per_page}");

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
    if ($emps && (count($emps) > 0)) {
        ?>
        <table class="one_employee_manpower" id="one_employee_manpower_print">
            <thead>
                <tr style="font-size: 20px;">
                    <th style="width: 2%;">الرقم</th>
                    <th style="width: 15%;">إسم المستخدم</th>
                    <th  style="width: 10%;">عدد الطلبات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($emps as $emp) {
                    $empTickets = $wpdb->get_results("SELECT  * FROM mju_tickets WHERE created_by = '".$emp->created_by."' ");
                    ?>
                    <tr class="<?php echo $trClass; ?> <?php // echo ($i % 2) ? 'even_row' : '';       ?>">

                        <td><?php echo $i; ?></td>
                        <td style="font-size: 20px;"><a href="<?php echo 'staff-home?segment=5&emp_username=' . $emp->created_by; ?>"><?php echo $emp->created_by_name ? $emp->created_by_name:$emp->created_by; ?></a></td>
                        <td style="font-size: 20px;"><a href="<?php echo 'staff-home?segment=5&emp_username=' . $emp->created_by; ?>"><?php echo count($empTickets) ? count($empTickets) : "لا يوجد" ?></a></td>
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