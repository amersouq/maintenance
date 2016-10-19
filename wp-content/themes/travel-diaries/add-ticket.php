<?php
/*
  Template Name: add-ticket
 */
checkLoginUser();

get_header();
global $wpdb;

$types = $wpdb->get_results("SELECT * FROM mju_tickets_types");
$regions = $wpdb->get_results("SELECT * FROM mju_regions");
?>

<a href="emp-home">الرئيسية</a>
<br /><br />
<div class="container-fluid">

    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="manpower-page">
                <div id="add_man_form">
                    <form id="" method="post" onsubmit="" action="<?php echo get_template_directory_uri() . '/add-ticket-action.php'; ?>">

                        الموضوع: <input id="subject" type="text" placeholder="العنوان" name="subject" required autofocus />
                        وصف المشكلة: <input id="description" type="text" placeholder="الوصف" name="description" required />
                        نوع طلب الصيانة:
                        <br />
                        <select id="type" name="type">  
                            <?php
                            //var_dump($employees);
                            foreach ($types as $type) {
                                ?>
                                <option value="<?php echo $type->type_id; ?>" ><?php echo $type->type_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <br />
                        المنطقة:
                        <br />
                        <select id="region_select" name="region_select">  
                            <option value="0" >أخرى</option>
                            <?php
                            //var_dump($employees);
                            foreach ($regions as $region) {
                                ?>
                                <option value="<?php echo $region->region_id; ?>" ><?php echo $region->region_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <br />
                        المبنى:
                        <br />
                        
                        <select id="building" name="building"></select>
                        <div id='loadingmessage' style='display:none'>
                            <img src="<?php echo get_template_directory_uri() . '/images/loading_150.gif'; ?>"/>
                        </div>
                        <br />
                        وصف المكان: <input id="place" type="text" placeholder="وصف المكان" name="place" required />
                        رقم الغرفة: <input id="room" type="text" placeholder="رقم الغرفة" name="room"  />
                        الجوال: <input id="phone" type="number" placeholder="050" name="phone" required autofocus />

                        <input name="emp_username" type="hidden" value="<?php echo $_SESSION['emp_username']; ?>">
                        <input name="emp_name" type="hidden" value="<?php echo $_SESSION['emp_name']; ?>">
                        <input name="buildings_url" type="hidden" id="buildings_url" value="<?php echo get_template_directory_uri() . '/getRegionBuildings.php'; ?>">
                        <!--<button type="submit" value="Submit" id="login_button">LOGIN</button>-->
                        <input  type="submit" value="إضافة الطلب"/>
                    </form>
                </div>
            </div>
        </div>				

    </div>

</div>

<?php get_footer(); ?>	