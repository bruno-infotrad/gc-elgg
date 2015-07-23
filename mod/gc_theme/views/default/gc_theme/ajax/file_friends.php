<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

$user = elgg_get_logged_in_user_entity();
// offset is grabbed in list_user_friends_objects
$offset = get_input("offset");
$base_url = get_input('base_url');
$content = elgg_list_entities_from_relationship(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'file',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo("file:none"),
	'preload_owners' => true,
	'preload_containers' => true,
	'limit' => 10,
));
if (!$content) {
	$content = elgg_echo("file:none");
}
echo $content;
