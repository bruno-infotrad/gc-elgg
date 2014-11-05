<?php
if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$owner = elgg_get_page_owner_entity();

$db_prefix = elgg_get_config('dbprefix');
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => FALSE,
	'type' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
	'limit' => '20',
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
