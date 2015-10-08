<?php
/**
 * Elgg friends page
 *
 * @package Elgg.Core
 * @subpackage Social.Friends
 */

$db_prefix = elgg_get_config('dbprefix');
$owner = elgg_get_page_owner_entity();
if (!$owner) {
	// unknown user so send away (@todo some sort of 404 error)
	forward();
}

$title = elgg_echo("friends:owned", array($owner->name));
//elgg_register_title_button('friends', 'multi_invite');
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => FALSE,
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

$content = elgg_list_entities_from_relationship($options);

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('friends/sidebar'),
	'title' => $title,
	'filter_override' => elgg_view('members/nav', array('selected' => 'friends')),
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
