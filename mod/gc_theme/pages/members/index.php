<?php
/**
 * Members index
 *
 */
require_once elgg_get_plugins_path() . 'gc_theme/lib/gc_find_active_users.php';
//Override function to get users to filter out banned users
$dbprefix = elgg_get_config("dbprefix");
$query = "SELECT count(*) as count from {$dbprefix}users_entity u JOIN {$dbprefix}entities e on u.guid=e.guid where u.banned='no' and e.enabled = 'yes'";
$result = get_data_row($query);
if ($result) {
	$num_members = $result->count;
}

$title = elgg_echo('members');

$options = array('type' => 'user', 'full_view' => false);
switch ($vars['page']) {
	case 'popular':
		$options['relationship'] = 'friend';
		$options['inverse_relationship'] = false;
		if (! elgg_is_admin_logged_in()) {
			$options['joins'] = array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid");
			$options['wheres'] = array("(u.banned = 'no')");
		}
		$content = elgg_list_entities_from_relationship_count($options);
		break;
	case 'online':
	default:
		$offset = get_input('offset', 0);
		$count = find_active_users(array('seconds'=>600, 'limit'=>10, 'offset'=>$offset, 'count'=>true));
		$objects = gc_find_active_users(600, 10, $offset);
		if ($objects) {
			$content = elgg_view_entity_list($objects, array(
			'count' => $count,
			'offset' => $offset,
			'limit' => 10
			));
		}
		break;
	case 'newest':
		if (! elgg_is_admin_logged_in()) {
			$options['joins'] = array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid");
			$options['wheres'] = array("(u.banned = 'no')");
		}
		$content = elgg_list_entities($options);
		break;
	case 'alphabetical':
	case 'all':
		$options =array(
			"type" => "user",
			"full_view" => false,
			"limit" => 15,
			"joins" => array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid"),
			"order_by" => "u.name asc",
		);
		if (! elgg_is_admin_logged_in()) {
			$options['wheres'] = array("(u.banned = 'no')");
		}
		$content = elgg_list_entities($options);
		break;
}

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title ,
	'filter_override' => elgg_view('members/nav', array('selected' => $vars['page'])),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
