<?php
/**
 * View a single page
 *
 * @package ElggPages
 */
elgg_load_library('elgg:pages');

$page_guid = get_input('guid');
$page = get_entity($page_guid);
if (!$page) {
	forward();
}

elgg_set_page_owner_guid($page->getContainerGUID());


$show_add_form =  true;
$container = elgg_get_page_owner_entity();
if ($container instanceof ElggGroup) {
	group_gatekeeper();
	$user = elgg_get_logged_in_user_entity();
        if (elgg_is_logged_in() && $container->isMember($user)) {
		$show_add_form =  true;
	} else {
		$show_add_form =  false;
	}
}

if (!$container) {
}

$title = $page->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "pages/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "pages/owner/$container->username");
}
pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($title);

$content = elgg_view_entity($page, array('full_view' => true,'class' => 'agora-page-list',));
$content .= elgg_view_comments($page,$show_add_form);

// can add subpage if can edit this page and write to container (such as a group)
if ($page->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	$url = "pages/add/$page->guid";
	elgg_register_menu_item('title', array(
			'name' => 'subpage',
			'href' => $url,
			'text' => elgg_echo('pages:newchild'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation'),
));

echo elgg_view_page($title, $body);
