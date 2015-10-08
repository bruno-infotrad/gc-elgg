<?php
/**
* Invite multiple friends at once
*/
gatekeeper();
$user = elgg_get_logged_in_user_entity();

$content = elgg_view_form("friends/multi_invite", array(
		"id" => "multi_invite",
		"class" => "elgg-form-alt mtm",
		"enctype" => "multipart/form-data"
	), array(
		"entity" => $user,
	));
	
$params = array(
	"content" => $content,
	"title" => elgg_echo('friends:multi_invite'),
	"filter" => "",
);
$body = elgg_view_layout("content", $params);

echo elgg_view_page($title, $body);
