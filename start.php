<?php
/**
 * Elgg Special Notifications plugin
 * @package special_notifications
 */
 
require_once(dirname(__FILE__) . '/lib/hooks.php');

elgg_register_event_handler('init', 'system', 'special_notifications_init');
 
/**
 * special_notifications plugin initialization functions.
 */
function special_notifications_init() {
 	
    // register extra css
    elgg_extend_view('elgg.css', 'special_notifications/special_notifications.css');

    // register hook for checking event which are available this plugin
    $sn = array('profile_location');
    foreach ($sn as $item) {
        elgg_register_plugin_hook_handler('special_notifications:config', 'notify', "snotify_$item");
    }
    
    // get available active checking event and register a hook, so they can be triggered
    $special_notifications = elgg_trigger_plugin_hook('special_notifications:config', 'notify', null, []);
    foreach ($special_notifications as $key => $sn) {
        if ($sn['active']) {
            elgg_register_plugin_hook_handler('special_notifications', 'user', "special_notification_".$sn['hook']);
        }
    }

}

?>
