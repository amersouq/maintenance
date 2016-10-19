<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Travel_Diaries
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
        <meta HTTP-EQUIV="Content-language" CONTENT="ar_AR">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"/>  
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"/>  
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/jQuery.print.js'; ?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/site-main.js'; ?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/jQuery.print.js'; ?>"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <div id="page" class="site">

            <header id="masthead" class="site-header" role="banner">

                <div class="header-t">
                    <div class="container">
                        <div id="mobile-header">
                            <!--<a id="top-menu-button" href="#top-menu"><span class="fa fa-bars"></span></a>-->
                        </div>
                        <?php if (has_nav_menu('secondary')) { ?>
                            <nav class="top-menu">
                                <?php // wp_nav_menu(array('theme_location' => 'secondary', 'menu_id' => 'secondary-menu', 'fallback_cb' => false)); ?>
                            </nav>
                            <?php
                        }
                        if (get_theme_mod('travel_diaries_ed_social'))
//                            do_action('travel_diaries_social');
                            
                            ?>

                    </div><!-- .container -->
                </div><!-- .header-t -->

                <div class="header-b">
                    <div class="container">

                        <div class="site-branding">

                            <?php
                            if (function_exists('has_custom_logo') && has_custom_logo()) {
                                the_custom_logo();
                            }
                            ?>

                            <h1 class="site-title">
                                <!--<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">-->
                                <?php bloginfo('name'); ?>
                                <!--</a>-->
                            </h1>

                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                                ?>
                                <p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
                                <?php
                            endif;
                            ?>
                            <p class="site-title no-print"><?php
                                if ($_SESSION["staff_user_details"]) {
                                    echo "مرحبا, " . $_SESSION["staff_user_details"]->name;
                                } else if ($_SESSION["emp_name"]) {
                                    echo "مرحبا, " . $_SESSION["emp_name"];
                                }
                                ?>
                            </p>
                        </div><!-- .site-branding -->

                        <?php if (get_theme_mod('travel_diaries_ed_header_info')) do_action('travel_diaries_header_info'); ?>
                        <?php
                        if (!is_page('20') && $_SESSION["staff_user_details"]) {
                            ?>
                            <form id="logout_form" class="no-print" align="right" name="form1" method="post" action="<?php echo get_template_directory_uri() . '/staff-logout.php'; ?>">
                                <label class="logoutLblPos">
                                    <input name="submit2" type="submit" id="submit2" value="خروج">
                                </label>
                            </form>

                        <?php } ?>
                    </div><!-- .container -->
                </div><!-- -->

            </header><!-- #masthead -->

            <div class="navigation">
                <div class="container">
                    <nav id="site-navigation" class="main-navigation" role="navigation">
                        <?php // wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu'));  ?>
                    </nav><!-- #site-navigation -->	
                </div><!-- .container -->
            </div><!-- .navigation -->

            <?php if (!is_page_template('template-home.php')) { ?>        
                <div id="content" class="site-content">
                    <div class="container">
                        <div class="row">
                        <?php } ?>