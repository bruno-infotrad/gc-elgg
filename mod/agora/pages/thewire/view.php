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
	elgg_push_breadcrumb($container->name, "thewire_group/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "thewire/owner/$container->username");
}
//pages_prepare_parent_breadcrumbs($thewire);
elgg_push_breadcrumb($title);

$content = elgg_view_entity($thewire, array('full_view' => true));
$content .= elgg_view_comments($thewire);
$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar/navigation'),
));

echo elgg_view_page($title, $body);
