<?php
/**
 * Elgg friends page
 *
 * @package Elgg.Core
 * @subpackage Social.Friends
 */

$db_prefix = elgg_get_config('dbprefix');
$owner_name = get_input('page_type');
$count = get_input('count',false);
$base_url = get_input('base_url');
$owner = get_user_by_username($owner_name);
if (!$owner) {
	// unknown user so send away (@todo some sort of 404 error)
	exit();
}
if ($count == true) {
	$options = array(
		'relationship' => 'friend',
		'relationship_guid' => $owner->getGUID(),
		'inverse_relationship' => FALSE,
		'base_url' => $base_url,
		'type' => 'user',
		'limit' => '20',
		'count' => 'true',
		'full_view' => FALSE
	);
} else {
	$options = array(
		'relationship' => 'friend',
		'relationship_guid' => $owner->getGUID(),
		'inverse_relationship' => FALSE,
		'base_url' => $base_url,
		'type' => 'user',
		'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
		'limit' => '20',
		'order_by' => 'u.name',
		'list_type' => 'gallery',
		'gallery_class' => 'elgg-gallery-friends',
		'size' => 'medium',
		'full_view' => FALSE
	);
	if (! elgg_is_admin_logged_in()) {
	        $options['wheres'] = array("(u.banned = 'no')");
	}
}
$content = elgg_list_entities_from_relationship($options);
echo $content;
