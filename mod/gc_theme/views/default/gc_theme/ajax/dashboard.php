<?php
gatekeeper();

$dbprefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($user->guid);
$options = array();
$offset = get_input('offset');
$options['offset'] = $offset;
$page_type = get_input('page_type');
$base_url = get_input('base_url');
$options['base_url'] = $base_url;
$already_viewed = get_input('already_viewed');
$GLOBALS['GC_THEME']->debug("IN AJAX ALREADY_VIEWED=$already_viewed");
$options['already_viewed'] = $already_viewed;
if (! $page_type) {
	$preferred_tab = $user->preferred_tab;
	$page_type = ($preferred_tab)?$preferred_tab:'all';
}
switch ($page_type) {
        case 'my_groups':
		$title = elgg_echo('groups:yours');
		$page_filter = 'my_groups';
		$group_list = elgg_get_entities_from_relationship(array(
                                'type' => 'group',
                                'relationship' => 'member',
                                'relationship_guid' => elgg_get_logged_in_user_guid(),
                                'inverse_relationship' => false,
                        ));
		foreach($group_list as $group) {
			if ($group_guid) {
				$group_guid .= ' OR e1.container_guid='.$group->guid;
				$group_guid .= ' OR e2.container_guid='.$group->guid;
			} else {
				$group_guid = 'e1.container_guid='.$group->guid;
				$group_guid .= ' OR e2.container_guid='.$group->guid;
			}
		}
		$options['joins'] = array("JOIN elgg_entities e1 ON e1.guid = rv.object_guid",
					"LEFT JOIN elgg_entities e2 ON e2.guid = rv.target_guid",
					"LEFT JOIN elgg_groups_entity ge1 ON ge1.guid = e1.container_guid",
					"LEFT JOIN elgg_groups_entity ge2 ON ge2.guid = e2.container_guid"
					);
		$options['wheres']=array("(rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' AND ".$group_guid.")", "(ge1.guid IS NOT NULL OR ge2.guid IS NOT NULL)");
                break;
        case 'groups':
		$title = elgg_echo('groups');
		$page_filter = 'groups';
		$options['joins'] = array("JOIN elgg_entities e1 ON e1.guid = rv.object_guid",
					"LEFT JOIN elgg_entities e2 ON e2.guid = rv.target_guid",
					"LEFT JOIN elgg_groups_entity ge1 ON ge1.guid = e1.container_guid",
					"LEFT JOIN elgg_groups_entity ge2 ON ge2.guid = e2.container_guid"
					);
		$options['wheres']=array("(rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote')", "(ge1.guid IS NOT NULL OR ge2.guid IS NOT NULL)");
		//$options['wheres']=array("(rv.access_id in('1','2') AND rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote')", "(ge1.guid IS NOT NULL OR ge2.guid IS NOT NULL)");
                break;
        case 'dfatd-maecd':
                $title = elgg_echo('gc_theme:dfatd');
                $page_filter = 'dfatd-maecd';
		$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
                break;
        case 'all':
                $title = elgg_echo('river:all');
                $page_filter = 'all';
		$options['wheres']=array("rv.type != 'user' AND rv.type != 'site' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
                break;
        case 'friends':
        default:
                $title = elgg_echo('river:friends');
                $page_filter = 'friends';
                $options['relationship_guid'] = elgg_get_logged_in_user_guid();
                $options['relationship'] = 'friend';
		$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
                break;
}

$options['body_class'] = 'new-feed';
$stream = elgg_list_river($options);
echo $stream;
