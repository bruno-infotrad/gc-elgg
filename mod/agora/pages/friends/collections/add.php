<?php
// You need to be logged in for this one
gatekeeper();

//$title = elgg_echo('friends:collections:add');

$content = elgg_view_title($title);

$content .= elgg_view_form('friends/collections/add', array(), array(
	'friends' => elgg_get_logged_in_user_entity()->getFriends(array('limit' => 0)),
));

$params = array(
        'content' => $content,
        'sidebar' => elgg_view('members/sidebar'),
        'title' => $title,
        'filter_override' => elgg_view('members/nav', array('selected' => 'collections')),
);      
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);
