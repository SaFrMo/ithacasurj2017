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

    // Prep calendar widget
    class Next_Meeting extends WP_Widget {

        public function __construct() {
            $widget_ops = array(
                'classname' => 'next_meeting',
                'description' => 'Show the date of the next chapter meeting, along with a link to more events',
            );
            parent::__construct( 'next_meeting', 'Next Meeting', $widget_ops );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            echo $args['before_widget'];

            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
            }

            ob_start(); ?>

                <i>running</i>

            <?php echo ob_get_clean();

            echo $args['after_widget'];

        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Next Meeting', 'text_domain' );
        	?>

        		<p>
            		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        		</p>

        	<?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update( $new_instance, $old_instance ) {
            $instance = array();
    		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    		return $instance;
        }

    }

    // Register widget
    add_action( 'widgets_init', function(){
    	register_widget( 'Next_Meeting' );
    });

?>
