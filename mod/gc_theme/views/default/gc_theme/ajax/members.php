<?php
/**
 * Members index
 *
 */
$page_type = get_input('page_type');
$offset = get_input('offset');
$name = get_input('name');
$options['offset'] = $offset;
require_once elgg_get_plugins_path() . 'gc_theme/lib/gc_find_active_users.php';
//Override function to get users to filter out banned users
$dbprefix = elgg_get_config("dbprefix");
$options = array('type' => 'user', 'full_view' => false);
switch ($page_type) {
	case 'search':
	$name = sanitize_string($name);
	$params = array( 'type' => 'user', 'full_view' => false, 'joins' => array("JOIN {$dbprefix}users_entity u ON e.guid=u.guid"),);
	if (! elgg_is_admin_logged_in()) {
		$params['wheres'] = array("((u.name LIKE \"%{$name}%\" OR u.username LIKE \"%{$name}%\") AND u.banned = 'no')");
	} else {
		$params['wheres'] = array("((u.name LIKE \"%{$name}%\" OR u.username LIKE \"%{$name}%\"))");
	}
	$content = elgg_list_entities($params);
	break;
	case 'popular':
		$options['relationship'] = 'friend';
		$options['inverse_relationship'] = false;
		$options['joins'] = array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid");
		if (! elgg_is_admin_logged_in()) {
			$options['wheres'] = array("(u.banned = 'no')");
		}
		$content = elgg_list_entities_from_relationship_count($options);
		break;
	case 'online':
	default:
		$offset = get_input('offset', 0);
		$count = find_active_users(600, 10, $offset, true);
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
		$options['joins'] = array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid");
		if (! elgg_is_admin_logged_in()) {
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
echo $content;
