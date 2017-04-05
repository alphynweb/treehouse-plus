<?php
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

class Badge
{

    protected $id;
    protected $name;
    protected $courses;
    protected $icon_url;
    protected $stage_title;
    protected $earned_date;

    function __construct( $badgeInfo ) {
        $this->courses     = isset( $badgeInfo->courses ) ? $badgeInfo->courses : null;
        $this->earned_date = isset( $badgeInfo->earned_date ) ? $badgeInfo->earned_date : null;
        $this->icon_url    = isset( $badgeInfo->icon_url ) ? $badgeInfo->icon_url : null;
        $this->id          = isset( $badgeInfo->id ) ? $badgeInfo->id : null;
        $this->name        = isset( $badgeInfo->name ) ? trim($badgeInfo->name) : null;
        $this->set_stage_title( $this->courses );
}

    // Render
    public function render() {
        ?>

        <li class="thp-badge" data-tooltip="<?php echo $this->name; ?>">
            <img src="<?php echo $this->icon_url; ?>">
        </li>
        <?php
    }

// Getters and setters

    public  function get_earned_date() {
        return $this->earned_date;
    }

    public function get_icon_url() {
        return $this->icon_url;
                                }

    public function get_name() {
        return $this->name;
        }

    public function get_stage_title() {
        return $this->stage_title;
    }

    protected function set_stage_title( $courses ) {
        if ( count( $courses ) < 1 ) {
            $this->stage_title = null;
            return;
        }
        $this->stage_title = $courses[ 0 ]->title;
        }

}
