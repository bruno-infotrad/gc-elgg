<?php
$db_prefix = elgg_get_config("dbprefix");
$container_guid = get_input('container_guid');
$base_url = get_input('base_url');
elgg_set_page_owner_guid($vars['container_guid']);
$offset = get_input('offset');
$content = elgg_list_entities_from_relationship(array(
	'base_url' => $base_url,
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_page_owner_guid(),
	'inverse_relationship' => false,
	'full_view' => false,
	'offset' => $offset,
	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name asc',
));
if (!$content) {
	$content = elgg_echo('groups:none');
}
echo $content;
