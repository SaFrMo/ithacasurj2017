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
        
        wp_register_script('site', get_stylesheet_directory_uri() . '/widgets/js/submit-event-widget.js', 'jquery', '1.0' );

        wp_enqueue_script('jquery');
        wp_enqueue_script('site', 'jquery');
    }
    add_action('wp_enqueue_scripts', 'enqueue_surj_scripts');

    // Get widgets
    require('widgets/meeting-widget.php');
    require('widgets/submit-widget.php');



?>
