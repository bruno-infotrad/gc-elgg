<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

$user = elgg_get_logged_in_user_entity();
// offset is grabbed in list_user_friends_objects
$offset = get_input("offset");

$content = list_user_friends_objects($user->guid, 'file', 10, false);
if (!$content) {
	$content = elgg_echo("file:none");
}
echo $content;
