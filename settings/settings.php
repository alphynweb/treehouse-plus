<?php
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

function thp_options_init() {

    //Sections - Badges and user
    //
    // Example - add_settings_section($id, $title, $callback, $page)
    // Example - add_settings_field($id, $title, $callback, $page, $section)
    // Example - register_setting($option_group (page?), $option_name (settings field ID)
    // Callbacks can be '' if no callback is required
    // 
    // User settings section
//    add_settings_section(
//            'thp_user_settings_section', 'User Display Settings', '', 'thp_settings_page'
//    );
//
//    add_settings_field(
//            'thp_username', 'Treehouse Username', 'thp_username_render', 'thp_settings_page', 'thp_user_settings_section'
//    );
//
//    register_setting(
//            'thp_settings_page', 'thp_username'
//    );
//
//    // Badge settings section
//    add_settings_section(
//            'thp_badges_settings_section', 'Badge Display Settings', '', 'thp_settings_page'
//    );
//
//    add_settings_field(
//            'thp_badge_tester', 'Treehouse Badge Tester', 'thp_badge_render', 'thp_settings_page', 'thp_badges_settings_section'
//    );
//
//    register_setting(
//            'thp_settings_page', 'thp_badge'
//    );
//
//    // Points settings section
//    add_settings_section(
//            'thp_points_settings_section', 'Points Display Settings', '', 'thp_settings_page'
//    );
//
//    add_settings_field(
//            'thp_points_tester', 'Treehouse Points Tester', 'thp_points_render', 'thp_settings_page', 'thp_points_settings_section'
//    );
//
//    register_setting(
//            'thp_settings_page', 'thp_points'
//    );
}

function render_thp_settings_page() {
    // If the user object is not set, then display the initial "Enter username" form
    // If the user object is set, then display all the information
    ?>

    <form action="options.php" method="post">

        <h1>Treehouse Plus: Settings Page</h1>

        <?php
        settings_fields( 'thp_settings_page' ); // Necessary before anything else

        do_settings_fields( 'thp_settings_page', 'thp_user_settings_section' );

        // If username is set then display other data
        if ( get_option( 'thp_username' ) ) {
            do_settings_fields( 'thp_settings_page', 'thp_badges_settings_section' );
            do_settings_fields( 'thp_settings_page', 'thp_points_settings_section' );
        };

        submit_button();
        // Display only user settings section
        ?>

    </form>

    <?php
    // Create user object
    $thp_user = new ThpUser( 'tomm2' );

    // If user was updated then save the new user object to wp
    // If user was created successfully then save to wp_options
    if ( empty( $thp_user->get_error ) ) {
        //$thp_user->save_user_data();
//        echo "<h1>Name -</h1>";
//        $thp_user->render_name();
//        echo "<h1>Badges -</h1>";
//        //$thp_user->render_badges();
//        echo "<h1>Points -</h1>";
//        $thp_user->render_points();
//        echo "<h1>Stages</h1>";
//        $thp_user->render_stages();
    }
}

add_action( 'admin_init', 'thp_options_init' );

function thp_admin_styles_and_scripts() {
    wp_enqueue_style( 'thp-treehouse', plugins_url( 'styles/styles.css', dirname( __FILE__ ) ) );
}

add_action( 'admin_enqueue_scripts', 'thp_admin_styles_and_scripts' );

