<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class ThpSettings
{

    protected $thp_user;

    function __construct() {
        add_action( 'admin_init', array ( $this, 'thp_options_init' ) );
        add_action( 'admin_menu', array ( $this, 'thp_menu' ) );
        $this->thp_user = ThpUser::get_instance();
    }

    public function thp_options_init() {
        include plugin_dir_path( __DIR__ ) . 'includes/options_init.php';
    }

    public function thp_chart_colors_field_callback() {
        // Validate color strings in color picker fields
        if ( !isset( $_POST[ 'thp_chart_colors' ] ) ) {
            return;
        }

        $thp_chart_colors = $_POST[ 'thp_chart_colors' ];

        $message = 'Points display settings updated successfully';
        $type    = 'updated';

        foreach ( $thp_chart_colors as $thp_chart_color ) {
            // Validate the string
            preg_match( '/(#[a-f0-9]{6}?)/i', $thp_chart_color, $matches );
            if ( !isset( $matches[ 1 ] ) ) {
                // One of the entries is in the wrong format
                $type    = 'error';
                $message = __( 'There was an error in your chart colors. Please make sure they are in the #FFFFFF format', 'treehouse-plus' );
                add_settings_error( 'thp_chart_colors', 'thp_chart_colors', $message, $type );
                // Switch back to old valuse
                return get_option( 'thp_chart_colors' );
            }
        }
        // Success
        // Also update user object with new points list

        $user_points_list = $this->thp_user->get_points_list();
        // Loop through existing user points list and set the colours
        foreach ( $user_points_list as $points ) {
            $name  = $points->get_name();
            $color = isset( $thp_chart_colors[ $name ] ) ? $thp_chart_colors[ $name ] : '#0000ff';
            $points->set_color( $color );
        }
        $this->thp_user->save_data();

        return $thp_chart_colors;
        add_settings_error( 'thp_chart_colors', 'thp_chart_colors', $message, $type );
    }

    public function thp_display_chart_colors_fields( $args ) {
        // Loop through points and display a color picker field for each one
        $pointsList = $this->thp_user->get_points_list();
        echo '<ul class="thp-chart-colors">';
        foreach ( $pointsList as $points ):
            $points_name  = $points->get_name();
            $input_name   = "thp_chart_colors[" . $points_name . "]";
            $points_color = $points->get_color();
            ?>

            <li>
                <label for="<?php echo $input_name; ?>"><?php echo $points_name; ?></label>
                <input type="text" class="thp-color-picker" value="<?php echo $points_color; ?>" name="<?php echo $input_name; ?>" data-default-color="#0000ff" />
            </li>

            <?php
        endforeach;
        echo '</ul>';
    }

    public function thp_display_initial_settings_section() {
        submit_button( 'Load Your Profile' );
    }

    public function thp_profile_name_field_callback() {
        $thp_profile_name = sanitize_text_field( $_POST[ 'thp_profile_name' ] );
        // Error stuff
        $message          = null;
        $type             = 'error';

        //$this->thp_user = ThpUser::get_instance();
//        if ( empty( $thp_profile_name ) ) {
//            // Field empty error
//            $message = __( 'Sorry, the profile name field cannot be empty', 'treehouse-plus' );
//        } else {
//            // Build user from json request
//            $url           = 'http://teamtreehouse.com/' . $thp_profile_name . '.json';
//            $response      = wp_remote_get( $url );
//            $response_code = wp_remote_retrieve_response_code( $response );
//
//            if ( $response_code == 200 ) {
//                // Json data for update successfully retrieved
//                $user_data      = json_decode( $response[ 'body' ] );
//                $this->thp_user = ThpUser::get_instance();
//                if ( !empty( $this->thp_user->get_error() ) ) {
//                    // Error with creating user info
//                    $message = __( $this->thp_user->get_error(), 'treehouse-plus' );
//                } else {
//                    // No error
//                    // Save new user info to db
//                    $this->thp_user->save_data();
//                    $type    = 'updated';
//                    $message = __( 'Your profile has been updated', 'treehouse-plus' );
//                }
//            } else if ( $response_code == 404 ) {
//                // Error in retrieving json data
//                $type    = 'error';
//                $message = __( 'Error in retreiving user data', 'treehouse-plus' );
//            }
//        }
//
//        add_settings_error( 'thp_initial_profile_name', 'thp_initial_profile_name', $message, $type );
    }

    public function thp_display_profile_name_field() {
        //$thp_profile_name = null;
        // If there is a user stored in the db then this will be the user settings screen and this will have the value of the user profile name
        //if ( isset( $this->thp_user ) ) {
        // Display $this->thp_user profile name
        $thp_profile_name = $this->thp_user->get_profile_name();
        //}
        ?>

        <input type="text" name="thp_profile_name" value="<?php echo $thp_profile_name; ?>" />

        <?php
    }

    public function thp_user_settings_section_callback() {
        
    }

    public function thp_badges_settings_section_callback() {
        
}

    public function thp_badges_save_section_callback() {
        
    }

    public function thp_points_settings_section_callback() {
        
    }

    public function thp_chart_colors_section_callback() {
        
    }

    public function thp_display_user_settings_section() {
        if ( !isset( $this->user ) ) {
            return false;
        }
        submit_button( 'Save User Settings' );
        settings_fields( 'thp_user_settings_page' );
        do_settings_sections( 'thp_user_settings_page' );
        if ( get_option( 'thp_gravatar' ) ) {
            $this->thp_user->render_gravatar();
        }
    }

    public function thp_display_gravatar_field() {
        $checked = get_option( 'thp_gravatar' ) ? "checked" : null;
        ?>

        <input type="checkbox" name="thp_gravatar" <?php echo $checked; ?> />

        <?php

    }

    public function thp_display_badge_sort_field() {
        $selected = get_option( 'thp_badge_sort' );
        ?>

        <select name="thp_badge_sort">
            <option value="earned date" <?php selected( $selected, "earned date" ); ?>>Earned Date (Newest to Oldest)</option>
            <option value="earned date reverse" <?php selected( $selected, "earned date reverse" ); ?>>Earned Date (Oldest to Newest)</option>
            <option value="name" <?php selected( $selected, "name" ); ?>>Name (A - Z)</option>
            <option value="name reverse" <?php selected( $selected, "name reverse" ); ?>>Name (Z - A)</option>
        </select>

        <?php
    }

    public function thp_display_badge_show_stages_field() {
        $checked = get_option( 'thp_badge_show_stages' ) ? "checked" : null;
        ?>
        <input type="checkbox" name="thp_badge_show_stages" <?php echo $checked; ?> />

        <?php
    }

    public function thp_display_badge_save_files_field() {
        // Checkbox to save the badges as resized files to the local filesystem 
        ?>

        <input type="checkbox" name="thp_badge_save_files" />

        <?php
    }

    public function thp_display_badge_save_sizes_field() {
        // Number field of pixel size to save badges as
        ?>

        <input type="number" name="thp_badge_save_sizes" value="<?php echo get_option( 'thp_badge_save_sizes' ) ? get_option( 'thp_badge_save_sizes' ) : 50; ?>" min="0" />

        <?php
    }

    public function thp_badge_save_sizes_callback() {
//        if ( !isset( $_POST[ 'thp_badge_save_sizes' ] ) ) {
//            return;
//        }
//
//        // Check that correct submit button was pressed
//        if ( isset( $_POST[ 'thp_badges_save_submit' ] ) ) {
//
//            if ( !is_numeric( $_POST[ 'thp_badge_save_sizes' ] ) ) {
//                // Error with creating user info
//                $message = __( 'Badge size must be a number', 'treehouse-plus' );
//                $type    = 'error';
//                add_settings_error( 'thp_badge_save_sizes', 'thp_badge_save_sizes', $message, $type );
//                return get_option( 'thp_badge_save_sizes' ) ? get_option( 'thp_badge_save_sizes' ) : 50;
//            }
//
//            // Resize and save badges
//            $this->thp_user = ThpUser::get_instance();
//            $this->thp_user->save_badges();
//            $message        = 'Badges saved successfully';
//            $type           = 'updated';
//
//            if ( !empty( $this->thp_user->get_error() ) ) {
//                // Error
//                $message = __( $this->thp_user->get_error(), 'treehouse-plus' );
//                $type    = 'error';
//            }
//
//            add_settings_error( 'thp_badge_save_files', 'thp_badge_save_files', $message, $type );
//        }
//
//        return $_POST[ 'thp_badge_save_sizes' ];
    }

    public function thp_display_points_display_field() {
        $option = get_option( 'thp_points_display' );
        ?>

        <select name="thp_points_display">
            <option value="list" <?php selected( $option, "list" ); ?>>List</option>
            <option value="bar" <?php selected( $option, "bar" ); ?>>Bar</option>
            <option value="column" <?php selected( $option, "column" ); ?>>Column</option>
            <option value="pie" <?php selected( $option, "pie" ); ?>>Pie</option>
            <option value="donut" <?php selected( $option, "donut" ); ?>>Donut</option>
        </select>

        <?php
}

    protected function thp_display_options() {
        // Display tabs
        include( plugin_dir_path( __DIR__ ) . 'includes/tabs.php');
        if ( !isset( $_GET[ 'tab' ] ) ) {
            settings_fields( 'thp_user_settings_page' );
            do_settings_sections( 'thp_user_settings_page' );
            submit_button( 'Update' );
            if ( get_option( 'thp_gravatar' ) ) {
                $this->thp_user->render_gravatar();
            }
        } else {
            $tab = $_GET[ 'tab' ];
            switch ( $tab ) {
                case "user";
                    settings_fields( 'thp_user_settings_page' );
                    do_settings_sections( 'thp_user_settings_page' );
                    submit_button( 'Update' );
                    if ( get_option( 'thp_gravatar' ) ) {
                        $this->thp_user->render_gravatar();
                    }
                    break;
                case "badges";
                    $this->thp_display_badge_settings_page();
                    break;
                case "points";
                    $this->thp_display_points_settings_page();
                    break;
                default:
                    settings_fields( 'thp_user_settings_page' );
                    do_settings_sections( 'thp_user_settings_page' );
                    submit_button( 'Update' );
                    if ( get_option( 'thp_gravatar' ) ) {
                        $this->thp_user->render_gravatar();
                    }
                    break;
            }
        }
   }

    public function thp_display_badge_settings_page() {
        settings_fields( 'thp_badges_settings_page' );

        do_settings_sections( 'thp_badges_settings_page' );
        submit_button( 'Save Badges Settings' );
        $this->thp_render_badge_save_messages();
        ?>

        <a href="#TB_inline?width=600&height=550&inlineId=thp-badge-save-modal" class="thickbox button button-secondary">Save badges</a>

        <?php
        if ( get_option( 'thp_badge_sort' ) ) {
            $this->thp_user->sort_badges( get_option( 'thp_badge_sort' ) );
        }
        if ( get_option( 'thp_badge_show_stages' ) ) {
            $this->thp_user->render_stages();
        } else {
            $this->thp_user->render_badges();
        }
    }

    public function thp_display_points_settings_page() {
        settings_fields( 'thp_points_settings_page' );
        echo '<section class="thp-section thp-points-settings-container">';
        submit_button( 'Save Settings' );
        do_settings_fields( 'thp_points_settings_page', 'thp_points_settings_section' );
        echo '</section>';
        echo '<section class="thp-section thp-chart-colors-container">';
        do_settings_fields( 'thp_points_settings_page', 'thp_chart_colors_section' );
        echo '</section>';
        echo '<section class="thp-chart-container">';
        $this->thp_user->render_points();
        echo '</section>';
    }

    public function thp_sanitize_text_field( $option ) {
        $sanitized_option = sanitize_text_field( $option );
        return $sanitized_option;
    }

    public function thp_render_badge_save_messages() {

        $total_badges_no      = count( $this->thp_user->get_badge_list() );
        $saved_badges_info    = $this->thp_user->get_saved_badges();
        $saved_badges_no      = $saved_badges_info[ 'no_of_badges' ];
        $badges_size          = $saved_badges_info[ 'size' ] . 'px';
        $saved_badges_message = "You currently have no badges saved to your filesystem";
        if ( $total_badges_no === $saved_badges_no ) {
            $saved_badges_message = "All {$total_badges_no} of your badges are currently saved to your filesystem at a size of <span class='thp-badges-size'>" . $badges_size . "</span>";
        } elseif ( $saved_badges_no > 0 ) {
            $saved_badges_message = "You currently have <span class='thp-saved-badges-no'>{$saved_badges_no}</span> out of <span class='thp-total-badges-no'>{$total_badges_no}</span> badges saved to your filesystem at a size of <span class='thp-badges-size'>{$badges_size}</span>";
        }
        echo '<p class="thp-saved-badges-message">';
        echo $saved_badges_message;
        echo '</p>';
    }

    public function thp_display_settings_page() {
        add_thickbox();
        // Establish whether any other badges or points etc have been earned since last visit.
        $thp_json_user = new ThpUser( $this->thp_user->get_profile_name() );

        // Check for new badges and points
        $thp_new_badge_count  = count( $thp_json_user->get_badge_list() ) - count( $this->thp_user->get_badge_list() );
        $thp_new_points_count = $thp_json_user->get_total_points() - $this->thp_user->get_total_points();
        
        if ( $thp_new_badge_count > 0 && $thp_new_points_count > 0 ) { // New badges and points earned
            $message = "You have earned {$thp_new_points_count} new points and {$thp_new_badge_count} new badges";
            $type    = 'error';
            add_settings_error( 'thp-new-points-badges-earned', 'thp-new-points-badges-earned', $message, $type );
        } elseif ( $thp_new_badge_count > 0 ) { // New badges earned
            $message = "You have earned {$thp_new_badge_count} new badges";
            $type    = 'error';
            add_settings_error( 'thp-new-badges-earned', 'thp-new-badges-earned', $message, $type );
        } elseif ( $thp_new_points_count > 0 ) { // New points earned
            $message = "You have earned {$thp_new_points_count} new points and {$thp_new_badge_count} new badges";
            $type    = 'error';
            add_settings_error( 'thp-new-points-badges-earned', 'thp-new-points-badges-earned', $message, $type );
        }

        ?>

        <!-- Badge save overlay -->

        <div id="thp-badge-save-modal" style="display: none;">
            <div id="thp-badge-save-section">

                <?php
                $this->thp_render_badge_save_messages();
                ?>

                <label for="thp_badge_save_sizes">Size to save badges (px)</label>
                <input type="number" name="thp_badge_save_sizes" id="thp_badge_save_sizes" value="<?php echo $this->get_badge_save_size(); ?>" min="0" max="300" />
                <button id="thp_badges_save_submit" class="button button-primary">Save my badges!</button>
                <div id="badgeFileList">
                    <div id="progress">
                        <div id="bar"></div>
                    </div>
                    <span id="noSaved"></span>
                </div>
            </div>

            <div id="thp-badge-save-complete-section">
                <p>Your badge save is complete!</p>
            </div>
        </div>	

        <div class="wrap">
            <h1><?php _e( 'Treehouse Plus: Settings Page', 'treehouse-plus' ); ?></h1>
            <?php settings_errors(); ?>
            <form action="options.php" method="post">

                <?php
                // Check if user data is in database
                // If it is, then create new user class from it
                if ( get_option( 'thp_user' ) ) {
                    $this->thp_display_options();
                } else {
                    settings_fields( 'thp_initial_settings_page' );
                    do_settings_sections( 'thp_initial_settings_page' );
                }
                ?>
            </form>
        </div> <!-- End wrap -->

        <?php
}

    public function thp_menu() {
        add_options_page(
                'Treehouse Plus - Options', 'Treehouse Plus', 'manage_options', 'treehouse-plus', array ( $this, 'thp_display_settings_page' )
        );
    }

    public function get_badge_save_size() {
        $badge_save_size = get_option( 'thp_save_badge_size' ) ? get_option( 'thp_save_badge_size' ) : 50;
        return $badge_save_size;
    }

}
?>
