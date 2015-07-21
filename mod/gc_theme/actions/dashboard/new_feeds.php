<?php
/**
 * Action called by AJAX periodically to check for new post on dashboard
 *
 * @package gc_theme
 */

$user = elgg_get_logged_in_user_entity();
$db_prefix = elgg_get_config('dbprefix');
$js_polling_control = elgg_get_plugin_setting('js_polling_control', 'gc_theme');
if ($user->feed_viewed) {
	if (sizeof($user->feed_viewed) > 1) {
		$user->feed_viewed=$user->feed_viewed['0'];
	}
	$feed_last_viewed = $user->feed_viewed;
}
if ($feed_last_viewed) {
	$query = "SELECT COUNT(*) as total from {$db_prefix}river rv where rv.posted > $feed_last_viewed  AND rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'";
	$new_feeds = get_data_row($query);
	$json = array('success' => TRUE, 'count' => $new_feeds->total, 'js_polling_control' => $js_polling_control);
} else {
	$json = array('success' => TRUE, 'count' => 0, 'js_polling_control' => $js_polling_control);
}
echo json_encode($json);

// For no forwarding?
exit;
