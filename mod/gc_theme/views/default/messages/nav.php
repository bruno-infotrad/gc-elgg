<?php
/**
 * Members navigation
 */

$user = elgg_get_logged_in_user_entity();
$num_personal_messages = (int)messages_count_unread();
$num_notifications = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $user->guid,
	'metadata_name' => 'read',
	'metadata_value' => false,
	'count' => true,
	'limit' => 0,
));
if ($num_personal_messages) {
	$html_npm = '<div class="gc-messages">'.$num_personal_messages.'</div>';
}
if ($num_notifications) {
	$html_nn = '<div class="gc-messages">'.$num_notifications.'</div>';
}
$tabs = array(
	'messages' => array(
		'title' => elgg_echo('messages').$html_npm,
		'url' => "messages/inbox/".$user->username,
		'selected' => $vars['selected'] == 'messages',
	),
	'notifications' => array(
		'title' => elgg_echo('notifications:personal').$html_nn,
		'url' => 'site_notifications/view/'.$user->username,
		'selected' => $vars['selected'] == 'notifications',
	),
);
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
