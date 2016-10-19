<?php
/*
  Template Name: emp-ticket-reopen
 */
checkLoginUser();
global $wpdb;


$ticketID = $_GET["ticketID"];

get_header();
?>
<a href="emp-home">الرئيسية</a> | <a href="<?php echo "emp-ticket-details?ticketID=" . $ticketID; ?>">الرجوع الى الطلب</a>

<div class="container-fluid">

    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="manpower-page">
                <div id="add_man_form">
                    <form id="" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/emp-ticket-reopen-action.php'; ?>">

                        التعليق: <input id="comment" type="text" placeholder="التعليق" name="comment" required />
                        <input name="emp_username" type="hidden" value="<?php echo $_SESSION['emp_username']; ?>">
                        <input name="ticketID" type="hidden" value="<?php echo $ticketID; ?>" />
                        <input  type="submit" value="إعادة فتح"/>
                    </form>
                </div>
            </div>
        </div>				

    </div>

</div>

<?php get_footer(); ?>
    

