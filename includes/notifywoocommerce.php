<?php

    require_once( 'notifycore.php' );

    add_action( 'woocommerce_reduce_order_stock', 'tgsmsnotify_notifyQtyAlert' );
    
    function tgsmsnotify_notifyQtyAlert( $order ) { // you get an object $order as an argument
        $mail_flag_db = tgsmsnotify_fetch_sms_settings_from_db();
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( ( $mail_flag_db[4] == 0 ) || (sanitize_text_field( $options['manager_number'] ) == '' ) ) {
            error_log('Quantity Threshold Notification disabled or Manager Number missing');
            return;
        }

        $qtyThreshold = $options['smsQtyThreshold'];
        if ( $qtyThreshold == '' || $qtyThreshold <=0 ) $qtyThreshold = 0;
        $message = 'Quantity going low for ';

        $items = $order->get_items();
        
        foreach( $items as $item ) {
            $product = wc_get_product( $item['product_id'] );

            if ($product->get_stock_quantity() <= $qtyThreshold) 
                $message .= $product->get_name() . ' ';
        }
        //mail("bbuurrff@gmail.com", "New post published", $message);
        tgsmsnotify_send_twilio_sms ( NULL, $message );
    }

    function tgsmsnotify_new_product ( $newstatus = NULL ) {
        static $mystatus = 'old';
        if ( isset( $newstatus) )
            $mystatus = $newstatus ;
        else
            return $mystatus;
    }

    add_action('transition_post_status', 'tgsmsnotify_sms_admin_on_product_publish', 10, 3);

    function tgsmsnotify_sms_admin_on_product_publish($new_status, $old_status, $post)  {
        $mail_flag_db = tgsmsnotify_fetch_sms_settings_from_db();
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( ( $mail_flag_db[2] == 0 ) || (sanitize_text_field( $options['manager_number'] ) == '' ) ) {
            error_log('Product Addition Notification disabled or Manager Number missing');
            return;
        }

        if($new_status === 'publish' && $old_status !== 'publish' && $post->post_type == 'product') {

            $product_id = $post->ID;
            $product = wc_get_product( $product_id );
            $product_name = $product->get_name();
            $message_text = $product_name . ' ';

            $notify_message_text = strval( esc_textarea( $options['smsNewProductMessage'] ) );
            $notify_message_text !== '' ? $notify_message_text = ($message_text . $notify_message_text) : $notify_message_text = ($message_text . ' Product Added') ;

            error_log("Final message ----> $notify_message_text");
            //mail("bbuurrff@gmail.com", "New post published", $notify_message_text);
            tgsmsnotify_send_twilio_sms ( $nickname, $notify_message_text );
            tgsmsnotify_new_product('new');
            
        } else {

            if ( ( $mail_flag_db[3] == 0 ) || (sanitize_text_field( $options['manager_number'] ) == '' ) ) {
                error_log('Price Change Notification disabled or Manager Number missing');
                return;
            }

            if ( $post->post_type == 'product') {
                $product_id = $post->ID;
                $product = wc_get_product( $product_id );

                $old_regular_price = $product->get_regular_price();
                $old_sale_price = $product->get_sale_price();
    
                error_log("Regular -------> $old_regular_price");
                error_log("Sale -------> $old_sale_price");
            }

            if ( isset ($_POST["_regular_price"]) && isset ($_POST["_regular_price"]) ) {
                $new_regular_price = floatval ( $_POST["_regular_price"] );
                $new_sale_price = floatval ( $_POST["_sale_price"] );
                error_log("POST Regular -------> $new_regular_price");
                error_log("POST Sale -------> $new_sale_price");

                if ( ($old_regular_price != $new_regular_price) || $old_sale_price != $new_sale_price ) {
                    $message_text = strval( esc_textarea( $options['smsPriceChangeMessage'] ) );
                    if ( $old_sale_price != $new_sale_price ) {
                        $message_text .= ' Changed Sale Price of ' . $product->get_name() . ' is ' . wc_format_decimal($new_sale_price,3);
                    }
                    if ( $old_regular_price != $new_regular_price ) {
                        $message_text .= ' Changed Price of ' . $product->get_name() . ' is ' . wc_format_decimal($new_regular_price,3);
                    }
                    $message_text !== '' ? $message_text : 'Price Changed' ;
                    error_log("Final message -------> $message_text");
                    //mail("bbuurrff@gmail.com", "New post published", $message_text);
                    tgsmsnotify_send_twilio_sms ( $nickname, $message_text );
                }                
            }
        }
        
        return $post;
    }?>