<?php
$db_prefix = elgg_get_config('dbprefix');
$owner_name = get_input('page_type');
$base_url = get_input('base_url');
$owner = get_user_by_username($owner_name);
if (!$owner) {
        // unknown user so send away (@todo some sort of 404 error)
        exit();
}
elgg_set_context('friendsof');
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => TRUE,
	'base_url' => $base_url,
	'type' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
	'size' => 'medium',
	'order_by' => 'u.name',
	'full_view' => FALSE,
	'limit' => 20,
	'item_class' => 'friendsof',
);
if (! elgg_is_admin_logged_in()) {
        $options['wheres'] = array("(u.banned = 'no')");
}
$content = elgg_list_entities_from_relationship($options);
echo $content;
