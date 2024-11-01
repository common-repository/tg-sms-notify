<?php

	require_once( 'notifycore.php' );

	add_action ("woocommerce_order_status_changed", "tgsmsnotify_notify_WC_order_status_processing", 99, 3 );

	function tgsmsnotify_notify_WC_order_status_processing ( $order_id, $old_status, $new_status ) {
		error_log ("fired the CHANGED hook");

		$sms_flag_db = tgsmsnotify_fetch_sms_settings_from_db();

		if ( ( $sms_flag_db[5] == 0 ) || ( $sms_flag_db[6] == 0 ) ) {
            error_log('Customer Notifications DISABLED');
            return;
		}
		
		$options = get_option( 'tgsmsnotify_setting_section' );

		$order = wc_get_order( $order_id );
		$orderid = $order->get_id();
		error_log ("Order ID -----> $orderid");

		if ( $new_status === 'processing' ) {
			error_log('Order Status is set to PROCESSING');
			
			$message_text = 'Order Id: ' . $orderid;
				
			$notify_message_text = strval( esc_textarea( $options['smsonOrderProcessingMessage'] ) );
			error_log("notify =======> $notify_message_text");
            $notify_message_text !== '' ? $notify_message_text = ($message_text . $notify_message_text) : $notify_message_text = ($message_text . ' Order started processing') ;
			
			tgsmsnotify_send_twilio_sms ( $nickanme, $notify_message_text );


		} elseif ( $new_status === 'completed' ) {
			error_log('Order Status is set to COMPLETED');

			$message_text = 'Order Id: ' . $orderid;
				
			$notify_message_text = strval( esc_textarea( $options['smsonOrderCompletedMessage'] ) );
			error_log("notify =======> $notify_message_text");
            $notify_message_text !== '' ? $notify_message_text = ($message_text . $notify_message_text) : $notify_message_text = ($message_text . ' Order completed') ;
			
			tgsmsnotify_send_twilio_sms ( $nickanme, $notify_message_text );

		}
	}
?>