<?php
/**
 * User blog module
 */

$user_guid = elgg_get_logged_in_user_guid();
if (!$user_guid) {
        // unknown user so send away (@todo some sort of 404 error)
        forward();
}
$user = get_entity($user_guid);
$target_user_guid = elgg_get_page_owner_guid();
$target_user = get_entity($target_user_guid);

$all_link = elgg_view('output/url', array(
        'href' => "friends/".$target_user->username,
        'text' => elgg_echo('link:view:all'),
        'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
        'relationship' => 'friend',
        'relationship_guid' => $target_user_guid,
        'inverse_relationship' => FALSE,
        'type' => 'user',
        'full_view' => FALSE
);
$content = elgg_list_entities_from_relationship($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('friends:none') . '</p>';
}

if ($user != $target_user) {

	$title = elgg_echo('friends:owned', array($target_user->name));
} else {
	$title = elgg_echo('friends:mine');
}

echo elgg_view('profile/module', array(
	'title' => $title,
	'content' => $content,
	'all_link' => $all_link,
));
