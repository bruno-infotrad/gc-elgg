<?php
/**
 * Elgg bookmarks plugin friends page
 *
 * @package ElggBookmarks
 */

$limit = get_input("limit", 10);
$offset = get_input("offset");
$base_url = get_input('base_url');
$page_owner = elgg_get_logged_in_user_entity();
$content = elgg_list_entities_from_relationship(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $page_owner->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
));
echo $content;
