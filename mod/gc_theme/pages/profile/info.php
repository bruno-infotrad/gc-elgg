<?php
$user = elgg_get_page_owner_entity();

$content = elgg_view('profile/layout', array('entity' => $user, 'group_members' => $sidebar));

$body = elgg_view_layout('one_sidebar', array(
	'content' => $content,
	'title' => elgg_echo('profile:info'),
	//'title' => $user->name,
));
echo elgg_view_page($group->name, $body);
