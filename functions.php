<?php

    // Prep child theme
    function enqueue_surj_styles() {

        wp_enqueue_style( 'ixion-style', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'ixion-style' ),
            wp_get_theme()->get('Version')
        );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_surj_styles' );

    // Prep scripts
    function enqueue_surj_scripts() {
        wp_enqueue_script('jquery');
    }
    add_action('wp_enqueue_scripts', 'enqueue_surj_scripts');

    // Get widgets
    require_once('meeting-widget.php');
    require_once('submit-widget.php');

?>
