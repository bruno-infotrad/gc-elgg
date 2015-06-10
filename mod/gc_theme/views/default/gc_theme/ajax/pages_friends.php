<?php
$user = elgg_get_logged_in_user_entity();
$offset = get_input("offset");

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'page_top',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $user->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
	'preload_containers' => true,
	'limit' => 10,
));
if (!$content) {
	$content = elgg_echo('pages:none');
}
echo $content;
