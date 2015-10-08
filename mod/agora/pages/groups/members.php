<?php
$db_prefix = elgg_get_config("dbprefix");
$group = elgg_get_page_owner_entity();
$title = $group->name.' '.elgg_echo('groups:members');
elgg_set_context('group_members');

$options = array(
	'relationship' => 'member',
	'relationship_guid' => $group->guid,
	'inverse_relationship' => true,
	'types' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
	'order_by' => 'u.name',
	'limit' => 20,
	'list_type' => 'gallery',
	'size' => 'medium',
	'gallery_class' => 'elgg-gallery-users',
);
if (! elgg_is_admin_logged_in()) {
	$options['wheres'] = array("(u.banned = 'no')");
}

$members = elgg_list_entities_from_relationship($options);
$admins = elgg_view('page/elements/group_admins',array('group' => $group));

$body = elgg_view_layout('content', array(
        //'filter_context' => 'mine',
        'filter' => '',
        'content' => $members,
        'title' => $title,
        'sidebar' => $admins
));

echo elgg_view_page($group->name, $body);
