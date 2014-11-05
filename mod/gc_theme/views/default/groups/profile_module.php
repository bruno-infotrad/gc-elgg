<?php
/**
 * User blog module
 */

$user = get_entity(elgg_get_logged_in_user_guid());
$target_user_guid = elgg_get_page_owner_guid();
$target_user = get_entity($target_user_guid);

$all_link = elgg_view('output/url', array(
        'href' => "groups/all",
        'text' => elgg_echo('link:view:all'),
        'is_trusted' => true,
));

elgg_push_context('widgets');
$content = elgg_list_entities_from_relationship_count(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $target_user_guid,
	'inverse_relationship' => false,
	'full_view' => false,
));

elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('groups:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "groups/add",
	'text' => elgg_echo('groups:add'),
	'is_trusted' => true,
));

if ($user != $target_user) {

	$title = elgg_echo('groups:user', array($target_user->name));
} else {
	$title = elgg_echo('groups:yours');
}

echo elgg_view('profile/module', array(
	'title' => $title,
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
