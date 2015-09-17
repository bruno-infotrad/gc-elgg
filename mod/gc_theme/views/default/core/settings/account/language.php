<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if ($user) {
	$title = elgg_echo('user:set:language');
	$content = elgg_echo('user:language:label') . ': ';
	$content .= elgg_view("input/select", array(
		'name' => 'language',
		'value' => $user->language,
		'options_values' => array('en'=> elgg_echo('en'),'fr'=> elgg_echo('fr')),
	));
	echo elgg_view_module('info', $title, $content);
}
