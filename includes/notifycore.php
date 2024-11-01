<?php
    require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
    use Twilio\Rest\Client;

    /*function test_sms() {
        $account_sid = 'AC68e085b8532883985b25d6d2f6095b66';
        $auth_token = '9793c8df7ff837d1dc41d4addf98808a';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+15133277846";
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            '+919560384344',
            array(
                'from' => $twilio_number,
                'body' => 'I sent this message in under 10 minutes!'
            )
        );
    }*/

    function tgsmsnotify_send_twilio_sms ( $nickname = 'admin', $msg = NULL ){

        $sms_flag_db = tgsmsnotify_fetch_sms_settings_from_db ();
        $twilio_details_from_db = tgsmsnotify_get_twilio_details_from_db ();

        if ( $sms_flag_db[0] == 0 ) 
            return;
        else {
            $account_sid = $twilio_details_from_db[0];
            $auth_token = $twilio_details_from_db[1];
            $twilio_number = $twilio_details_from_db[2];
            $recipient = strval( "+".strval( $twilio_details_from_db[3] ) );
    
            $message_text = strval ( $msg );
    
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                //'+919560384344',
                $recipient,
                array(
                    'from' => $twilio_number,
                    'body' => $message_text
                )
            );
        }
    }
    
    function tgsmsnotify_get_twilio_details_from_db () {

        $options = get_option( 'tgsmsnotify_setting_section' );
        if ( is_null ( $options ) ) {
            error_log ('The Twilio settings are missing in the DB');
            return;
        }

        // Your Account SID and Auth Token from twilio.com/console
        if ( !isset ( $options['twilio_sid'] ) ) $account_sid = '' ; else $account_sid = sanitize_text_field( $options['twilio_sid'] );

        if ( !isset ( $options['twilio_auth'] ) ) $auth_token = '' ; else $auth_token = sanitize_text_field( $options['twilio_auth'] );
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // A Twilio number you own with SMS capabilities
        if ( !isset ( $options['twilio_from'] ) ) {
            $twilio_number = '' ; 
        } else {
            $twilio_number = "+" . $options['twilio_from'];
            $twilio_number = sanitize_text_field( $twilio_number );
        }

        if ( !isset ( $options['manager_number'] ) ) $recipient = '' ; else $recipient = intval(sanitize_text_field( $options['manager_number']));
        
        if ( !isset ( $options['smsMessage'] ) ) $text = '' ; else $text = esc_textarea( $options['smsMessage'] );
        
        return array($account_sid, $auth_token, $twilio_number, $recipient, $text);
    }

    function tgsmsnotify_fetch_sms_settings_from_db () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        $mail_admin_flag = (checked( 1, $options['toManager'], false )) ? 1 : 0; 
        $sms_admin_flag = (checked( 1, $options['toManager'], false )) ? 1 : 0; 
        $message_text = $options['smsMessage'];

        $sms_fire_on_product_add = (checked( 1, $options['smsNewProduct'], false )) ? 1 : 0; 
        $sms_fire_on_price_change = (checked( 1, $options['smsPriceChange'], false )) ? 1 : 0; 
        $sms_fire_on_qty_below_threshold = (checked( 1, $options['smsQtyAlert'], false )) ? 1 : 0;

        $sms_fire_on_order_processing = (checked( 1, $options['smsonOrderProcessing'], false )) ? 1 : 0; 
        $sms_fire_on_order_completed = (checked( 1, $options['smsonOrderCompleted'], false )) ? 1 : 0; 
        
        return array( $sms_admin_flag, $message_text, $sms_fire_on_product_add, $sms_fire_on_price_change, $sms_fire_on_qty_below_threshold, $sms_fire_on_order_processing, $sms_fire_on_order_completed );
    }

    add_action('transition_post_status', 'tgsmsnotify_sms_admin_on_post_publish', 10, 3);

    function tgsmsnotify_sms_admin_on_post_publish($new_status, $old_status, $post)  {
        $mail_flag_db = tgsmsnotify_fetch_sms_settings_from_db();
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( ( $old_status == $new_status ) || ( $mail_flag_db[0] == 0 ) || (sanitize_text_field( $options['manager_number'] ) == '' ) ) {
            error_log('New Post Published Notification disabled or this is not a new post or Manager Number missing');
            return;
        }

        if($new_status === 'publish' && $old_status !== 'publish' && $post->post_type === 'post') {
            $author_id = $post->post_author;
            $nickname = get_the_author_meta( 'nicename', $author_id );

            $message_text = strval ( $nickname . " " . strval( $options['smsMessage'] ) );
			$message_text != '' ? $message_text : 'Post Published' ;
            
            tgsmsnotify_send_twilio_sms ( $nickname, $message_text );
            return $post;
        }
    }

    add_action('transition_post_status', 'tgsmsnotify_mail_admin_on_post_publish', 10, 4);

    function tgsmsnotify_mail_admin_on_post_publish($new_status, $old_status, $post)  {
        $mail_flag_db = tgsmsnotify_fetch_sms_settings_from_db();

        if ( ( $old_status == $new_status ) || $mail_flag_db[0] == 0 ) {
            error_log('Mail function checking status');
            return;
        }

        if($new_status === 'publish' && $old_status !== 'publish' && $post->post_type === 'post') {
            
            $author_id = $post->post_author;
            $nickname = get_the_author_meta( 'nicename', $author_id );
            $admin_email = get_bloginfo ( 'admin_email' ); //extract customer data for the order

            $message = $nickname;
            $message .= $mail_flag_db[1];
            //mail($admin_email, "New post published", $message);
            error_log('Finishing the steps in Mail function but doing nothing');
            return $post;
        }
    }?>