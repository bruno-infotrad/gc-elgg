<?php
$user = elgg_get_logged_in_user_entity();
$offset = get_input("offset");

$content = list_user_friends_objects($user->guid, 'page_top', 10, false);
if (!$content) {
	$content = elgg_echo('pages:none');
}
echo $content;
