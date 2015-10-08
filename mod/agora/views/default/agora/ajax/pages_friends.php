<?php
$user = elgg_get_logged_in_user_entity();
$offset = get_input("offset");
$base_url = get_input('base_url');
$content = elgg_list_entities_from_relationship(array(
	'base_url' => $base_url,
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
