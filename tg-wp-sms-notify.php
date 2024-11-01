<?php
    /**
     * Plugin Name:       TG SMS Notify
     * Plugin URI:        https://karmadaily.com/plugins/wp-sms-notify/
     * Description:       Send simple SMS
     * Version:           2.0
     * Author:            Tushar Goel
     * Author URI:        https://tushargoel.com/
     */

    if ( !function_exists( 'add_action' ) ) {
        echo 'Please do not call me directly!';
        exit;
    }

    define( 'TGSMSNOTIFY__PLUGIN_SETTINGS', plugin_dir_path( __FILE__ ) );
    define( 'TGSMSNOTIFY__PLUGIN_NOTIFYCORE', plugin_dir_path( __FILE__ ) );
    define( 'TGSMSNOTIFY__PLUGIN_NOTIFYCOREWOOCOMMERCE', plugin_dir_path( __FILE__ ) );
    define( 'TGSMSNOTIFY__PLUGIN_NOTIFYWCCUSTOMERS', plugin_dir_path( __FILE__ ) );

    require_once( TGSMSNOTIFY__PLUGIN_SETTINGS . './includes/settings.php' );
    require_once( TGSMSNOTIFY__PLUGIN_NOTIFYCORE . './includes/notifycore.php' );
    require_once( TGSMSNOTIFY__PLUGIN_NOTIFYCOREWOOCOMMERCE . './includes/notifywoocommerce.php' );
    require_once( TGSMSNOTIFY__PLUGIN_NOTIFYWCCUSTOMERS . './includes/notifywccustomers.php' );

    function tgsmsnotify_my_plugin_activate() {

        add_option( 'Activated_Plugin', 'tg-sms-notify' );

    }
    register_activation_hook( __FILE__, 'tgsmsnotify_my_plugin_activate' );
      
    function tgsmsnotify_load_plugin() {
    
        if ( is_admin() && get_option( 'Activated_Plugin' ) == 'tg-sms-notify' ) {
    
            delete_option( 'Activated_Plugin' );

        }
    }

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tgsmsnotify_add_action_links' );
    
    function tgsmsnotify_add_action_links ( $links ) {

        $tgsmsnotify_links = array(
            '<a href="' . admin_url( 'options-general.php?page=tgsmsnotify-main-settings' ) . '">Settings</a>',
        );

        return array_merge( $tgsmsnotify_links, $links );
    }
?>