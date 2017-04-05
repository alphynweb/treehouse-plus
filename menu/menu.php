<?php

function thp_menu() {
    add_options_page(
            'Treehouse Plus - Options', // Page title
            'Treehouse Plus', // Menu title
            'manage_options', // Capability
            'treehouse-plus', // Menu slug
            'thp_render_settings_page' // Callback
    );
}

add_action( 'admin_menu', 'thp_menu' );
