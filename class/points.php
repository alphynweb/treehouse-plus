<?php
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

class Points
{

    protected $name;
    protected $points;
    protected $color;

    function __construct( $name, $points ) {
        $this->name   = ucfirst( $name );
        $this->points = $points;
//        if ( strtolower( $name ) !== "total" ) {
//            $this->set_color( $name );
//        }
}

    // Getters

    public function get_name() {
        return $this->name;
    }

    public function get_points() {
        return $this->points;
    }

    public function get_color() {
        return isset( $this->color ) ? $this->color : '#0000ff';
    }

    public function set_color( $color ) {
//        if ( !get_option( 'thp_chart_colors' ) ) {
//            $this->color = '#0000ff';
//            return;
//        }
//        // Assign color to points according to thp_chart_colors
//        $chart_colors = get_option( 'thp_chart_colors' );
//        $this->color  = array_key_exists( $name, $chart_colors ) ? $chart_colors[ $name ] : '#0000ff';
        $this->color = $color;
    }

    // Render
    public function render() {
        $total = strtolower( $this->name ) === "total" ? "thp-total-points" : null;
        ?>

        <li class="thp-points-item <?php echo $total; ?>">
            <span class="thp-points-icon" style="color: <?php echo $this->color; ?>"></span>
            <span class="thp-points-name"><?php echo $this->name; ?></span>
            <span class="thp-points-num">(<?php echo $this->points; ?>)</span>
        </li>
        <?php
    }

}
