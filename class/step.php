<?php

if ( !defined( 'ABSPATH' ) ) {
    exit( 'Access not allowed' );
}

class Step
{

    protected $name;
    protected $icon_url;

    function __construct( $step ) {
        $this->name     = $step->get_name();
        $this->icon_url = $step->get_icon_url();
}

}
