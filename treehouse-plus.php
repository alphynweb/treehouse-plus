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
    $a             = shortcode_atts( [
        'num' => 20
            ], $atts );
    $no_of_badges  = is_numeric( $a[ 'num' ] ) ? esc_attr( $a[ 'num' ] ) : 20;
    $thp_user = ThpUser::get_instance();
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
