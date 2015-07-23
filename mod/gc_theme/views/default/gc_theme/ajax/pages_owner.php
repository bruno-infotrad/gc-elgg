<?php
$group_guid = get_input('group_guid',0);
$offset = get_input('offset');
$base_url = get_input('base_url');
if ($group_guid) {
	$group = get_entity($group_guid);
	if (!$group || !elgg_instanceof($group, 'group')) {
        	register_error(elgg_echo('groups:notfound'));
        	exit();
	}
	$container_guid = $group_guid;
} else {
	$username = get_input('owner');
	$container_guid = get_user_by_username($username)->getGUID();
}
$content = elgg_list_entities(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'page_top',
	'container_guid' => $container_guid,
	'offset' => $offset,
	'full_view' => false,
));
if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}
echo $content;
