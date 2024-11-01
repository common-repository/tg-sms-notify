<?php
    // if uninstall.php is not called by WordPress, die
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        die;
    }
    
    $options = get_option( 'tgsmsnotify_setting_section' );
    $uninstallFlag = intval ( $options['deleteTGSMSdata'] );

    if ( $uninstallFlag != 0 ) {
        $option_name = 'tgsmsnotify_setting_section';
        delete_option($option_name);
    }
?>
