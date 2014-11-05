<?php
// Load Elgg framework
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/engine/start.php');

// Ensure only logged-in users can see this page
gatekeeper();

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$user = elgg_get_page_owner_entity();

// Set the context to settings
elgg_set_context('settings');

$title = elgg_echo('notifications:subscriptions:changesettings:groups');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
elgg_push_breadcrumb($title);

// Get the form
$people = array();

$groupmemberships = elgg_get_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'types' => 'group',
	'limit' => 9999,
));

$content = elgg_view_form('notificationsettings/groupsave', array(), array('groups' => $groupmemberships));

$params = array(
        'content' => $content,
        //'sidebar' => elgg_view('friends/sidebar'),
        'title' => $title,
        'filter_override' => elgg_view('settings/nav', array('selected' => 'group_notifications')),
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
