<?php
/**
 * View a single wire
 *
 * @package ElggPages
 */

$thewire_guid = get_input('guid');
$thewire = get_entity($thewire_guid);
if (!$thewire) {
	forward();
}

elgg_set_page_owner_guid($thewire->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
}

$title = $thewire->title;
if (elgg_instanceof($container, 'group')) {
	if ($container->isMember(elgg_get_logged_in_user_entity())&& $container->readonly != 'yes') {
		$comments = elgg_view_comments($thewire);
	} else {
		$comments = elgg_view_comments($thewire,FALSE);
	}
} else {
	$comments = elgg_view_comments($thewire);
}
$content = elgg_view_entity($thewire, array('full_view' => true));
//Test if group member, if not do not allow to comment

$content .= $comments;
$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar/navigation'),
));

echo elgg_view_page($title, $body);
