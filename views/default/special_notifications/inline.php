<?php
/**
 * Elgg Special Notifications plugin
 * @package special_notifications
 */

$content = elgg_extract('content', $vars, '');

if ($content) {
    $params = [];
    if ($class = elgg_extract('class', $vars, '')) {
        $params['class'] = $class;
    }

    echo elgg_format_element('div', $params, $content);
}