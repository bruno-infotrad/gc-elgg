<?php
$page_type = get_input('page_type');
$offset = get_input('offset');
$base_url = get_input('base_url');
if ($page_type == 'all') {
	$return = blog_get_page_content_list($offset,$base_url,NULL);
	echo $return['content'];
} elseif ($page_type == 'friends') {
	$user = elgg_get_logged_in_user_entity();
	$return = blog_get_page_content_friends($offset,$base_url,$user->guid);
	echo $return['content'];
} elseif ($page_type == 'owner') {
	$container_guid = get_input('group_guid');
	if (! $container_guid) {
		$container_guid = $user->getGUID();
	}
	$return = blog_get_page_content_list($offset,$base_url,$container_guid);
	echo $return['content'];
}
function blog_get_page_content_list($offset,$base_url,$container_guid = NULL) {

	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'base_url' => $base_url,
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => false,
		'offset' => $offset,
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		group_gatekeeper();

		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		if (!$container) {

		}
		$return['title'] = elgg_echo('blog:title:user_blogs', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($current_user && ($container_guid == $current_user->guid)) {
			$return['filter_context'] = 'mine';
		} else if (elgg_instanceof($container, 'group')) {
			$return['filter'] = false;
		} else {
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
		}
	} else {
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('blog:title:all_blogs');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('blog:blogs'));
	}

	elgg_register_title_button();

	// show all posts for admin or users looking at their own blogs
	// show only published posts for other users.
	$show_only_published = true;
	if ($current_user) {
		if (($current_user->guid == $container_guid) || $current_user->isAdmin()) {
			$show_only_published = false;
		}
	}
	if ($show_only_published) {
		$options['metadata_name_value_pairs'] = array(
			array('name' => 'status', 'value' => 'published'),
		);
	}

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$return['content'] = elgg_echo('blog:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
}

/**
 * Get page components to list of the user's friends' posts.
 *
 * @param int $user_guid
 * @return array
 */
function blog_get_page_content_friends($offset,$base_url,$user_guid) {

	$user = get_user($user_guid);
	if (!$user) {
		forward('blog/all');
	}

	$return = array();

	$return['filter_context'] = 'friends';
	$return['title'] = elgg_echo('blog:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "blog/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

	elgg_register_title_button();

	$options = array(
		'base_url' => $base_url,
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => false,
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'relationship_join_on' => 'container_guid',
		'preload_owners' => true,
		'preload_containers' => true,
	);

       	$list = elgg_list_entities_from_relationship($options);

	if (!$list) {
		$return['content'] = elgg_echo('blog:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
}
