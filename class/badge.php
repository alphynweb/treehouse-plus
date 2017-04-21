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
    protected $filename;
    protected $pathway;
    protected $is_saved = false;

    function __construct( $badgeInfo ) {
        $this->courses     = isset( $badgeInfo->courses ) ? $badgeInfo->courses : null;
        $this->earned_date = isset( $badgeInfo->earned_date ) ? $badgeInfo->earned_date : null;
        $this->icon_url    = isset( $badgeInfo->icon_url ) ? $badgeInfo->icon_url : null;
        $this->id          = isset( $badgeInfo->id ) ? $badgeInfo->id : null;
        $this->name        = isset( $badgeInfo->name ) ? trim( $badgeInfo->name ) : null;
        $this->set_stage_title( $this->courses );
        $this->set_filename();
        $this->set_pathway();
        if ( file_exists( $this->pathway ) ) {
            $this->is_saved = true;
        }
}

    // Render
    public function render() {
        // If badges is in filesystem then show that, otherwise use the Treehouse url
        $url = file_exists( $this->pathway ) ? $this->filename : $this->icon_url;
        ?>

        <li class="thp-badge" data-tooltip="<?php echo $this->name; ?>">
            <img src="<?php echo $url; ?>">
        </li>
        <?php
    }

    public function resize() {
        $badge_size = $_POST[ 'thp_badge_save_sizes' ];
        $new_width  = $badge_size;
        $new_height = $badge_size;
        $max_size   = $badge_size;
        // Create image from $tmp

        $temp_image = imagecreatetruecolor( $new_width, $new_height );
        imagesavealpha( $temp_image, true );
        $bg_color   = imagecolorallocatealpha( $temp_image, 0, 0, 0, 127 );
        imagefill( $temp_image, 0, 0, $bg_color );
        $tmp_image  = imagecreatefrompng( $this->icon_url );
        $tmp_name   = $tmp_image[ 'tmp_name' ];

        list($width, $height, $type, $attr) = getimagesize( $this->icon_url );
        if ( $width > $height ) {
            // Landscape
            $new_height = floor( $height / ($width / $max_size) );
        } elseif ( $width < $height ) {
            // Portrait
            $new_width = floor( $width / ($height / $max_size) );
        }

        imagecopyresampled( $temp_image, $tmp_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        return $temp_image;
    }

    public function save() {
        $upload_dir      = wp_upload_dir();
        $user_badges_dir = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
        if ( !file_exists( $user_badges_dir ) ) {
            wp_mkdir_p( $user_badges_dir );
        }
        $get            = wp_remote_get( $this->icon_url );
        $type           = wp_remote_retrieve_header( $get, 'content-type' );
        $badge_size     = $_POST[ 'thp_badge_save_sizes' ];
        $path_info      = pathinfo( $this->icon_url );
        $filename       = $path_info[ 'filename' ] . '-' . $badge_size . 'px.' . $path_info[ 'extension' ];
        $this->filename = esc_url( $upload_dir[ 'baseurl' ] . '/' . 'treehouse-plus-badges/' . $filename );
        
        
        $user_badges_dir = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
        $this->pathway   = $user_badges_dir . $filename;
        
        
        if ( is_file( $this->filename ) ) {
            return;
        }
        $dest          = $user_badges_dir . $filename;
        $resized_image = $this->resize( $dest );
        imagepng( $resized_image, $dest );

    }

// Getters and setters

    public function get_earned_date() {
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

    public function get_pathway() {
        return $this->pathway;
    }

    protected function set_filename() {
        $upload_dir     = wp_upload_dir();
        $filename       = basename( $this->icon_url );
        $this->filename = esc_url( $upload_dir[ 'baseurl' ] . '/' . 'treehouse-plus-badges/' . $filename );
    }

    protected function set_pathway() {
        $upload_dir      = wp_upload_dir();
        $user_badges_dir = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
        $this->pathway   = $user_badges_dir . basename( $this->icon_url );
    }

    protected function set_stage_title( $courses ) {
        if ( count( $courses ) < 1 ) {
            $this->stage_title = null;
            return;
        }
        $this->stage_title = $courses[ 0 ]->title;
        }

}
