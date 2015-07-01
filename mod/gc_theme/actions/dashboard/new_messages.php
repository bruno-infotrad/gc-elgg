<?php
/**
 * Action called by AJAX periodically to update message count in title
 *
 * @package gc_theme
 */

if (elgg_is_logged_in()) {
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
	$num_messages = $num_personal_messages + $num_notifications;
	if ($num_messages != 0) {
		$json = array('success' => TRUE, 'message_count' => $num_messages);
	} else {
        	$json = array('success' => TRUE, 'message_count' => 0);
	}
	echo json_encode($json);
}
// For no forwarding?
exit;
