<?php
gatekeeper();

$db_prefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($user->guid);
$options = array();
$offset = get_input('offset');
$options['offset'] = $offset;
$page_type = get_input('page_type');
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
				$group_guid .= ' OR e.container_guid='.$group->guid;
			} else {
				$group_guid = 'e.container_guid='.$group->guid;
			}
		}
		$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' AND ".$group_guid);
		$options['joins'] = array("JOIN " . $db_prefix . "entities e ON rv.object_guid=e.guid JOIN " . $db_prefix . "groups_entity ge ON e.container_guid=ge.guid");
                break;
        case 'groups':
		$title = elgg_echo('groups');
		$page_filter = 'groups';
		$options['wheres']=array("rv.access_id in('1','2') AND rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' or rv.type='group'");
		$options['joins'] = array("JOIN " . $db_prefix . "entities e ON rv.object_guid=e.guid JOIN " . $db_prefix . "groups_entity ge ON e.container_guid=ge.guid");
		$options['order_by'] = "rv.posted desc";
		$query = "SELECT rv.* from {$CONFIG->dbprefix}river rv where rv.object_guid = $item->object_guid ORDER BY rv.annotation_id DESC LIMIT 1";
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
