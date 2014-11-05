<?php
elgg_load_js('elgg.contribute_to');
$content = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => false,
	'limit' => 0,
	));
if (!$content) {
	$content = elgg_echo('groups:none');
}
echo elgg_view_form('gc_theme/contribute_to', array(), array('group_list' => $content));
