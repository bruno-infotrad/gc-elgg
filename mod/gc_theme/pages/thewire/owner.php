<?php
/**
 * User's wire posts
 * 
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('thewire/all');
}

$title = elgg_echo('thewire:user', array($page_owner->name));

elgg_push_breadcrumb(elgg_echo('thewire'), "thewire/all");
elgg_push_breadcrumb($page_owner->name);

if (elgg_get_logged_in_user_guid() == $page_owner->guid) {
	$form_vars = array('class' => 'thewire-single-form  thewire-form');
	$content = "<div id='thewire-single'>";
	$content .= elgg_view_form('compound/add', $form_vars);
	$content .= elgg_view('input/urlshortener');
	$content .= "</div>";
}

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'container_guid' => $page_owner->guid,
	//'owner_guid' => $page_owner->guid,
	'limit' => 15,
));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
        $filter_context = 'mine';
}

$vars = array(
        'filter_context' => $filter_context,
        'content' => $content,
        'title' => $title,
        'sidebar' => elgg_view('thewire/sidebar'),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
        $vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
