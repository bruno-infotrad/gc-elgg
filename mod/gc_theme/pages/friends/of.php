<?php
/**
 * Elgg friends of page
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
elgg_set_context('friendsof');
$title = elgg_echo("friends:of:owned", array($owner->name));

$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => TRUE,
	'type' => 'user',
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
	'size' => 'medium',
	'order_by' => 'u.name',
	'full_view' => FALSE,
	'limit' => 20,
	'list_class' => 'elgg-list elgg-list-entity elgg-list-friendsof',
	'item_class' => 'friendsof',
);
if (! elgg_is_admin_logged_in()) {
        $options['wheres'] = array("(u.banned = 'no')");
}

$content = elgg_list_entities_from_relationship($options);

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
        'filter_override' => elgg_view('members/nav', array('selected' => 'friendsof')),

);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
