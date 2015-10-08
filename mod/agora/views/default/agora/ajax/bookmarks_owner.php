<?php
/**
 * Individual's or group's bookmarks
 *
 */
// access check for closed groups
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);
$base_url = get_input('base_url');
if (!$group || !elgg_instanceof($group, 'group')) {
        register_error(elgg_echo('groups:notfound'));
        exit();
}
if (group_gatekeeper(false)) {
	$offset = get_input('offset');
	$content = elgg_list_entities(array(
		'base_url' => $base_url,
		'types' => 'object',
		'subtypes' => 'bookmarks',
		'container_guid' => $group_guid,
		'limit' => 10,
		'offset' => $offset,
		'full_view' => FALSE,
	));
	if (!$content) {
		$content = elgg_echo("bookmark:none");
	}
echo $content;
}
