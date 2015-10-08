<?php
$user_preferred_tab = elgg_get_logged_in_user_entity()->preferred_tab;
$preferred_tab = ($user_preferred_tab)?$user_preferred_tab:'all';
$title = elgg_echo('agora:userpreferences:ui');
$content = elgg_echo('agora:preferred_tab').': ';
$content .= elgg_view('input/dropdown', array(
	'name' => 'preferred_tab',
	'id' => 'preferred-tab',
	'value' => $preferred_tab,
	'options_values' => array(
		'friends' => elgg_echo('access:friends:label'),
		'my_groups' => elgg_echo('groups:yours'),
		'groups' => elgg_echo('groups'),
		'all' => elgg_echo('all'),
	)
));
$user_event_preferred_tab = elgg_get_logged_in_user_entity()->event_preferred_tab;
$event_preferred_tab = ($user_event_preferred_tab)?$user_event_preferred_tab:'all';
$content .= '<br>'.elgg_echo('agora:event_preferred_tab').': ';
$content .= elgg_view('input/dropdown', array(
	'name' => 'event_preferred_tab',
	'id' => 'event-preferred-tab',
	'value' => $event_preferred_tab,
	'options_values' => array(
		'all' => elgg_echo('event_manager:list:navigation:list'),
		'attending' => elgg_echo('event_manager:event:relationship:event_attending'),
		'mine' => elgg_echo('event_manager:event:relationship:my_events'),
	)
));
echo elgg_view_module('info', $title, $content);
