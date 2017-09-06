<?php

    // Prep calendar widget
    class Submit_Events extends WP_Widget {

        public function __construct() {
            $widget_ops = array(
                'classname' => 'submit_event',
                'description' => 'Streamlined event submission process',
            );
            parent::__construct( 'next_meeting', 'Submit Event', $widget_ops );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {

            // Only show for admins
            if( ! is_user_logged_in() ) return;

            echo $args['before_widget'];

            ob_start(); ?>

                <h2>Submit Event</h2>

                <p>Only administrators can see this section. Enter an event's information here to have it promoted by Ithaca SURJ!</p>

                <script>
                    function submitSurjEvent(){

                        // assumes jQuery is loaded
                        jQuery.post(
                            '<?php echo admin_url('admin-ajax.php'); ?>',
                            jQuery('.submit-event').serialize(),
                            function(data){
                                console.log(data);
                            }
                        );

                        return false;
                    }
                </script>

                <form class="submit-event" onsubmit="return submitSurjEvent();">

                    <label for="event-title">Title</label>
                    <input id="event-title" name="event-title" type="text"/>

                    <label for="event-location">Location</label>
                    <input id="event-location" name="event-location" type="text"/>

                    <label for="event-date">Date</label>
                    <input id="event-date" name="event-date" type="date"/>

                    <label for="event-start-time">Start Time</label>
                    <input id="event-start-time" name="event-start-time" type="time"/>

                    <label for="event-finish-time">Finish Time</label>
                    <input id="event-finish-time" name="event-finish-time" type="time"/>

                    <label for="event-description">Description</label>
                    <textarea id="event-description" name="event-description"></textarea>

                    <label for="event-contact">Contact Email</label>
                    <input id="event-contact" name="event-contact" type="email"/>

                    <h3>Submit to:</h3>

                    <div class="checks">
                        <input id="listserv" name="listserv" type="checkbox" checked/>
                        <label for="listserv">Listserv</label><br/>

                        <input id="facebook" name="facebook" type="checkbox" checked/>
                        <label for="facebook">Facebook</label><br/>

                        <input id="calendar" name="calendar" type="checkbox" checked/>
                        <label for="calendar">Events Calendar</label><br/>
                    </div>

                    <input type="submit" value="Submit!"/>

                </form>

            <?php

            echo ob_get_clean();

            echo $args['after_widget'];

        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Submit Event', 'text_domain' );
            $after_date = ! empty( $instance['after_date'] ) ? $instance['after_date'] : esc_html__( 'Test after info', 'text_domain' );
            ?>

                <!-- <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'after_date' ) ); ?>"><?php esc_attr_e( 'Text after info:', 'text_domain' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'after_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'after_date' ) ); ?>" type="textfield" value="<?php echo esc_attr( $after_date ); ?>">
                </p> -->

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
            $instance['after_date'] = strip_tags( $new_instance['after_date'] );

            return $instance;
        }




    }

    // Register widget
    add_action( 'widgets_init', function(){
        register_widget( 'Submit_Events' );
    });

    // Register endpoint
    add_action('wp_ajax_submit_surj_event', 'submit_surj_event');

    function submit_surj_event(){

    }

?>
