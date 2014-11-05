<?php
/**
 * Members navigation
 */

$user = get_entity(elgg_get_logged_in_user_guid());
$tabs = array(
	'settings' => array(
		'title' => elgg_echo('gc_theme:userpreferences'),
		'url' => "settings/user/".$user->username,
		'selected' => $vars['selected'] == 'settings',
	),
	'notifications' => array(
		'title' => elgg_echo('notifications:personal'),
		'url' => "notifications/personal",
		'selected' => $vars['selected'] == 'notifications',
	),
	'group_notifications' => array(
		'title' => elgg_echo('notifications:subscriptions:changesettings:groups'),
		'url' => "notifications/group",
		'selected' => $vars['selected'] == 'group_notifications',
	),
	'statistics' => array(
		'title' => elgg_echo('usersettings:statistics:opt:linktext'),
		'url' => "settings/statistics",
		'selected' => $vars['selected'] == 'statistics',
	),
);
//echo '<div class="elgg-head">'.$add_button.'<h1>'.elgg_echo('gc_theme:people').'</h1></div>';
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
