<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class ThpSettings
{

    protected $user;

    function __construct() {
        add_action( 'admin_init', 'thp_options_init' );
        add_action( 'admin_enqueue_scripts', 'thp_admin_styles_and_scripts' );
        add_action( 'admin_menu', 'thp_menu' );

        $this->render();
    }

    protected function thp_options_init() {

        //Sections - Badges and user
        //
    // Example - add_settings_section($id, $title, $callback, $page)
        // Example - add_settings_field($id, $title, $callback, $page, $section)
        // Example - register_setting($option_group (page?), $option_name (settings field ID)
        // Callbacks can be '' if no callback is required
        // 
        // User settings section
        add_settings_section(
                'thp_user_settings_section', 'User Display Settings', '', 'thp_settings_page'
        );

        add_settings_field(
                'thp_username', 'Treehouse Username', 'thp_username_render', 'thp_settings_page', 'thp_user_settings_section'
        );

        register_setting(
                'thp_settings_page', 'thp_username'
        );

        // Badge settings section
        add_settings_section(
                'thp_badges_settings_section', 'Badge Display Settings', '', 'thp_settings_page'
        );

        add_settings_field(
                'thp_badge_tester', 'Treehouse Badge Tester', 'thp_badge_render', 'thp_settings_page', 'thp_badges_settings_section'
        );

        register_setting(
                'thp_settings_page', 'thp_badge'
        );

        // Points settings section
        add_settings_section(
                'thp_points_settings_section', 'Points Display Settings', '', 'thp_settings_page'
        );

        add_settings_field(
                'thp_points_tester', 'Treehouse Points Tester', 'thp_points_render', 'thp_settings_page', 'thp_points_settings_section'
        );

        register_setting(
                'thp_settings_page', 'thp_points'
        );
    }

    protected function thp_admin_styles_and_scripts() {
        wp_enqueue_style( 'thp-treehouse', plugins_url( 'styles/styles.css', dirname( __FILE__ ) ) );
    }

    protected function render_thp_settings_page() {
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
}

    protected function menu() {
        add_options_page(
                'Treehouse Plus - Options', 'Treehouse Plus', 'manage_options', 'treehouse-plus', 'render_thp_settings_page'
        );
    }

}
?>
