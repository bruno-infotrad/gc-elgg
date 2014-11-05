<?php
/**
 * Delete message
 */

$guid = (int) get_input('guid');
$username = elgg_get_logged_in_user_entity()->username;
$user_guid = elgg_get_logged_in_user_entity()->getGUID();

$message = get_entity($guid);
$message_toid = $message->toId;
if (!$message || !$message->canEdit()) {
	register_error(elgg_echo('messages:error:delete:single'));
	forward(REFERER);
}

if (!$message->delete()) {
	register_error(elgg_echo('messages:error:delete:single'));
} else {
	system_message(elgg_echo('messages:success:delete:single'));
}
if (preg_match ('/\/send\//',$_SERVER['HTTP_REFERER'])|| $message_toid != $user_guid) {
	forward("messages/sent/$username");
} else {
	forward("messages/inbox/$username");
}
