<?php
$user_preferred_tab = elgg_get_logged_in_user_entity()->preferred_tab;
$preferred_tab = ($user_preferred_tab)?$user_preferred_tab:'all';
$title = elgg_echo('gc_theme:userpreferences:ui');
$preferred_tab = elgg_view('input/dropdown', array(
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
$content = elgg_echo('gc_theme:preferred_tab').': ';
$content .= $preferred_tab;

echo elgg_view_module('info', $title, $content);
