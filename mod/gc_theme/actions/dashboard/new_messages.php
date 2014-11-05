<?php
/**
 * Action called by AJAX periodically to update message count in title
 *
 * @package gc_theme
 */

if (elgg_is_logged_in()) {
	$num_messages = (int)messages_count_unread();
	if ($num_messages != 0) {
		$json = array('success' => TRUE, 'message_count' => $num_messages);
	} else {
        	$json = array('success' => TRUE, 'message_count' => 0);
	}
	echo json_encode($json);
}
// For no forwarding?
exit;
