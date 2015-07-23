<?php
$db_prefix = elgg_get_config("dbprefix");
$group_guid = get_input('group_guid');
$offset = get_input('offset');
$base_url = get_input('base_url');
elgg_set_page_owner_guid($group_guid);
$options = array(
        'relationship' => 'member',
        'relationship_guid' => $group_guid,
        'inverse_relationship' => true,
	'base_url' => $base_url,
        'types' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
        'order_by' => 'u.name',
        'limit' => 20,
        'offset' => $offset,
        'list_type' => 'gallery',
        'size' => 'medium',
        'gallery_class' => 'elgg-gallery-users',
);
if (! elgg_is_admin_logged_in()) {
        $options['wheres'] = array("(u.banned = 'no')");
}
$members = elgg_list_entities_from_relationship($options);
echo $members;
