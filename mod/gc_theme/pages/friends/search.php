<?php
$user = get_entity(elgg_get_logged_in_user_guid());
if ($vars['search_type'] == 'tag') {
	$tag = get_input('tag');

	$title = elgg_echo('friends:title:searchtag', array($tag));

	$options = array();
	$options['query'] = $tag;
	$options['type'] = "user";
	$options['offset'] = $offset;
	$options['limit'] = $limit;
	$results = elgg_trigger_plugin_hook('search', 'tags', $options, array());
	$count = $results['count'];
	$users = $results['entities'];
	$content = elgg_view_entity_list($users, array(
		'count' => $count,
		'offset' => $offset,
		'limit' => $limit,
		'full_view' => false,
		'list_type_toggle' => false,
		'pagination' => true,
	));
} else {
	$name = sanitize_string(get_input('name'));
	$title = elgg_echo('gc_theme:friends:title:searchname', array($name));
	$db_prefix = elgg_get_config('dbprefix');
	$options = array(
		'relationship' => 'friend',
		'relationship_guid' => $user->getGUID(),
		'inverse_relationship' => FALSE,
		'type' => 'user',
		'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
		'wheres' => array("(u.name LIKE \"%{$name}%\" OR u.username LIKE \"%{$name}%\")"),
		'limit' => '20',
		'order_by' => 'u.name',
		//'list_type' => 'gallery',
		'size' => 'medium',
		//'full_view' => FALSE
	);
	$content = elgg_list_entities_from_relationship($options);
}
$header = '<h2>'.elgg_echo('gc_theme:friends_search:results').$name.'</h2>';
$params = array(
        'content' => $header.$content,
        'sidebar' => elgg_view('friends/sidebar'),
        'title' => $title,
        'filter_override' => elgg_view('members/nav', array('selected' => 'friends')),
);
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);
