<?php
if (elgg_is_logged_in()) {
	if (!($username = get_input('username'))) {
               	exit;
        }
	if(!($user=get_user_by_username($username))) {
               	exit;
	}
	$db_prefix = elgg_get_config('dbprefix');
	$options = array( 'type' => 'group',
			'relationship' => 'member',
			'relationship_guid' => $user->getGUID(),
			'joins' => array("JOIN ".$db_prefix."groups_entity ge ON e.guid=ge.guid"),
			'order_by' => 'name asc',
			'limit' => 1000,
			'offset' => 10,
	);
	$groups = elgg_get_entities_from_relationship($options);
	foreach ($groups as $group) {
		if (strlen($group->name) > 23) {
			$group_name = substr($group->name,0,23).'...';
		} else {
			$group_name = $group->name;
		}
		elgg_register_menu_item('all_my_groups', array(
			'name' => "group-$group->guid",
			'text' => $group_name,
			'title' => $group->name,
			'href' => $group->getURL(),
			'priority' => 31,
			//'parent_name' => 'groups:all',
			//'section' => '1communities',
		));
	}
	$html = elgg_view_menu('all_my_groups',array('sort_by' => 'priority', 'class' => 'all-my-groups elgg-menu-hz'));
	$html = preg_replace('/<\/?ul.*>/U','',$html);
	echo $html;
}
