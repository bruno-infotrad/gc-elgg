<?php
if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$db_prefix = elgg_get_config("dbprefix");
$content = elgg_list_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_page_owner_guid(),
	'inverse_relationship' => false,
	'full_view' => false,
	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name asc',

));
if (!$content) {
	$content = elgg_echo('groups:none');
}
echo $content;
