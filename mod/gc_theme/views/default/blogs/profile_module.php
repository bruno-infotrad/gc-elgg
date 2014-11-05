<?php
/**
 * User blog module
 */

$user = get_entity(elgg_get_logged_in_user_guid());
$target_user_guid = elgg_get_page_owner_guid();
$target_user = get_entity($target_user_guid);
elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('blog:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "blog/add/$user->guid",
	'text' => elgg_echo('blog:write'),
	'is_trusted' => true,
));

if ($user != $target_user) {

        $title = elgg_echo('blogs:user', array($target_user->name));
	$all_link = elgg_view('output/url', array(
			'href' => "blog/owner/$target_user->username",
			'text' => elgg_echo('link:view:all'),
			'is_trusted' => true,
		));

} else {
        $title = elgg_echo('blogs:mine');
	$all_link = elgg_view('output/url', array(
			'href' => "blog/owner/$user->username",
			'text' => elgg_echo('link:view:all'),
			'is_trusted' => true,
		));
}

echo elgg_view('profile/module', array(
	'title' => $title,
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
