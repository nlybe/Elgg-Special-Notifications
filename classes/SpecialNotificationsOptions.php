<?php
/**
 * Elgg Special Notifications plugin
 * @package special_notifications
 */

class SpecialNotificationsOptions {

    const PLUGIN_ID = 'special_notifications';    // current plugin ID
    const SN_METHOD_INLINE = 'inline';      // display a warning message inside a div element
    const SN_METHOD_ERROR = 'elgg_error';   // display a standard Elgg error using the register_error function
    const SN_METHOD_NOTIFICATION = 'elgg_notification';     // not implemented yet: send an Elgg notification to the user by using the notify_user function
}
