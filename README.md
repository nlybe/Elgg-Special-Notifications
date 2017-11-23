Special Notifications plugin for Elgg
=====================================
![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

Extended notifications functionality on certain events for Elgg communites.

This plugin can be used from developers for sending notifications to users automatically based on certain conditions, if these conditions are not being satisfied.

For example if a specific profile field is missing for current user, then this user should be notified.

## Features
- Option to register more events/conditions via hook
- Different notifications methods available

## How to use

1. Register hook for a new checking event with required options. Example:
```
elgg_register_plugin_hook_handler('special_notifications:config', 'notify', "snotify_profile_location");

function snotify_profile_location($hook, $type, $return, $params) {
    
    $key = 'profile_location';
    if (!$params || (is_array($params) && $params['notifier'] == $key)) {
        $return[$key] = [
            'active' => true, 
            'hook' => 'profile_location_missing', 
            'methods' => [SpecialNotificationsOptions::SN_METHOD_INLINE], 
        ];
    }
    return $return;
}
```

2. Register a hook for checking if certain criteria are satisfied or not. If not then one or more notifications should be send to user. Example:
```
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
    }

    if ($notify) {
        $methods = $settings[$key]['methods'];
        foreach ($methods as $m) {
            switch ($m) {
                case "inline":
                    $close_btn = elgg_format_element('a', ['class' => 'close', 'data-dismiss' => 'alert', 'aria-label' => 'close'], '&times;');
                    $inline = elgg_view('special_notifications/inline',[
                        'content' => $close_btn.elgg_echo('special_notifications:profile_location:message'),
                        'class' => 'alert alert-warning fade in',
                    ]);
                    break;
                
                case "elgg_error":
                    register_error(elgg_echo('special_notifications:profile_location:message'));
                    break;
            }
            
        }
        
        if ($inline) {
            return $inline;
        }
    }
    
    return;
}
```

3. Trigger the hook anywhere on the site or after a specific event, e.g. after login. The example below can be inserted on profile page and display a warning:
```
if (elgg_is_active_plugin('special_notifications') && elgg_get_logged_in_user_guid()==$user->getGUID()) {
    if ($notifications = elgg_trigger_plugin_hook('special_notifications', "user", [], false)) {
        $content = elgg_format_element('div', ['class' => 'col-md-12 col-sm-12 col-xs-12'], $notifications);
    }
}
...
echo content;
...
```

As an example, a checking event is available on this plugin: Check if user has entered location on profile. If location is empty, then notify the user.

## Future Improvements
- Implement the 'elgg_notification' method: use the standard Elgg notification method (notify_user)
- Use annotation for saving user interaction. For example user should be able to select not to be notified again or to be notified after certain time.
- Add more native checking event for user notifications