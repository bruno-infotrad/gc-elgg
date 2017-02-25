<?php
$page_type = get_input('page_type');
$offset = get_input('offset');
$container_guid = get_input('group_guid');
$base_url = get_input('base_url');
elgg_group_gatekeeper();
$group = get_entity($container_guid);
if (!elgg_instanceof($group, 'group')) {
	forward('', '404');
}
$options = array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'groupforumtopic',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => 'e.last_action desc',
	'container_guid' => $container_guid,
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
);
$content = elgg_list_entities($options);
echo $content;
