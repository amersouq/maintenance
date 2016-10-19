<?php

/**
 * Travel Diaries functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Travel_Diaries
 */
if (!function_exists('travel_diaries_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function travel_diaries_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Travel Diaries, use a find and replace
         * to change 'travel-diaries' to the name of your theme in all the template files.
         */
        load_theme_textdomain('travel-diaries', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'travel-diaries'),
            'secondary' => esc_html__('Secondary', 'travel-diaries'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         * See https://developer.wordpress.org/themes/functionality/post-formats/
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('travel_diaries_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        /* Custom Logo */
        add_theme_support('custom-logo', array(
            'header-text' => array('site-title', 'site-description'),
        ));

        // Custom Image Sizes
        add_image_size('travel-diaries-post-thumb', 68, 53, true);
        add_image_size('travel-diaries-feat-thumb', 360, 314, true);
        add_image_size('travel-diaries-image-with-sidebar', 750, 437, true);
        add_image_size('travel-diaries-image-full-width', 1170, 437, true);
        add_image_size('travel-diaries-blog-archive', 264, 231, true);
        add_image_size('travel-diaries-blog', 360, 250, true);
        add_image_size('travel-diaries-articles', 360, 314, true);
        add_image_size('travel-diaries-guide', 360, 420, true);
        add_image_size('travel-diaries-banner', 1920, 548, true);
    }

endif;
add_action('after_setup_theme', 'travel_diaries_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function travel_diaries_content_width() {
    $GLOBALS['content_width'] = apply_filters('travel_diaries_content_width', 750);
}

add_action('after_setup_theme', 'travel_diaries_content_width', 0);

/**
 * Adjust content_width value according to template.
 *
 * @return void
 */
function travel_diaries_template_redirect_content_width() {

    // Full Width in the absence of sidebar.
    if (is_page()) {
        $sidebar_layout = travel_diaries_sidebar_layout();
        if (( $sidebar_layout == 'no-sidebar' ) || !( is_active_sidebar('right-sidebar') ))
            $GLOBALS['content_width'] = 1170;
    }elseif (!( is_active_sidebar('right-sidebar') )) {
        $GLOBALS['content_width'] = 1170;
    }
}

add_action('template_redirect', 'travel_diaries_template_redirect_content_width');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function travel_diaries_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Right Sidebar', 'travel-diaries'),
        'id' => 'right-sidebar',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer One', 'travel-diaries'),
        'id' => 'footer-one',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Two', 'travel-diaries'),
        'id' => 'footer-two',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Three', 'travel-diaries'),
        'id' => 'footer-three',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Four', 'travel-diaries'),
        'id' => 'footer-four',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Banner Widget', 'travel-diaries'),
        'id' => 'banner-widget',
        'description' => esc_html__('Custom Use for Newsletter Plugin', 'travel-diaries'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'travel_diaries_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function travel_diaries_scripts() {
    $travel_diaries_query_args = array(
        'family' => 'Open+Sans:400,400italic,700,600italic,600',
    );
    wp_enqueue_style('travel-diaries-google-fonts', add_query_arg($travel_diaries_query_args, "//fonts.googleapis.com/css"));
    wp_enqueue_style('travel-diaries-font-awesome', get_template_directory_uri() . '/css/font-awesome.css');
    wp_enqueue_style('travel-diaries-sidr-style', get_template_directory_uri() . '/css/jquery.sidr.css');
    wp_enqueue_style('travel-diaries-meanmenu-style', get_template_directory_uri() . '/css/meanmenu.css');
    wp_enqueue_style('travel-diaries-jcf-style', get_template_directory_uri() . '/css/jcf.css');
    wp_enqueue_style('travel-diaries-style', get_stylesheet_uri(), '', '1.1');

    wp_enqueue_script('travel-diaries-ie', get_template_directory_uri() . '/js/ie.js', array('jquery'), '3.7.2', true);
    wp_enqueue_script('travel-diaries-jcf', get_template_directory_uri() . '/js/jcf.js', array('jquery'), '1.2.0', true);
    wp_enqueue_script('travel-diaries-jcf-checkbox', get_template_directory_uri() . '/js/jcf.checkbox.js', array('jquery', 'travel-diaries-jcf'), '1.2.0', true);
    wp_enqueue_script('travel-diaries-jcf-file', get_template_directory_uri() . '/js/jcf.file.js', array('jquery', 'travel-diaries-jcf'), '1.2.0', true);
    wp_enqueue_script('travel-diaries-jcf-radio', get_template_directory_uri() . '/js/jcf.radio.js', array('jquery', 'travel-diaries-jcf'), '1.2.0', true);
    wp_enqueue_script('travel-diaries-jcf-select', get_template_directory_uri() . '/js/jcf.select.js', array('jquery', 'travel-diaries-jcf'), '1.2.0', true);
    wp_enqueue_script('travel-diaries-meanmenu', get_template_directory_uri() . '/js/jquery.meanmenu.js', array('jquery'), '2.0.8', true);
    wp_enqueue_script('travel-diaries-sidr', get_template_directory_uri() . '/js/jquery.sidr.js', array('jquery'), '1.2.1', true);
    wp_enqueue_script('travel-diaries-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '20160108', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'travel_diaries_scripts');

/**
 * Registering and enqueuing scripts/stylesheets for Customizer controls.
 */
function travel_diaries_customizer_js() {
    wp_enqueue_script('travel-diaries-customizer-js', get_template_directory_uri() . '/inc/js/customizer.js', array("jquery"), '20160512', true);
}

add_action('customize_controls_enqueue_scripts', 'travel_diaries_customizer_js');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Widget Featured Post
 */
require get_template_directory() . '/inc/widget-featured-post.php';

/**
 * Widget Recent Post
 */
require get_template_directory() . '/inc/widget-recent-post.php';

/**
 * Widget Recent Post
 */
require get_template_directory() . '/inc/widget-popular-post.php';

/**
 * Widget Recent Post
 */
require get_template_directory() . '/inc/widget-social-links.php';

/**
 * Custom Meta Box
 */
require get_template_directory() . '/inc/metabox.php';


/*
  Custom Work
 */

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if (!session_id()) {
        session_start();
    }
}

function myEndSession() {
//    session_destroy();
    session_unset();
//    echo'<script> window.location="'.get_site_url().'"; </script> ';
    wp_redirect(get_site_url());
}

add_action('checkLoginUserInit', 'checkLoginUser');

function checkLoginUser() {
    if (!isset($_SESSION['emp_username']) || is_null($_SESSION['emp_name'])) {
        //echo "baraaaa";
//         wp_redirect('employees failed page ');
        echo'<script> window.location="wrong-emp-login"; </script> ';
    }
}
add_action('checkStaffLoginUserInit', 'checkStaffLoginUserInit');

function checkStaffLoginUserInit() {
    if (!isset($_SESSION['staff_user_details']) || is_null($_SESSION['staff_logged_in'])) {
        //echo "baraaaa";
        echo'<script> window.location="'.get_site_url().'"; </script> ';
//        wp_redirect(get_site_url());
    }
}

function wpdocs_custom_scripts() {
//    wp_enqueue_script('jquery-3.0.0.min', get_template_directory_uri() . '/js/jquery-3.0.0.min.js', array('jquery'));
    wp_enqueue_script( 'main', get_template_directory_uri().'/js/jQuery.print.js', array( 'jquery' ), '1.0.0', true );
//    wp_enqueue_script('main', get_template_directory_uri() . '/js/site-main.js', array('jquery'));
}

add_action('wp_enqueue_scripts', 'wpdocs_custom_scripts');
add_action('authUserInit', 'authUserInit');

function authUserInit($wsUsername, $wsPassword, $emp_username, $emp_name) {
    $_SESSION['logged_in'] = true; //set you've logged in
    $_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
    $_SESSION['expire_time'] = 15 * 60 * 10; //expire time in seconds: three hours (you must change this)
    $_SESSION["emp_username"] = $emp_username;
    $_SESSION["emp_name"] = $emp_name;
    $_SESSION['loggedin_time'] = time();
    wp_redirect("emp-home");
}
