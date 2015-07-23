<?php
$db_prefix = elgg_get_config('dbprefix');
$container_guid = get_input('container_guid');
$offset = get_input('offset');
$base_url = get_input('base_url');
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $container_guid,
	'inverse_relationship' => FALSE,
	'base_url' => $base_url,
	'type' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
	'limit' => '20',
	'offset' => $offset,
	'order_by' => 'u.name',
	'list_type' => 'gallery',
	'size' => 'medium',
	'full_view' => FALSE
);
if (! elgg_is_admin_logged_in()) {
        $options['wheres'] = array("(u.banned = 'no')");
}
$content = elgg_list_entities_from_relationship($options);
echo $content;
