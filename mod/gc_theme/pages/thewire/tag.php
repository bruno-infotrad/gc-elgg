<?php
/**
 * Wire posts tagged with <tag>
 */

$tag = get_input('tag');
if (!$tag) {
	forward('thewire/all');
}

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb('#' . $tag);

// remove # from tag
$tag = trim($tag, '# ');

$title = elgg_echo('gc_theme:thewire:tags', array($tag));


$content = elgg_list_entities_from_metadata(array(
	'metadata_name' => 'tags',
	'metadata_value' => $tag,
	'metadata_case_sensitive' => false,
	'type' => 'object',
	'limit' => 20,
));

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar'),
));

echo elgg_view_page($title, $body);
