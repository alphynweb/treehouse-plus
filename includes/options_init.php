<?php

// Inital settings section
add_settings_section(
        'thp_initial_settings_section', 'Initial Settings', array ( $this, 'thp_display_initial_settings_section' ), 'thp_initial_settings_page'
);

add_settings_field(
        'thp_profile_name', 'Treehouse Profile Name', array ( $this, 'thp_display_profile_name_field' ), 'thp_initial_settings_page', 'thp_initial_settings_section'
);

register_setting(
        'thp_initial_settings_page', 'thp__profile_name', array ( $this, 'thp_profile_name_field_callback' )
);

// User settings section
add_settings_section(
        'thp_user_settings_section', 'User Settings', array ( $this, 'thp_user_settings_section_callback' ), 'thp_user_settings_page'
);

add_settings_field(
        'thp_profile_name', 'Treehouse Profile Name', array ( $this, 'thp_display_profile_name_field' ), 'thp_user_settings_page', 'thp_user_settings_section'
);

register_setting(
        'thp_user_settings_page', 'thp_profile_name', array ( $this, 'thp_profile_name_field_callback' )
);

add_settings_field(
        'thp_gravatar', 'Show Gravatar', array ( $this, 'thp_display_gravatar_field' ), 'thp_user_settings_page', 'thp_user_settings_section'
);

register_setting(
        'thp_user_settings_page', 'thp_gravatar'
);

// Badge settings section
add_settings_section(
        'thp_badges_settings_section', 'Badge Settings', array ( $this, 'thp_badges_settings_section_callback' ), 'thp_badges_settings_page'
);

add_settings_field(
        'thp_badge_sort', 'Sort Badges By', array ( $this, 'thp_display_badge_sort_field' ), 'thp_badges_settings_page', 'thp_badges_settings_section'
);

register_setting(
        'thp_badges_settings_page', 'thp_badge_sort'
);

add_settings_field(
        'thp_badge_show_stages', 'Show badges by course stages (badges will be displayed by date earned)', array ( $this, 'thp_display_badge_show_stages_field' ), 'thp_badges_settings_page', 'thp_badges_settings_section'
);

register_setting(
        'thp_badges_settings_page', 'thp_badge_show_stages'
);

// Badge saving section
add_settings_section(
        'thp_badges_save_section', 'Badge Save', array ( $this, 'thp_badges_save_section_callback' ), 'thp_badges_settings_page'
);

//add_settings_field(
//        'thp_badge_save_files', 'Save badges as files', array ( $this, 'thp_display_badge_save_files_field' ), 'thp_badges_settings_page', 'thp_badges_save_section'
//);
//
//register_setting(
//        'thp_badges_settings_page', 'thp_badge_save_files', array ( $this, 'thp_badge_save_files_callback' )
//);

add_settings_field(
        'thp_badge_save_sizes', 'Size to save badges as (pixels)', array ( $this, 'thp_display_badge_save_sizes_field' ), 'thp_badges_settings_page', 'thp_badges_save_section'
);

register_setting(
        'thp_badges_settings_page', 'thp_badge_save_sizes', array ( $this, 'thp_badge_save_sizes_callback' )
);

// Points settings section
add_settings_section(
        'thp_points_settings_section', 'Points Settings', array ( $this, 'thp_points_settings_section_callback' ), 'thp_points_settings_page'
);

add_settings_field(
        'thp_points_display', 'Display Points As', array ( $this, 'thp_display_points_display_field' ), 'thp_points_settings_page', 'thp_points_settings_section', array ( 'label_for' => 'thp_points_display' )
);

register_setting(
        'thp_points_settings_page', 'thp_points_display'
);

// Chart colors section
add_settings_section(
        'thp_chart_colors_section', 'Chart Colors', array ( $this, 'thp_chart_colors_section_callback' ), 'thp_points_settings_page'
);

add_settings_field(
        'thp_chart_colors', 'Chart Colors', array ( $this, 'thp_display_chart_colors_fields' ), 'thp_points_settings_page', 'thp_chart_colors_section', array ( 'label_for' => 'thp_chart_colors' )
);

register_setting(
        'thp_points_settings_page', 'thp_chart_colors', array ( $this, 'thp_chart_colors_field_callback' )
);

