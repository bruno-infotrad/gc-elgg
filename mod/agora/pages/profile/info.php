<?php
elgg_load_js('elgg.gc_comments');
elgg_load_js('elgg.gc_wire');
elgg_load_js('elgg.gc_gft');
elgg_load_js('elgg.discussion');
elgg_load_js('elgg.gc_discussion');

$user = elgg_get_page_owner_entity();

$content = elgg_view('profile/layout', array('entity' => $user, 'group_members' => $sidebar));

$body = elgg_view_layout('one_sidebar', array(
	'content' => $content,
	'title' => elgg_echo('profile:info'),
	//'title' => $user->name,
));
echo elgg_view_page($group->name, $body);
