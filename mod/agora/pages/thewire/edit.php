<?php
/**
 * Reply page
 * 
 */

gatekeeper();

$guid = get_input('guid');
$thewire = get_entity($guid);
$container_guid = $thewire->getContainerGUID();

$title = elgg_echo('thewire:reply');

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($title);

$form_vars = array('class' => 'thewire-form');
$content = elgg_view_form('compound/add', $form_vars, array('guid' => $guid,'container_guid' => $container_guid, 'thewire' => $thewire));

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
