<?php
/**
 * All files
 *
 * @package ElggFile
 */
$limit = get_input("limit", 12);
$offset = get_input("offset");
$base_url = get_input('base_url');
$content = elgg_list_entities(array(
	'base_url' => $base_url,
	'types' => 'object',
	'subtypes' => 'file',
	'limit' => $limit,
	'list_type' => 'gallery',
	'full_view' => FALSE,
	'offset' => $offset,
));
if (!$content) {
	$content = elgg_echo('file:none');
}
$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo $content;
