<?php

Class Thp_Widget extends WP_Widget
{

    public function __construct() {
        $widget_args = [
            'description' => esc_html__( 'Treehouse-Plus Widget', 'treehouse-plus' )
        ];
        parent::__construct(
                'thp-widget', 'Treehouse-plus Widget', $widget_args
        );
}

    public function form( $instance ) {
        $title                 = !empty( $instance[ 'thp-widget-title' ] ) ? $instance[ 'thp-widget-title' ] : '';
        $show_badges_checked   = !empty( $instance[ 'thp-widget-show-badges' ] ) ? 'checked' : null;
        $badges_num            = !empty( $instance[ 'thp-widget-badges-num' ] ) ? $instance[ 'thp-widget-badges-num' ] : 20;
        $show_points_checked   = !empty( $instance[ 'thp-widget-show-points' ] ) ? 'checked' : null;
        $show_name_checked     = !empty( $instance[ 'thp-widget-show-name' ] ) ? 'checked' : null;
        $show_gravatar_checked = !empty( $instance[ 'thp-widget-show-gravatar' ] ) ? 'checked' : null;
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-title' ) ); ?>"><?php _e( 'Title', 'treehouse-plus' ); ?></label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'thp-widget-title' ) ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <h2>Display:</h2>

        <ul>
            <li>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-name' ) ); ?>"><?php _e( 'Name', 'treehouse-plus' ); ?></label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-name' ) ); ?>"
                           name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-name' ) ); ?>"
                           type="checkbox"
                           <?php echo $show_name_checked; ?> />
                </p>
            </li>
            <li>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-gravatar' ) ); ?>"><?php _e( 'Gravatar', 'treehouse-plus' ); ?></label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-gravatar' ) ); ?>"
                           name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-gravatar' ) ); ?>"
                           type="checkbox"
                           <?php echo $show_gravatar_checked; ?> />
                </p>
            </li>
            <li>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-badges' ) ); ?>"><?php _e( 'Recent Badges', 'treehouse-plus' ); ?></label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-badges' ) ); ?>"
                           name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-badges' ) ); ?>"
                           type="checkbox"
                           <?php echo $show_badges_checked; ?> />

                    <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-num' ) ); ?>"><?php _e( 'Number', 'treehouse-plus' ); ?></label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-num' ) ); ?>"
                           name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-badges-num' ) ); ?>"
                           type="number"
                           value="<?php echo $badges_num; ?>" />
                </p>
            </li>
            <li>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-points' ) ); ?>"><?php _e( 'Points', 'treehouse-plus' ); ?></label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-points' ) ); ?>"
                           name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-points' ) ); ?>"
                           type="checkbox"
                           <?php echo $show_points_checked; ?> />
                </p>
            </li>
        </ul>

        <?php
        //echo $args[ 'after_widget' ]; // TODO
    }

    public function update( $new_instance, $old_instance ) {
        $instance                               = [];
        $instance[ 'thp-widget-title' ]         = !empty( $new_instance[ 'thp-widget-title' ] ) ? strip_tags( $new_instance[ 'thp-widget-title' ] ) : '';
        $instance[ 'thp-widget-show-badges' ]   = $new_instance[ 'thp-widget-show-badges' ];
        $instance[ 'thp-widget-badges-num' ]    = is_numeric( $new_instance[ 'thp-widget-badges-num' ] ) ? $new_instance[ 'thp-widget-badges-num' ] : $old_instance[ 'thp-widget-badges-num' ];
        $instance[ 'thp-widget-show-points' ]   = $new_instance[ 'thp-widget-show-points' ];
        $instance[ 'thp-widget-show-name' ]     = $new_instance[ 'thp-widget-show-name' ];
        $instance[ 'thp-widget-show-gravatar' ] = $new_instance[ 'thp-widget-show-gravatar' ];
        return $instance;
    }

    public function widget( $args, $instance ) {
        // Get user info
        if ( !get_option( 'thp_user' ) ) {
            return;
        }

        $thp_user = new ThpUser( get_option( 'thp_user' )[ 'user_data' ] );

        echo $args[ 'before_widget' ];
        if ( !empty( $instance[ 'thp-widget-title' ] ) ) {
            echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'thp-widget-title' ] ) . $args[ 'after_title' ];
        }
        if ( !empty( $instance[ 'thp-widget-show-name' ] ) ) {
            $thp_user->render_name();
        }
        if ( !empty( $instance[ 'thp-widget-show-gravatar' ] ) ) {
            $thp_user->render_gravatar();
        }
        if ( !empty( $instance[ 'thp-widget-show-points' ] ) ) {
            echo "<h2>Points</h2>";
            $thp_user->render_points( 'list' );
        }
        if ( !empty( $instance[ 'thp-widget-show-badges' ] ) ) {
            echo "<h2>Recent Badges</h2>";
            $badges_num = !empty( $instance[ 'thp-widget-badges-num' ] ) ? $instance[ 'thp-widget-badges-num' ] : null;
            $thp_user->sort_badges( 'earned date' );
            $thp_user->render_badges( $badges_num );
        }
        echo $args[ 'after_widget' ];
    }

}
