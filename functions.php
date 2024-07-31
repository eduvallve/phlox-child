<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION

// CUSTOM FUNCTIONS

define( 'CHILD_THEME_URI', get_template_directory_uri() . '-child/' );

// import custom CSS files from child theme
define( 'CHILD_THEME_CSS', CHILD_THEME_URI . 'css/' );
wp_enqueue_style( 'admin', CHILD_THEME_CSS . 'admin.css', false, '1.1', 'all');
wp_enqueue_style( 'elementor', CHILD_THEME_CSS . 'elementor.css', false, '1.1', 'all');
wp_enqueue_style( 'custom', CHILD_THEME_CSS . 'custom.css', false, '1.1', 'all');
// wp_enqueue_style( 'custom-layout-portfolio', CHILD_THEME_CSS . 'custom-layout-portfolio.css', false, '1.1', 'all');

// import custom JS files from child theme
define( 'CHILD_THEME_JS', CHILD_THEME_URI . 'js/' );
wp_enqueue_script( 'custom', CHILD_THEME_JS . 'custom.js', false, '1.1', 'all');

// Allow cross-origin
function add_cors_http_header(){
    header('Access-Control-Allow-Origin: *');
}
add_action('init','add_cors_http_header');

include_once 'php/custom-layout-portfolio.php';


function years_experience_counter(){
    return intVal(date("Y")) - intval(2016);
}

add_shortcode('edu-years-experience', 'years_experience_counter');

include_once 'php/dark-mode.php';
include_once 'php/portfolio-page-tags.php';
