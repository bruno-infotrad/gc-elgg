<?php
elgg_load_js('elgg.contribute_to');
$db_prefix = elgg_get_config("dbprefix");
$content = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => false,
	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name asc',
	'limit' => 0,
	));
if (!$content) {
	$content = elgg_echo('groups:none');
}
echo elgg_view_form('gc_theme/contribute_to', array(), array('group_list' => $content));
