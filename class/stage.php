<?php
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

class Stage
{

    protected $title;
    protected $steps;

    function __construct( $stage_title, $steps ) {
        $this->title = $stage_title;
        $this->set_steps( $steps );
}

    // Render

    public function render() {
        ?>
        <div class="thp-badge-stages">
            <h2><?php echo $this->title; ?></h2>
            <ul>
                <?php
                foreach ( $this->steps as $step ) {
                    $step->render();
                }
                ?>
            </ul>
        </div>

        <?php
}

    // Getters and setters

    public function get_steps() {
        return $this->steps;
    }

    public function get_title() {
        return $this->title;
    }

    protected function set_steps( $steps ) {
        $steps_array = [];
        foreach ( $steps as $step ) {
            $new_step = new Badge( $step );
            array_push( $steps_array, $new_step );
        }

        // Sort steps array into chronological order
        usort( $steps_array, function($step_a, $step_b) {
            $date_earned_a = $step_a->get_earned_date();
            $date_earned_b = $step_b->get_earned_date();

            if ( $date_earned_a == $date_earned_b ) {
                return 0;
            }

            return $date_earned_a < $date_earned_b ? -1 : 1;
        } );

        $this->steps = $steps_array;
    }

}
