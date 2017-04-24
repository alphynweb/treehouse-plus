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
        $badges_heading        = !empty( $instance[ 'thp-widget-badges-heading' ] ) ? $instance[ 'thp-widget-badges-heading' ] : '';
        $points_heading        = !empty( $instance[ 'thp-widget-points-heading' ] ) ? $instance[ 'thp-widget-points-heading' ] : '';
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

        <!-- Show name -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-name' ) ); ?>"><?php _e( 'Show Name', 'treehouse-plus' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-name' ) ); ?>"
                   name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-name' ) ); ?>"
                   type="checkbox"
                   <?php echo $show_name_checked; ?> />
        </p>
        <!-- Show gravatar -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-gravatar' ) ); ?>"><?php _e( 'Show Gravatar', 'treehouse-plus' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-gravatar' ) ); ?>"
                   name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-gravatar' ) ); ?>"
                   type="checkbox"
                   <?php echo $show_gravatar_checked; ?> />
        </p>
        <!-- Badges heading -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-heading' ) ); ?>"><?php _e( 'Badges Heading', 'treehouse-plus' ); ?></label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-heading' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'thp-widget-badges-heading' ) ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $badges_heading ); ?>" />
        </p>
        <!-- Show badges -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-badges' ) ); ?>"><?php _e( 'Recent Badges', 'treehouse-plus' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-badges' ) ); ?>"
                   name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-badges' ) ); ?>"
                   type="checkbox"
                   <?php echo $show_badges_checked; ?> />
        </p>
        <!-- Number of badges -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-num' ) ); ?>"><?php _e( 'Number', 'treehouse-plus' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-badges-num' ) ); ?>"
                   name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-badges-num' ) ); ?>"
                   type="number"
                   value="<?php echo $badges_num; ?>" />
        </p>
        <!-- Points heading -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-points-heading' ) ); ?>"><?php _e( 'Points Heading', 'treehouse-plus' ); ?></label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-points-heading' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'thp-widget-points-heading' ) ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $points_heading ); ?>" />
        </p>
        <!-- Show points -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-points' ) ); ?>"><?php _e( 'Show Points', 'treehouse-plus' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thp-widget-show-points' ) ); ?>"
                   name ="<?php echo esc_attr( $this->get_field_name( 'thp-widget-show-points' ) ); ?>"
                   type="checkbox"
                   <?php echo $show_points_checked; ?> />
        </p>

        <?php
        //echo $args[ 'after_widget' ]; // TODO
    }

    public function update( $new_instance, $old_instance ) {
        $instance                                = [];
        $instance[ 'thp-widget-title' ]          = !empty( $new_instance[ 'thp-widget-title' ] ) ? strip_tags( $new_instance[ 'thp-widget-title' ] ) : '';
        $instance[ 'thp-widget-badges-heading' ] = !empty( $new_instance[ 'thp-widget-badges-heading' ] ) ? strip_tags( $new_instance[ 'thp-widget-badges-heading' ] ) : '';
        $instance[ 'thp-widget-points-heading' ] = !empty( $new_instance[ 'thp-widget-points-heading' ] ) ? strip_tags( $new_instance[ 'thp-widget-points-heading' ] ) : '';
        $instance[ 'thp-widget-show-badges' ]    = $new_instance[ 'thp-widget-show-badges' ];
        $instance[ 'thp-widget-badges-num' ]     = is_numeric( $new_instance[ 'thp-widget-badges-num' ] ) ? $new_instance[ 'thp-widget-badges-num' ] : $old_instance[ 'thp-widget-badges-num' ];
        $instance[ 'thp-widget-show-points' ]    = $new_instance[ 'thp-widget-show-points' ];
        $instance[ 'thp-widget-show-name' ]      = $new_instance[ 'thp-widget-show-name' ];
        $instance[ 'thp-widget-show-gravatar' ]  = $new_instance[ 'thp-widget-show-gravatar' ];
        return $instance;
    }

    public function widget( $args, $instance ) {
        // Get user info
        if ( !get_option( 'thp_user' ) ) {
            return;
        }

        $thp_user = ThpUser::get_instance();

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
        if ( !empty( $instance[ 'thp-widget-points-heading' ] ) ) {
            echo '<h2 class="thp-widget-points-heading">' . $instance[ 'thp-widget-points-heading' ] . '</h2>';
        }
        if ( !empty( $instance[ 'thp-widget-show-points' ] ) ) {
            $thp_user->render_points( 'list' );
        }
        if ( !empty( $instance[ 'thp-widget-badges-heading' ] ) ) {
            echo '<h2 class="thp-widget-badges-heading">' . $instance[ 'thp-widget-badges-heading' ] . '</h2>';
        }
        if ( !empty( $instance[ 'thp-widget-show-badges' ] ) ) {
            $badges_num = !empty( $instance[ 'thp-widget-badges-num' ] ) ? $instance[ 'thp-widget-badges-num' ] : null;
            $thp_user->sort_badges( 'earned date' );
            $thp_user->render_badges( $badges_num );
        }
        echo $args[ 'after_widget' ];
    }

}
