<?php
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

class ThpUser
{

    protected $badgeList;
    protected $stageList;
    protected $pointsTotal;
    protected $pointsList;
    protected $user_data;
    protected $error;

    function __construct( $user_data ) {
        $this->user_data = $user_data;
        $this->name      = $user_data->name;
        $this->set_badgeList();
        $this->set_stageList();
        $this->set_points();
    }

    public function sort_badges( $thp_badge_sort ) {
        switch ( $thp_badge_sort ) {
            case "earned date":
                usort( $this->badgeList, function($badge_a, $badge_b) {
                    $date_a = $badge_a->get_earned_date();
                    $date_b = $badge_b->get_earned_date();

                    if ( $date_a === $date_b ) {
                        return 0;
                    }

                    return ($date_a > $date_b) ? -1 : 1;
                } );
                break;
            case "earned date reverse":
                usort( $this->badgeList, function($badge_a, $badge_b) {
                    $date_a = $badge_a->get_earned_date();
                    $date_b = $badge_b->get_earned_date();

                    if ( $date_a === $date_b ) {
                        return 0;
                    }

                    return ($date_a < $date_b) ? -1 : 1;
                } );
                break;
            case "name":
                usort( $this->badgeList, function($badge_a, $badge_b) {
                    $name_a = strtolower( $badge_a->get_name() );
                    $name_b = strtolower( $badge_b->get_name() );

                    if ( $name_a === $name_b ) {
                        return 0;
                    }

                    return ($name_a < $name_b) ? -1 : 1;
                } );
                break;
            case "name reverse":
                usort( $this->badgeList, function($badge_a, $badge_b) {
                    $name_a = strtolower( $badge_a->get_name() );
                    $name_b = strtolower( $badge_b->get_name() );

                    if ( $name_a === $name_b ) {
                        return 0;
                    }

                    return ($name_a > $name_b) ? -1 : 1;
                } );
                break;
            case "Stage":
                usort( $this->badgeList, function($badge_a, $badge_b) {
                    $date_a = $badge_a->get_earned_date();
                    $date_b = $badge_b->get_earned_date();

                    if ( $date_a === $date_b ) {
                        return 0;
                    }

                    return ($date_a < $date_b) ? -1 : 1;
                } );
                break;
        }
        }

    public function save_user_data() {
        $options[ 'user_data' ] = $this->user_data; // Treehouse JSON data
        $options[ 'updated' ]   = time();

        update_option( 'thp_user', $options );
    }

    public function render_name() {
        echo "<h2>" . $this->get_name() . "</h2>";
    }

    public function render_gravatar() {
        ?>
        <div class="thp-gravatar">
            <img src="<?php echo $this->user_data->gravatar_url; ?>" />
        </div>
        <?php
        }

    public function render_badges( $num = null ) {
        $count = 0;
        echo "<ul>";
        foreach ( $this->badgeList as $badge ) {
            $badge->render();
            // Specific number of badges passed through to display
            $count ++;
            if ( $count === ( int ) $num ) {
                break;
            }
        }
        echo "</ul>";
    }

    public function render_points( $display = null ) {
        if ( !$display ) {
            $thp_points_display = get_option( 'thp_points_display' ) ? get_option( 'thp_points_display' ) : "list";
        } else {
            $thp_points_display = "list";
        }
        if ( $thp_points_display === "list" ) {
            echo '<h2 class="thp-total-points">' . $this->pointsTotal->get_name() . ' (' . $this->pointsTotal->get_points() . ')' . '</h2>';
            echo '<ul class="thp-points-list">';
            foreach ( $this->pointsList as $points ) {
                $points->render();
            }
            echo "</ul>";
        } else {
            $this->render_points_chart( $thp_points_display );
        }
    }

    protected function render_points_chart( $chart_type ) {
        ?>

        <h2 class="thp-total-points"><?php echo $this->pointsTotal->get_name() . " (" . $this->pointsTotal->get_points() . ")"; ?></h2>
        <div id="thpPointsChart" class="thp-chart"></div>

        <script>
            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages': ['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(thpDrawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function thpDrawChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('number', 'Points');
                data.addColumn({type: 'string', role: 'style'});
                data.addRows([
        <?php
        $title = "";
        foreach ( $this->pointsList as $points ) {
            if ( strtolower( $points->get_name() ) !== "total" ) {
                echo "['" . $points->get_name() . "', " . $points->get_points() . ", '" . $points->get_color() . "'],";
            }
        }
        ?>
                ]);
        <?php
        $chart_width    = '100%';
        $chart_height   = '500';
        $chart_bg_color = [
            'fill' => 'transparent'
        ];

        $chart_colors     = [];
        $thp_chart_colors = get_option( 'thp_chart_colors' );
        if ( $thp_chart_colors ) {
            foreach ( $thp_chart_colors as $key => $value ) {
                array_push( $chart_colors, $value );
            }
        }

        $animation = array (
            'duration' => 1000,
            'easing'   => 'out',
            'startup'  => true
        );

        switch ( $chart_type ) {
            case "bar":
                $chart_options = [
                    'width'           => $chart_width,
                    'height'          => $chart_height,
                    'backgroundColor' => $chart_bg_color,
                    'chartArea'       => [
                        'left'   => 100,
                        'top'    => 0,
                        'width'  => '100%',
                        'height' => '90%'
                    ],
                    'isStacked'       => false,
                    'animation'       => $animation
                ];
                $thp_chart     = "var thpChart = new google.visualization.BarChart(document.getElementById('thpPointsChart'));";
                break;
            case "column":
                $chart_options = [
                    'width'           => $chart_width,
                    'height'          => $chart_height,
                    'backgroundColor' => $chart_bg_color,
                    'chartArea'       => [
                        'left'   => 50,
                        'top'    => 10,
                        'width'  => '100%',
                        'height' => '90%'
                    ],
                    'animation'       => $animation
                ];
                $thp_chart     = "var thpChart = new google.visualization.ColumnChart(document.getElementById('thpPointsChart'));";
                break;
            case "pie":
                $chart_options = [
                    'width'                    => $chart_width,
                    'height'                   => $chart_height,
                    'backgroundColor'          => $chart_bg_color,
                    'chartArea'                => [
                        'left'            => 0,
                        'top'             => 0,
                        'width'           => '100%',
                        'height'          => '60%',
                        'backgroundColor' => [
                            'stroke'      => 'black',
                            'strokeWidth' => 30
                        ]
                    ],
                    'colors'                   => $chart_colors,
                    'pieSliceBorderColor'      => 'transparent',
                    'sliceVisibilityThreshold' => 0
                ];
                $thp_chart     = "var thpChart = new google.visualization.PieChart(document.getElementById('thpPointsChart'));";
                break;
            case "donut":
                $chart_options = [
                    'width'                    => $chart_width,
                    'height'                   => $chart_height,
                    'backgroundColor'          => $chart_bg_color,
                    'pieHole'                  => 0.5,
                    'chartArea'                => [
                        'left'   => 0,
                        'top'    => 0,
                        'width'  => '100%',
                        'height' => '60%'
                    ],
                    'colors'                   => $chart_colors,
                    'pieSliceBorderColor'      => 'transparent',
                    'sliceVisibilityThreshold' => 0
                ];
                $thp_chart     = "var thpChart = new google.visualization.PieChart(document.getElementById('thpPointsChart'));";
                break;
            default:
                $chart_options = [
                    'width'                    => $chart_width,
                    'height'                   => $chart_height,
                    'backgroundColor'          => $chart_bg_color,
                    'chartArea'                => [
                        'left'   => 0,
                        'top'    => 0,
                        'width'  => '100%',
                        'height' => '50%'
                    ],
                    'colors'                   => $chart_colors,
                    'pieSliceBorderColor'      => 'transparent',
                    'sliceVisibilityThreshold' => 0
                ];
                $thp_chart     = "var thpChart = new google.visualization.PieChart(document.getElementById('thpPointsChart'));";
                break;
        }

        $chart_options = "var options = " . json_encode( $chart_options ) . ";";
        echo $chart_options;
        echo $thp_chart;
        ?>
                thpChart.draw(data, options);
            }

            jQuery(window).resize(function () {
                thpDrawChart();
            });

        </script>



        <?php
    }

    public function render_stages() {
        foreach ( $this->stageList as $stage ) {
            $stage->render();
        }
    }

// Getters

    public function get_profile_name() {
        return $this->user_data->profile_name;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }

    public function get_name() {
        return $this->name;
    }

    public function get_badgeList() {
        return $this->badgeList;
    }

    public function get_error() {
        return $this->error;
    }

    public function get_points_list() {
        return $this->pointsList;
    }

// Setters

    protected function set_badgeList() {
        $badges = array ();

        foreach ( $this->user_data->badges as $badge ) {
            $new_badge = new Badge( $badge );
            array_push( $badges, $new_badge );
        }

        $this->badgeList = $badges;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }

    protected function set_points() {
        $points_list = [];
        foreach ( $this->user_data->points as $key => $value ) {
            if ( strtolower( $key ) === "total" ) {
                $new_points        = new Points( $key, $value );
                $this->pointsTotal = $new_points;
            } elseif ( $value > 0 ) {
                $new_points = new Points( $key, $value );
                array_push( $points_list, $new_points );
            }
        }
        $this->pointsList = $points_list;
    }

    protected function set_stageList() {
// Create sorted badge list
// Knock off "Newbie" badge ie first badge (Check and see whether it has no courses?)
// Sort badges by stage name

        $sorted_badges = $this->badgeList;

// Knock "Newbie badge off" (first badge)
        array_shift( $sorted_badges );

        usort( $sorted_badges, function($badge_a, $badge_b) {
            $stage_a = $badge_a->get_stage_title();
            $stage_b = $badge_b->get_stage_title();

            if ( $stage_a === $stage_b ) {
                return 0;
            }

            return ($stage_a < $stage_b) ? -1 : 1;
        } );



        $current_stage_title = $sorted_badges[ 0 ]->get_stage_title();

        $stage_list = [];
        $step_list  = [];

        foreach ( $sorted_badges as $badge ) {
            $stage_title = $badge->get_stage_title();

// If stage is different from current stage then make new stage with current stage steps
            if ( $stage_title !== $current_stage_title ) {
// Create new Stage object
                $stage = new Stage( $current_stage_title, $step_list );
                array_push( $stage_list, $stage );

// Reassign current stage to badge stage title
                $current_stage_title = $badge->get_stage_title();

// Reset stage steps
                $step_list = [];

// Push badge in as first step
                array_push( $step_list, $badge );
            } else {
// Add badge to steps
                array_push( $step_list, $badge );
            }
        };

        $this->stageList = $stage_list;
    }

}
