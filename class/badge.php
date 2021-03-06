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

    function __construct( $badgeInfo ) {
        $this->courses     = isset( $badgeInfo->courses ) ? $badgeInfo->courses : null;
        $this->earned_date = isset( $badgeInfo->earned_date ) ? $badgeInfo->earned_date : null;
        $this->icon_url    = isset( $badgeInfo->icon_url ) ? $badgeInfo->icon_url : null;
        $this->id          = isset( $badgeInfo->id ) ? $badgeInfo->id : null;
        $this->name        = isset( $badgeInfo->name ) ? trim( $badgeInfo->name ) : null;
        $this->set_stage_title( $this->courses );
        $this->set_filename();
//        if ( file_exists( $this->pathway ) ) {
//            $this->is_saved = true;
//        }
        $test              = 0;
}

    // Render
    public function render() {
        // If badges is in filesystem then show that, otherwise use the Treehouse url
        // Get current saved user badge size
        $size     = get_option( 'thp_save_badge_size' );
        // Check in that size folder whether the file is already saved
        $upload_dir = wp_upload_dir();
        $filename = $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges/resized-' . $size . 'px/' . $this->get_filename();
        // If it is, use the filename
        if ( is_file( $filename ) && $size ) {
            $src = $upload_dir[ 'baseurl' ] . '/' . 'treehouse-plus-badges/resized-' . $size . 'px/' . $this->get_filename();
        } else {
            // If it isn't use the url
            $src = $this->get_icon_url();
        }
        $t = 0;
        ?>

        <li class="thp-badge" data-tooltip="<?php echo $this->name; ?>">
            <img src="<?php echo $src; ?>">
        </li>
        <?php
    }

    public function resize( $dest, $size = 50 ) {
        //$badge_size = $_POST[ 'thp_badge_save_sizes' ];
        //$badge_size = get_option( 'thp_badge_save_sizes' ) ? get_option( 'thp_badge_save_sizes' ) : 50;
        $new_width  = $size;
        $new_height = $size;
        $max_size   = $size;
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

    public function save( $size = 50 ) {
        // Check directories exist and create if they don't
        $upload_dir         = wp_upload_dir();
        $user_badges_dir    = trailingslashit( $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges' );
        $this->create_directory( $user_badges_dir );
        $resized_badges_dir = $user_badges_dir . 'resized-' . $size . 'px';
        $this->create_directory( $resized_badges_dir );

        $get  = wp_remote_get( $this->icon_url );
        $type = wp_remote_retrieve_header( $get, 'content-type' );

        $path_info = pathinfo( $this->icon_url );

        $filename       = $path_info[ 'filename' ] . '.' . $path_info[ 'extension' ];
        $this->filename = esc_url( $upload_dir[ 'baseurl' ] . '/' . 'treehouse-plus-badges/' . 'resized-' . $size . 'px/' . $filename );

        $this->pathway = $resized_badges_dir . '/' . $filename;

        // If file already exists then return
        if ( is_file( $this->filename ) ) {
            return;
        }

        // Resize and save
        $dest          = $resized_badges_dir . '/' . $filename;
        $resized_image = $this->resize( $dest, $size );
        imagepng( $resized_image, $dest );
    }

    // Creates a folder for the resized badges e.g. resized-50
    protected function create_directory( $dir ) {
        if ( !file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }
    }

// Getters and setters

    public function get_earned_date() {
        return $this->earned_date;
                }

    public function get_filename() {
        return $this->filename;
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
        // Get current saved user badge size
        $size     = get_option( 'thp_save_badge_size' );
        if (!$size) {
            return false;
        }
        $upload_dir = wp_upload_dir();
        // Check in that size folder whether the file is already saved
        $pathway = $upload_dir[ 'basedir' ] . '/' . 'treehouse-plus-badges/resized-' . $size . 'px/' . $this->get_filename();
        return $pathway;
    }

    protected function set_filename() {
        $this->filename = basename( $this->icon_url );
    }

    protected function set_pathway() {
        $this->pathway = basename( $this->icon_url );
    }

    protected function set_stage_title( $courses ) {
        if ( count( $courses ) < 1 ) {
            $this->stage_title = null;
            return;
        }
        $this->stage_title = $courses[ 0 ]->title;
        }

}
