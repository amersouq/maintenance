<?php
/*
  Template Name: staff-change-password
 */
checkStaffLoginUserInit();
global $wpdb;

get_header();

$userDetails = $_SESSION["staff_user_details"];
?>
<a href="staff-home?segment=5">الرئيسية</a>
<br /><br />
<div class="container-fluid">

    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="manpower-page">
                <div id="add_man_form">
                    <form id="change_password_form" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/staff-change-password-action.php'; ?>">
                        <div class="">
                            <div class=""><label class="title_label"> الإسم: </label>&nbsp;&nbsp;<?php echo" " . $userDetails->name; ?></div>
                        </div>	
                        <br />
                        <div class="">
                            <div class=""><label class="title_label"> إسم المستخدم: </label>&nbsp;&nbsp;<?php echo $userDetails->username; ?></div>
                        </div>
                        <br />
                        كلمة المرور: <input id="password" type="password" placeholder="كلمة المرور" name="password" required />
                        تأكيد كلمة المرور: <input id="confirm_password" type="password" placeholder="كلمة المرور" name="confirm_password" required />
                        <input name="userID" type="hidden" value="<?php echo $userDetails->id; ?>" />
                        <input name="url" id="url" type="hidden" value="<?php echo get_template_directory_uri() . '/staff-change-password-action.php'; ?>" />
                        <input name="redirect_url" id="redirect_url" type="hidden" value="<?php echo get_site_url(); ?>" />
                        <input  type="submit" value="تعديل"/>
                    </form>
                </div>
            </div>
        </div>				

    </div>

</div>

<?php get_footer(); ?>
    

