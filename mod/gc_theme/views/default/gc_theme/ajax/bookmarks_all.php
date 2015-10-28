<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package ElggBookmarks
 */
$limit = get_input("limit", 10);
$offset = get_input("offset");
$base_url = get_input('base_url');
$content = elgg_list_entities(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'view_toggle_type' => false,
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
	'distinct' => false,
));
echo $content;
