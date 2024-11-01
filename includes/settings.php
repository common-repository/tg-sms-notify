<?php

    require_once( 'notifycore.php' );

    add_action( 'admin_menu', 'tgsmsnotify_settings_menu' );
    
    function tgsmsnotify_settings_menu() {
        add_options_page( 
            'TGSMS Notify', 
            'TGSMS Notify Settings', 
            'manage_options', 
            'tgsmsnotify-main-settings', 
            'tgsmsnotify_settings_page' 
        );
    }


/*     public function display_options() {
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
        if ( $active_tab == 'general' ) {
            //PC::debug(  'general', 'tab name:' );
            register_setting( 'general_section', 'eswc_settingz', array( $this, 'sanitize_general' ) );
            // ID / title / cb / page       
            $this->add_settings_section( 'general_section', null, array( $this, 'general_section_cb' ), $this->settings_page_name );
            // ID / title / cb / page / section /args
            add_settings_field( 'eswc_kill_gutenberg', __( 'Gutenberg', 'extra-settings-for-woocommerce' ), array( $this, 'eswc_kill_gutenberg_cb' ), $this->settings_page_name, 'general_section' );
            add_settings_field( 'eswc_change_email_author', __( 'WP core email author', 'extra-settings-for-woocommerce' ), array( $this, 'eswc_change_email_author_cb' ), $this->settings_page_name, 'general_section' );
        } else if ( $active_tab == 'account' ) {
            //PC::debug(  'account', 'tab name:' );
            register_setting( 'account_section', 'eswc_settingz', array( $this, 'sanitize_account' ) );
            $this->add_settings_section( 'account_section', null, array( $this, 'account_section_cb' ), $this->settings_page_name );
            add_settings_field( 'eswc_redirect', __( 'Login redirect', 'extra-settings-for-woocommerce' ), array( $this, 'eswc_redirect_cb' ), $this->settings_page_name, 'account_section' );      
        }
    } */


    add_action( 'admin_init', 'tgsmsnotify_settings_init' );
    
    function tgsmsnotify_settings_init () {
        
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'sms-sender-settings';
        //error_log($active_tab);

        // tab for SMS Sender (Twilio) account setting 

        if ( $active_tab == 'sms-sender-settings' ) {

            add_settings_section(
                'tgsmsnotify_twilio_setting_section',
                '',
                'tgsmsnotify_twilio_setting_section_callback_function',
                'tgsmsnotify_twilio_settings_page'
            );

            add_settings_field( 
                'tgsmsnotify_twilio_account_sid_field', 
                'Twilio Account SId', 
                'tgsmsnotify_twilio_account_sid_field_callback', 
                'tgsmsnotify_twilio_settings_page', 
                'tgsmsnotify_twilio_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_twilio_account_auth_token_field',
                'Twilio Auth Token',
                'tgsmsnotify_twilio_account_auth_token_field_callback',
                'tgsmsnotify_twilio_settings_page',
                'tgsmsnotify_twilio_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_twilio_account_from_number_field',
                'Twilio From number (without +)',
                'tgsmsnotify_twilio_account_from_number_field_callback',
                'tgsmsnotify_twilio_settings_page',
                'tgsmsnotify_twilio_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_deletePluginData_field',
                'Delete Plugin Data on Uninstall',
                'tgsmsnotify_deletePluginData_field_callback',
                'tgsmsnotify_twilio_settings_page',
                'tgsmsnotify_twilio_setting_section'
            );
    
            register_setting( 'tgsmsnotify_twilio_setting_section', 'tgsmsnotify_setting_section', 'tgsmsnotify_sanitize_twilio' );

        // tab for setting Manager Notifications
        } else if ( $active_tab == 'manager-options' ) {

            add_settings_section(
                'tgsmsnotify_setting_section',
                '',
                'tgsmsnotify_setting_section_callback_function',
                'tgsmsnotify_manager_settings_page'
            );

            add_settings_field( 
                'tgsmsnotify_sendSMS_managerNumber_field', 
                'Recipient Manager Number (without +)', 
                'tgsmsnotify_sendSMS_managerNumber_field_callback', 
                'tgsmsnotify_manager_settings_page', 
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_field',
                'Send SMS for New Post',
                'tgsmsnotify_sendSMS_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field( 
                'tgsmsnotify_sendSMS_messageTemplate_field', 
                'Custom message for New Post', 
                'tgsmsnotify_sendSMS_messageTemplate_callback', 
                'tgsmsnotify_manager_settings_page', 
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_newProduct_field',
                'Send SMS for New Product',
                'tgsmsnotify_sendSMS_newProduct_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_newProductMessage_field',
                'Custom message for New Product',
                'tgsmsnotify_sendSMS_newProductMessage_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_priceChange_field',
                'Send SMS for Product Price Change',
                'tgsmsnotify_sendSMS_priceChange_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_priceChangeMessage_field',
                'Custom message for Price Change',
                'tgsmsnotify_sendSMS_priceChangeMessage_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_qtyAlert_field',
                'Send SMS for Quantity Alert',
                'tgsmsnotify_sendSMS_qtyAlert_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_qtyThreshold_field',
                'Define Quantity Threshold',
                'tgsmsnotify_sendSMS_qtyThreshold_callback',
                'tgsmsnotify_manager_settings_page',
                'tgsmsnotify_setting_section'
            );

            register_setting( 'tgsmsnotify_setting_section', 'tgsmsnotify_setting_section', 'tgsmsnotify_sanitize_manager' );

        } else if ( $active_tab == 'customer-options' ) {
        // tab for setting Customer Notifications

            add_settings_section(
                'tgsmsnotify_customer_setting_section',
                '',
                'tgsmsnotify_customer_setting_section_callback_function',
                'tgsmsnotify_customer_settings_page'
            );
        
            add_settings_field(
                'tgsmsnotify_sendSMS_onOrderProcessing_field',
                'Send SMS on Order Processing stage',
                'tgsmsnotify_sendSMS_onOrderProcessing_callback',
                'tgsmsnotify_customer_settings_page',
                'tgsmsnotify_customer_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_onOrderProcessing_Message_field',
                'Custom message for Order Processing stage',
                'tgsmsnotify_sendSMS_onOrderProcessing_Message_field_callback',
                'tgsmsnotify_customer_settings_page',
                'tgsmsnotify_customer_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_onOrderCompleted_field',
                'Send SMS on Order Completion',
                'tgsmsnotify_sendSMS_onOrderCompleted_callback',
                'tgsmsnotify_customer_settings_page',
                'tgsmsnotify_customer_setting_section'
            );

            add_settings_field(
                'tgsmsnotify_sendSMS_onOrderCompleted_Message_field',
                'Custom message for Order Completion',
                'tgsmsnotify_sendSMS_onOrderCompleted_Message_field_callback',
                'tgsmsnotify_customer_settings_page',
                'tgsmsnotify_customer_setting_section'
            );

            register_setting( 'tgsmsnotify_customer_setting_section', 'tgsmsnotify_setting_section', 'tgsmsnotify_sanitize_customer' ); 
        }
    }
    
    function tgsmsnotify_twilio_setting_section_callback_function() {

        if ( !isset ( $_GET["tab"] ) || ( $_GET["tab"] == "sms-sender-settings" ) )
        {
            $twilio_flag_db = tgsmsnotify_get_twilio_details_from_db();

            if ( ( $twilio_flag_db[0] == '') || ($twilio_flag_db[1] == '') || ($twilio_flag_db[2] == '') ) {
                $html = '<p color="red">Twilio settings not available, SMS would not be triggered.</p>';
                echo $html ;
            } else {
                $html = '<p style="color:red;">Twilio settings available but not shown here, enter new settings and hit Save to overwrite the old ones.</p>';
                echo $html ;
            }
        }
    }

    function tgsmsnotify_twilio_account_sid_field_callback() {
        $options = get_option( 'tgsmsnotify_setting_section' );

        $html = '<input type="text" id="tgsmsnotify_twilio_account_sid_field" name="tgsmsnotify_setting_section[twilio_sid]" />';

        echo $html ;
    }

    function tgsmsnotify_twilio_account_auth_token_field_callback() {
        $options = get_option( 'tgsmsnotify_setting_section' );

        $html = '<input type="text" id="tgsmsnotify_twilio_account_from_number_field" name="tgsmsnotify_setting_section[twilio_auth]" />';

        echo $html ;
    }

    function tgsmsnotify_twilio_account_from_number_field_callback() {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['twilio_from']) ) {
            $html = '<input type="text" id="tgsmsnotify_twilio_account_auth_token_field" name="tgsmsnotify_setting_section[twilio_from]" value="' . intval ($options['twilio_from']) . '" />';
            $html .= '<br><p><i>With country code prefixed</i></p>';
        } else {
            $html = '<input type="text" id="tgsmsnotify_twilio_account_auth_token_field" name="tgsmsnotify_setting_section[twilio_from]" />';
            $html .= '<br><p><i>With country code prefixed</i></p>';
        }

        echo $html ;
    }

    function tgsmsnotify_deletePluginData_field_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if (empty($options['deleteTGSMSdata'])) $options['deleteTGSMSdata']=0;
        $html = '<input type="checkbox" id="tgsmsnotify_deletePluginData_field" name="tgsmsnotify_setting_section[deleteTGSMSdata]" value="1"' . checked( 1, $options['deleteTGSMSdata'], false ) . ' />';
        $html .= '<label for="tgsmsnotify_deletePluginData_field">Please check this option if you want to delete settings on plugin uninstall.<br>';
        $html .= '<i>It is a little bit of work to add all the settings again.</i></label><br>';

        echo $html ;
    }

    
    
    /* add_action( 'admin_init', 'tgsmsnotify_settings_init' );
    
    function tgsmsnotify_settings_init() {
        
        
            
        }
    } */

     function tgsmsnotify_setting_section_callback_function() {
        require_once('notifycore.php');

        if ( isset ( $_GET["tab"] ) && ( $_GET["tab"] == "manager-options" ) ) {
            $html = '<p>Set up your Manager Options here</p>';
            echo $html ;
        }
    } 

    function tgsmsnotify_sendSMS_managerNumber_field_callback() {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['manager_number'] ) ) {
            $html = '<input type="text" id="tgsmsnotify_sendSMS_managerNumber_field" name="tgsmsnotify_setting_section[manager_number]" value="' . intval ($options['manager_number']) . '" />';
            $html .= '<br><p><i>With country code prefixed</i></p>';
        } else {
            $html = '<input type="text" id="tgsmsnotify_sendSMS_managerNumber_field" name="tgsmsnotify_setting_section[manager_number]" />';
            $html .= '<br><p><i>With country code prefixed</i></p>';
        }
        echo $html ;
    }

    function tgsmsnotify_sendSMS_messageTemplate_callback() {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsMessage'] ) ) 
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_messageTemplate_field" name="tgsmsnotify_setting_section[smsMessage]">' . esc_textarea( $options['smsMessage'] ) . '</textarea>';
        else
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_messageTemplate_field" name="tgsmsnotify_setting_section[smsMessage]"> </textarea>';

        echo  $html ;
    }

    function tgsmsnotify_sendSMS_callback() { 
        $options = get_option( 'tgsmsnotify_setting_section' );
     
        if ( isset ( $options['toManager'] ) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_field" name="tgsmsnotify_setting_section[toManager]" value="1"' . checked( 1, $options['toManager'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_field">Notify on new Post Add</label><br>';            
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_field" name="tgsmsnotify_setting_section[toManager]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_field">Notify on new Post Add</label><br>';            
        }

        echo  $html ;
    }

    function tgsmsnotify_sendSMS_newProduct_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsNewProduct'] ) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_newProduct_field" name="tgsmsnotify_setting_section[smsNewProduct]" value="1"' . checked( 1, $options['smsNewProduct'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_newProduct_field">Notify on new Product Add</label><br>';
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_newProduct_field" name="tgsmsnotify_setting_section[smsNewProduct]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_newProduct_field">Notify on new Product Add</label><br>';            
        }

        echo  $html ;
    }

    function tgsmsnotify_sendSMS_newProductMessage_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsNewProductMessage']) )
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_newProductMessage_field" name="tgsmsnotify_setting_section[smsNewProductMessage]">' . esc_textarea( $options['smsNewProductMessage'] ) . '</textarea>';
        else
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_newProductMessage_field" name="tgsmsnotify_setting_section[smsNewProductMessage]"> </textarea>';

        echo  $html ;
    }

    function tgsmsnotify_sendSMS_priceChange_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsPriceChange']) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_priceChange_field" name="tgsmsnotify_setting_section[smsPriceChange]" value="1"' . checked( 1, $options['smsPriceChange'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_priceChange_field">Notify on Price Change</label><br>';
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_priceChange_field" name="tgsmsnotify_setting_section[smsPriceChange]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_priceChange_field">Notify on Price Change</label><br>';            
        }

        echo $html ;
    }

    function tgsmsnotify_sendSMS_priceChangeMessage_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsPriceChangeMessage'] ) )
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_priceChangeMessage_field" name="tgsmsnotify_setting_section[smsPriceChangeMessage]">' . esc_textarea( $options['smsPriceChangeMessage'] ) . '</textarea>';
        else
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_priceChangeMessage_field" name="tgsmsnotify_setting_section[smsPriceChangeMessage]"> </textarea>';

        echo $html ;
    }

    function tgsmsnotify_sendSMS_qtyAlert_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ($options['smsQtyAlert'] ) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_qtyAlert_field" name="tgsmsnotify_setting_section[smsQtyAlert]" value="1"' . checked( 1, $options['smsQtyAlert'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_qtyAlert_field">Notify on Quantity Below Threshold</label><br>';
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_qtyAlert_field" name="tgsmsnotify_setting_section[smsQtyAlert]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_qtyAlert_field">Notify on Quantity Below Threshold</label><br>';            
        }

        echo $html ;
    }

    function tgsmsnotify_sendSMS_qtyThreshold_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );
        
        if ( isset ( $options['smsQtyThreshold'] ) )
            $html = '<input type="number" id="tgsmsnotify_sendSMS_qtyThreshold_field" name="tgsmsnotify_setting_section[smsQtyThreshold]" value="' . intval ($options['smsQtyThreshold']) . '" />';
        else
        $html = '<input type="number" id="tgsmsnotify_sendSMS_qtyThreshold_field" name="tgsmsnotify_setting_section[smsQtyThreshold]" />';

        echo $html ;
    }

    
    
    /* add_action( 'admin_init', 'tgsmsnotify_customer_settings_init' );
    
    function tgsmsnotify_customer_settings_init() {
        
        

            
        }
    } */

     function tgsmsnotify_customer_setting_section_callback_function() {

        if ( isset ( $_GET["tab"] ) && ( $_GET["tab"] == "customer-options" ) ) {
            $html = '<p>Set up your Customer Notification Options here</p>';
            echo $html ;
        }
    } 

    function tgsmsnotify_sendSMS_onOrderProcessing_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsonOrderProcessing']) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_onOrderProcessing_field" name="tgsmsnotify_setting_section[smsonOrderProcessing]" value="1"' . checked( 1, $options['smsonOrderProcessing'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_onOrderProcessing_field">Notify Customer on Order Processing</label><br>';
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_onOrderProcessing_field" name="tgsmsnotify_setting_section[smsonOrderProcessing]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_onOrderProcessing_field">Notify Customer on Order Processing</label><br>';            
        }

        echo $html ;
    }

    function tgsmsnotify_sendSMS_onOrderProcessing_Message_field_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsonOrderProcessingMessage'] ) )
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_onOrderProcessing_Message_field" name="tgsmsnotify_setting_section[smsonOrderProcessingMessage]">' . esc_textarea( $options['smsonOrderProcessingMessage'] ) . '</textarea>';
        else
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_onOrderProcessing_Message_field" name="tgsmsnotify_setting_section[smsonOrderProcessingMessage]"> </textarea>';

        echo $html ;
    }

    function tgsmsnotify_sendSMS_onOrderCompleted_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsonOrderCompleted']) ) {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_onOrderCompleted_field" name="tgsmsnotify_setting_section[smsonOrderCompleted]" value="1"' . checked( 1, $options['smsonOrderCompleted'], false ) . ' />';
            $html .= '<label for="tgsmsnotify_sendSMS_onOrderCompleted_field">Notify Customer on Order Completed</label><br>';
        } else {
            $html = '<input type="checkbox" id="tgsmsnotify_sendSMS_onOrderCompleted_field" name="tgsmsnotify_setting_section[smsonOrderCompleted]" />';
            $html .= '<label for="tgsmsnotify_sendSMS_onOrderCompleted_field">Notify Customer on Order Completed</label><br>';            
        }

        echo $html ;
    }

    function tgsmsnotify_sendSMS_onOrderCompleted_Message_field_callback () {
        $options = get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $options['smsonOrderCompletedMessage'] ) )
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_onOrderCompleted_Message_field" name="tgsmsnotify_setting_section[smsonOrderCompletedMessage]">' . esc_textarea( $options['smsonOrderCompletedMessage'] ) . '</textarea>';
        else
            $html = '<textarea rows="4" cols="40" id="tgsmsnotify_sendSMS_onOrderCompleted_Message_field" name="tgsmsnotify_setting_section[smsonOrderCompletedMessage]"> </textarea>';

        echo $html ;
    }

    function tgsmsnotify_sanitize_twilio ( $input ) {                   // prfx_sanitize_options( $input ) {
        $options = get_option('tgsmsnotify_setting_section');
        
        $tginput = (array) get_option( 'tgsmsnotify_setting_section' );
                
         /* ob_start();                    // start buffer capture
        var_dump( $tginput );           // dump the values
        $contents = ob_get_contents(); // put the buffer into a variable
        ob_end_clean();                // end capture
        error_log($contents);   */

        if ( empty ($input['twilio_sid']) ) {
            if ( isset ( $options['twilio_sid'] ) )
                $tginput['twilio_sid'] = $options['twilio_sid'];
            else
                $tginput['twilio_sid'] = '';
        }

        if ( empty ($input['twilio_auth']) ) {
            if ( isset ( $options['twilio_auth'] ) )
                $tginput['twilio_auth'] = $options['twilio_auth'];
            else
                $tginput['twilio_auth'] = '';
        }

        if ( isset ( $input['twilio_from'] ) ) $tginput['twilio_from'] = intval ($input['twilio_from']); else error_log('From number UNSET');
        if ( isset ( $input['deleteTGSMSdata'] ) ) 
            $tginput['deleteTGSMSdata'] = ( intval( $input['deleteTGSMSdata'] ) == 1 ? 1 : 0 );
        else
            $tginput['deleteTGSMSdata'] = 0;

        return $tginput;
    }
        
    function tgsmsnotify_sanitize_manager ( $input ) {
        
        $tginput = (array) get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $input['manager_number'] ) ) $tginput['manager_number'] = intval ( $input['manager_number'] );
        if ( isset ( $input['smsMessage'] ) ) $tginput['smsMessage'] = esc_textarea( $input['smsMessage'] );

        if ( isset ( $input['toManager'] ) ) 
            $tginput['toManager'] = ( intval( $input['toManager'] ) == 1 ? 1 : 0 );
        else
            $tginput['toManager'] = 0;

        if ( isset ( $input['smsNewProduct'] ) ) 
            $tginput['smsNewProduct'] = ( intval( $input['smsNewProduct'] ) == 1 ? 1 : 0 );
        else
            $tginput['smsNewProduct'] = 0;

        if ( isset ( $input['smsNewProductMessage'] ) ) $tginput['smsNewProductMessage'] = esc_textarea( $input['smsNewProductMessage'] );
        
        if ( isset ( $input['smsPriceChange'] ) ) 
            $tginput['smsPriceChange'] = ( intval( $input['smsPriceChange'] ) == 1 ? 1 : 0 );
        else
            $tginput['smsPriceChange'] = 0;

        if ( isset ( $input['smsPriceChangeMessage'] ) ) $tginput['smsPriceChangeMessage'] = esc_textarea( $input['smsPriceChangeMessage'] );
        
        if ( isset ( $input['smsQtyAlert'] ) ) 
            $tginput['smsQtyAlert'] = ( intval( $input['smsQtyAlert'] ) == 1 ? 1 : 0 );
        else
        $tginput['smsQtyAlert'] = 0;

        if ( isset ( $input['smsQtyThreshold'] ) ) $tginput['smsQtyThreshold'] = intval( $input['smsQtyThreshold'] );

        return $tginput;
    }

    function print_errors(){
        settings_errors( 'unique_identifyer' );
    }

    function tgsmsnotify_sanitize_customer ( $input ) {

        $tginput = (array) get_option( 'tgsmsnotify_setting_section' );

        if ( isset ( $input['smsonOrderProcessing'] ) ) 
            $tginput['smsonOrderProcessing'] = ( intval( $input['smsonOrderProcessing'] ) == 1 ? 1 : 0 );
        else 
        $tginput['smsonOrderProcessing'] = 0;

        if ( isset ( $input['smsonOrderProcessingMessage'] ) ) $tginput['smsonOrderProcessingMessage'] = esc_textarea( $input['smsonOrderProcessingMessage'] );

        if ( isset ( $input['smsonOrderCompleted'] ) ) 
            $tginput['smsonOrderCompleted'] = ( intval( $input['smsonOrderCompleted'] ) == 1 ? 1 : 0 );
        else
            $tginput['smsonOrderCompleted'] = 0;
        
        if ( isset ( $input['smsonOrderCompletedMessage'] ) ) $tginput['smsonOrderCompletedMessage'] = esc_textarea( $input['smsonOrderCompletedMessage'] );

        return $tginput;
    }

    function tgsmsnotify_settings_page() {
        ?>

        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>TG SMS Notify Settings</h1>

            <?php //settings_errors(); ?>

            <?php

            $active_tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'sms-sender-settings';
            /*$active_tab = 'sms-sender-settings';
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'sms-sender-settings';
            } // end if
            */
            ?>

            <!-- wordpress provides the styling for tabs. -->
            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=tgsmsnotify-main-settings&tab=sms-sender-settings" class="nav-tab <?php echo $active_tab == 'sms-sender-settings' ? 'nav-tab-active' : ''; ?>">SMS Sender Settings</a>
                
                <a href="?page=tgsmsnotify-main-settings&tab=manager-options" class="nav-tab <?php echo $active_tab == 'manager-options' ? 'nav-tab-active' : ''; ?>">Manager Settings</a>
                
                <a href="?page=tgsmsnotify-main-settings&tab=customer-options" class="nav-tab <?php echo $active_tab == 'customer-options' ? 'nav-tab-active' : ''; ?>">Customer Settings</a>
            </h2>

            
            <form method="post" action="<?php echo esc_url( add_query_arg( 'tab', $active_tab, admin_url( 'options.php' ) ) ); ?>">
                
                <?php 
                
                if ( $active_tab == 'sms-sender-settings' ) {
                    error_log('1st tab values');
                    settings_fields( 'tgsmsnotify_twilio_setting_section' );
                    do_settings_sections( 'tgsmsnotify_twilio_settings_page' );
                } else if ( $active_tab == 'manager-options' ) {
                    error_log('end tab values');
                    settings_fields( 'tgsmsnotify_setting_section' );
                    do_settings_sections( 'tgsmsnotify_manager_settings_page' );
                } else if ( $active_tab == 'customer-options' ) {
                    error_log('3rd tab values');
                    settings_fields( 'tgsmsnotify_customer_setting_section' );
                    do_settings_sections( 'tgsmsnotify_customer_settings_page' );
                } 

                //settings_fields( 'tgsmsnotify_settings_page' );
                //do_settings_sections( 'tgsmsnotify_settings_page' );
                //echo '<input type="hidden" name="tab" value="' . esc_attr( $active_tab ) . '" />';
                submit_button();
                ?>
        
            </form>
        </div>
<?php
    }?>