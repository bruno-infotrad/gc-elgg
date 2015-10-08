<?php
$offset = get_input('offset');
$base_url = get_input('base_url');
$content = elgg_list_entities(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'page_top',
	'full_view' => false,
	'offset' => $offset,
));
if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}
echo $content;
