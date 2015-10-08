<?php
/**
 * User blog module
 */

$user = get_entity(elgg_get_logged_in_user_guid());
$target_user_guid = elgg_get_page_owner_guid();
$target_user = get_entity($target_user_guid);


$all_link = elgg_view('output/url', array(
        'href' => "thewire/owner/$target_user->username",
        'text' => elgg_echo('link:view:all'),
        'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'thewire',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('wire:none') . '</p>';
}

if ($user != $target_user) {
        $title = elgg_echo('thewire:user', array($target_user->name));
	$new_link = "";
} else {
        $title = elgg_echo('thewire:mine');
	$new_link = elgg_view('output/url', array(
		'href' => "thewire/owner/$user->name",
		'text' => elgg_echo('thewire:write'),
		'is_trusted' => true,
	));

}

echo elgg_view('profile/module', array(
	'title' => $title,
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
