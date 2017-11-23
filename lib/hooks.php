<?php
/**
 * Elgg Special Notifications plugin
 * @package special_notifications
 *
 * All hooks are here
 */

/**
 * Checking event: Check if user location is missing
 *
 * @param string      $hook         "special_notifications"
 * @param string      $type         "user"
 * @param string|null $return       list content (null if not set)
 * @param array       $params       array with key "options"
 * @return string
 * 
 * Trigger: elgg_trigger_plugin_hook('special_notifications', "user", [], false);
 */
function special_notification_profile_location_missing($hook, $type, $return, $params) {
    if ($type !== 'user') {
        return;
    }
    
    $user = elgg_get_logged_in_user_entity();
    if (!$user) {
        return;
    }
   
    $key = 'profile_location';
    $settings = elgg_trigger_plugin_hook('special_notifications:config', 'notify', ['notifier' => $key], []);
    if (!$settings[$key]['active']) {
        return;
    }

    // check the condition for notify the user
    $notify = false;
    if (!$user->location) {
        $notify = true;
//        $annotations = $user->getAnnotations('sn_profile_location', 1);
//        if (!$annotations) {
//            $notify = true;
//        }
//        else {
//            get annotation
//            if value=true {$notify = true;}
//            else {$notify = false;}
//        }
    }

    if ($notify) {

        $methods = $settings[$key]['methods'];
        foreach ($methods as $m) {
            switch ($m) {
                case SpecialNotificationsOptions::SN_METHOD_INLINE:
                    $close_btn = elgg_format_element('a', ['class' => 'close', 'data-dismiss' => 'alert', 'aria-label' => 'close'], '&times;');
                    $inline = elgg_view('special_notifications/inline',[
                        'content' => $close_btn.elgg_echo('special_notifications:profile_location:message', [elgg_normalize_url("profile/{$user->username}/edit")]),
                        'class' => 'alert alert-warning fade in',
                    ]);
                    break;
                
                case SpecialNotificationsOptions::SN_METHOD_ERROR:
                    register_error(elgg_echo('special_notifications:profile_location:message'));
                    break;
                
                case SpecialNotificationsOptions::SN_METHOD_NOTIFICATION:
                    // do nothing at the moment
                    break;
            }
            
        }
        
        if ($inline) {
            return $inline;
        }
    }
    
    return;
}
 

/**
 * Register a checking event for missing location on user's profile
 *
 * @param string $hook        "special_notifications:config"
 * @param string $type        "notify"
 * @param array  $return      array of checking event settings for profile_location
 * @param array  $params      if not specified, return all. Or return if $params['notifier'] is equal to key
 * @return array
 */
function snotify_profile_location($hook, $type, $return, $params) {
    
    $key = 'profile_location';
    if (!$params || (is_array($params) && $params['notifier'] == $key)) {
        $return[$key] = [
            'active' => true, 
            'hook' => 'profile_location_missing', 
            'annotation' => 'sn_profile_location', // true: notify, false: not notify
            'methods' => [SpecialNotificationsOptions::SN_METHOD_INLINE], 
        ];
    }
    return $return;
}
