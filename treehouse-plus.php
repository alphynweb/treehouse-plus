<?php

/*
 * Plugin Name: Treehouse Plus
 * Plugin URI:
 * Description: Provides Treehouse info on Wordpress sites, shortcodes and widgets etc.
 * Version: 1.0
 * Author: Alphynweb
 * Author URI: http://www.alphynweb.co.uk/
 * License: GPL2
 * License URI:
 * Text Domain: treehouse-plus
 */

if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

$plugin_dir = plugin_dir_path( __FILE__ );

require_once $plugin_dir . 'class/user.php';
require_once $plugin_dir . 'class/badge.php';
require_once $plugin_dir . 'class/stage.php';
require_once $plugin_dir . 'class/points.php';
require_once $plugin_dir . 'class/settings.php';
require_once $plugin_dir . 'class/widget.php';

$thp_settings = new ThpSettings();

// Shortcodes
remove_shortcode( 'treehouse-plus-badges' );
add_shortcode( 'treehouse-plus-badges', 'thp_badges_shortcode' );

function thp_badges_shortcode( $atts ) {
    if ( !get_option( 'thp_user' ) ) {
        return false;
    }
    $a            = shortcode_atts( [
        'num' => 20
            ], $atts );
    $no_of_badges = is_numeric( $a[ 'num' ] ) ? esc_attr( $a[ 'num' ] ) : 20;
    $thp_user     = ThpUser::get_instance();
    if ( get_option( 'thp_badge_show_stages' ) ) {
        $thp_user->render_stages();
    } else {
        $thp_user->render_badges( $no_of_badges );
    }
}

remove_shortcode( 'treehouse-plus-points' );
add_shortcode( 'treehouse-plus-points', 'thp_points_shortcode' );

function thp_points_shortcode() {
    if ( !get_option( 'thp_user' ) ) {
        return false;
    }
    $thp_user = ThpUser::get_instance();
    $thp_user->render_points();
}

remove_shortcode( 'treehouse-plus-profile' );
add_shortcode( 'treehouse-plus-profile', 'thp_profile_shortcode' );

function thp_profile_shortcode() {
    if ( !get_option( 'thp_user' ) ) {
        return false;
    }
    $thp_user = ThpUser::get_instance();
    $thp_user->render_name();
    $thp_user->render_gravatar();
}

// Fron end enqueue scripts
add_action( 'wp_enqueue_scripts', 'thp_treehouse_styles_and_scripts' );

function thp_treehouse_styles_and_scripts( $hook_suffix ) {
    // To do - only implement these on pages that use the plugin
    wp_register_script( 'google-charts-loader', 'https://www.gstatic.com/charts/loader.js' );
    wp_register_style( 'thp-treehouse', plugins_url( 'styles/styles.css', __FILE__ ) );
    wp_enqueue_script( 'google-charts-loader' );
    wp_enqueue_style( 'thp-treehouse' );
}

// Admin enqueue scripts
add_action( 'admin_enqueue_scripts', 'thp_admin_styles_and_scripts' );

function thp_admin_styles_and_scripts( $hook_suffix ) {
    if ( $hook_suffix != 'settings_page_treehouse-plus' ) {
        return;
    }
    wp_enqueue_script( 'thp-fields', plugins_url( 'scripts/thp_fields.js', __FILE__ ) );
    wp_enqueue_script( 'thp-badge-save', plugins_url( 'scripts/thp_badge_save.js', __FILE__ ) );
    wp_register_script( 'google-charts-loader', 'https://www.gstatic.com/charts/loader.js' );
    wp_register_style( 'thp-treehouse', plugins_url( 'styles/admin-styles.css', __FILE__ ) );
    wp_enqueue_script( 'google-charts-loader' );
    wp_enqueue_style( 'thp-treehouse' );
    }

// WP Color Picker
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );

function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url( 'scripts/color-picker.js', __FILE__ ), array ( 'wp-color-picker' ), false, true );
}

// Widget
add_action( 'widgets_init', 'register_thp_widget' );

function register_thp_widget() {
    register_widget( 'Thp_Widget' );
}

// Ajax tester - Todo - delete
add_action( 'wp_ajax_thp_get_badge_list', 'thp_get_badge_list' );

function thp_get_badge_list() {
    // Delete badges that aren't the right size
//    if ( !isset( $_POST[ 'thp_badge_save_sizes' ] ) ) {
//        return;
//    }
//    $badge_size      = $_POST[ 'thp_badge_save_sizes' ] . 'px';
//    $badge_size      = get_option( 'thp_badge_save_sizes' ) ? get_option( 'thp_badge_save_sizes' ) . 'px' : '50px';
    $size = $_POST['size'] . "px";
    // Delete badges from filesystem that aren't the right size
    $upload_dir      = wp_upload_dir();
    $user_badges_dir = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
    $files           = glob( $user_badges_dir . '*' );
    foreach ( $files as $file ) {
        if ( is_file( $file ) ) {
            // Establish pixel number on file
            $pathinfo = pathinfo( $file );
            $filename = $pathinfo[ 'filename' ];
            $ex       = explode( "-", $filename );
            $badge_size     = end( $ex );
            if ( $badge_size != $size ) {
                // Delete file
                unlink( $file );
            }
        }
    }

    $thp_user          = ThpUser::get_instance();
    $badge_list        = $thp_user->get_badge_list();
    $saved_badges_info = $thp_user->get_saved_badges();
    $saved_badges_no   = $saved_badges_info[ 'no_of_badges' ];

    $badge_list_info = [];
    foreach ( $badge_list as $badge ) {
        $new_badge = [
            'icon_url' => $badge->get_icon_url(),
        ];
        array_push( $badge_list_info, $new_badge );
    }
    echo json_encode( $badge_list_info );
    wp_die();
}

// TODO - not needed?
add_action( 'wp_ajax_thp_delete_badges', 'thp_delete_badges' );

function thp_delete_badges() {
    //$badge_size = get_option( 'thp_badge_save_sizes' ) ? get_option( 'thp_badge_save_sizes' ) : null;
    //$badge_size = isset( $_POST[ 'thp_badge_save_sizes' ] ) ? $_POST[ 'thp_badge_save_sizes' ] : null;
//    if ( !isset( $_POST[ 'thp_badge_save_sizes' ] ) ) {
//        return;
//    }
//    $badge_size      = $_POST[ 'thp_badge_save_sizes' ] . 'px';
    $size = $_POST['size'];
    // Delete badges from filesystem that aren't the right size
    $upload_dir      = wp_upload_dir();
    $user_badges_dir = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
    $files           = glob( $user_badges_dir . '*' );
    foreach ( $files as $file ) {
        if ( is_file( $file ) ) {
            // Establish pixel number on file
            $pathinfo = pathinfo( $file );
            $filename = $pathinfo[ 'filename' ];
            $ex       = explode( "-", $filename );
            $badge_size     = end( $ex );
            if ( $badge_size != $size ) {
                // Delete file
                unlink( $file );
            }
        }
    }
}

add_action( 'wp_ajax_thp_save_badge', 'thp_save_badge' );

function thp_save_badge() {
    //Receive info from ajax about which badge it is, then locate badge in badge list, then save badge object.
    // Info received will be icon_url
    //$badge_size      = $_POST[ 'thp_badge_save_sizes' ] . 'px';
    $thp_user      = ThpUser::get_instance();
    $badge_list    = $thp_user->get_badge_list();
    $icon_url      = $_POST[ 'icon_url' ];
    $size = $_POST['size'];
    $badge_to_save = null;

    // Locate badge in badge list
    foreach ( $badge_list as $badge ) {
        if ( $badge->get_icon_url() == $icon_url ) {
            $badge_to_save = $badge;
        }
    }

    // Save badge
    $badge_to_save->save($size);

    echo "Badge saved: " . $icon_url;
    wp_die();
}

add_action( 'wp_ajax_thp_save_user_data', 'thp_save_user_data' );

function thp_save_user_data() {
    $thp_user = ThpUser::get_instance();
    $thp_user->save_data();
    echo true;
    wp_die();
}
