<?php

    // Prep child theme
    function my_theme_enqueue_styles() {

        wp_enqueue_style( 'ixion-style', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'ixion-style' ),
            wp_get_theme()->get('Version')
        );
    }
    add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

    // Get Next Meeting widget
    require_once('meeting-widget.php');

?>
