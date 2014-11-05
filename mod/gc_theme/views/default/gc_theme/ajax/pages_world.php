<?php
$offset = get_input('offset');
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'page_top',
	'full_view' => false,
	'offset' => $offset,
));
if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}
echo $content;
