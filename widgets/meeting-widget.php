<?php

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

            // Get cached value
            $transient_key = '_custom_calendar_events_';
            $events_list = get_transient( $transient_key );
            if( $events_list === false ){

                // set to EST
                date_default_timezone_set('US/Eastern');

                $calendar_id = '94ruogf3dumfotdius9eqrnpjc@group.calendar.google.com';
                $api_key = 'AIzaSyBoHBbcX5g_UTPDP6xSqXX88zSevTxfUeo';
                $query = 'Chapter%20Meeting';
                $time_min = date(DATE_RFC3339);

                // see https://developers.google.com/google-apps/calendar/v3/reference/events/list
                $url =
                    'https://www.googleapis.com/calendar/v3/calendars/'
                    . $calendar_id
                    . '/events?key=' . $api_key
                    . '&q=' . $query
                    . '&timeMin=' . $time_min
                    . '&orderBy=startTime'
                    . '&singleEvents=true';

                // see https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL     => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache"
                    )
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if( ! $err ){
                    $decoded_json = json_decode($response, true);
                    // expires in 5 minutes
                    set_transient( $transient_key, $decoded_json, 60 * 5 );

                    $events_list = $decoded_json;
                } else {
                    $output = $err;
                }

            }

            $output = '';
            $event = $events_list['items'][0];

            $start_object = DateTime::createFromFormat(DATE_RFC3339, $event['start']['dateTime']);
            $start = date_format( $start_object, 'g:i A' );
            $end_object = DateTime::createFromFormat(DATE_RFC3339, $event['end']['dateTime']);
            $date = date_format( $start_object, 'l, F j' );
            $end = date_format( $end_object, 'g:i A' );

            // Orientation starts 30m earlier than meeting
            $start_object->sub( date_interval_create_from_date_string('30 minutes') );
            $orientation = date_format( $start_object, 'g:i A' );

            ob_start(); ?>

                <p>
                    <?php echo $date; ?><br>
                    <?php echo $start; ?> to <?php echo $end; ?><br>
                    <?php echo $orientation; ?> orientation<br>
                    Friends Meeting House<br>
                    120 3rd St., Ithaca, NY<br>
                    <a href="<?php echo $event['htmlLink']; ?>" target="_blank">More info...</a>
                </p>

                <p>
                    <a href="/calendar">More events...</a>
                </p>

            <?php echo ob_get_clean();

            if ( ! empty( $instance['after_date'] ) ) {
                echo apply_filters( 'widget_title', $instance['after_date'] );
            }

            echo $args['after_widget'];

        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Next Meeting', 'text_domain' );
            $after_date = ! empty( $instance['after_date'] ) ? $instance['after_date'] : esc_html__( 'Test after info', 'text_domain' );
            ?>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'after_date' ) ); ?>"><?php esc_attr_e( 'Text after info:', 'text_domain' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'after_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'after_date' ) ); ?>" type="textfield" value="<?php echo esc_attr( $after_date ); ?>">
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
            $instance['after_date'] = strip_tags( $new_instance['after_date'] );

            return $instance;
        }

    }

    // Register widget
    add_action( 'widgets_init', function(){
        register_widget( 'Next_Meeting' );
    });

?>
