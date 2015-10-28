<?php


$group = elgg_get_page_owner_entity();
$user = elgg_get_logged_in_user_entity();

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('groups:notfound'));
	forward();
}

elgg_load_library('elgg:groups');
elgg_load_js('elgg.gc_comments');
elgg_load_js('elgg.gc_wire');
elgg_load_js('elgg.gc_gft');
elgg_load_js('elgg.discussion');
elgg_load_js('elgg.gc_discussion');
groups_register_profile_buttons($group);
gc_groups_register_profile_buttons($group);

$title = $group->name;
$context = elgg_get_context();

if (group_gatekeeper(false)) {
	if (!$group instanceof ElggGroup) {
        	forward('groups/all');
	}
	$composer = '';
//put test in if user not part of group don't show composer otherwise goes in lalaland
	if (elgg_is_logged_in() && $group->isMember($user)) {
		//$composer = elgg_view('page/elements/composer', array('entity' => $group));
		$composer = elgg_view('compound/multi', array("id" => "invite_to_group",));
		//$composer = elgg_view_form('compound/add', array('enctype' => 'multipart/form-data'));
	}
	$db_prefix = elgg_get_config('dbprefix');
	switch ($context) {
		case 'profile':
			$group_guid = 'e1.container_guid='.$group->guid;
			$group_guid .= ' OR e2.container_guid='.$group->guid;
			$options['joins'] = array("JOIN elgg_entities e1 ON e1.guid = rv.object_guid",
						"LEFT JOIN elgg_entities e2 ON e2.guid = rv.target_guid",
						"LEFT JOIN elgg_groups_entity ge1 ON ge1.guid = e1.container_guid",
						"LEFT JOIN elgg_groups_entity ge2 ON ge2.guid = e2.container_guid"
						);
			$options['wheres']=array("(rv.action_type != 'join' AND rv.action_type != 'vote' AND ".$group_guid.")", "(ge1.guid IS NOT NULL OR ge2.guid IS NOT NULL)");
/*
			$options = array(
				'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
				'wheres' => array("(e.container_guid = $group->guid OR rv.object_guid = $group->guid) AND rv.action_type != 'vote' AND rv.action_type != 'join'"),
			);
*/

	}
	$options['body_class'] = 'new-feed';
	$activity = elgg_list_river($options);
	
	if (!$activity) {
		$activity = elgg_view('output/longtext', array('value' => elgg_echo('groups:activity:none')));
	}
	if (elgg_is_active_plugin('search')) {
	//	$sidebar_alt = elgg_view('groups/sidebar/search', array('entity' => $group));
	}
	$tabs = elgg_view("gc_theme/group_tabs", array("type" => $type));
	//$sidebar_alt .= elgg_view('groups/sidebar/members', array('entity' => $group,'limit' => 27));
	$body = elgg_view_layout('two_sidebar_group_wall', array(
		'title' => $title,
		'content' => $tabs . $composer . $activity,
		//'sidebar_alt' => $sidebar_alt,
		'entity' => $group,
	));
	
	echo elgg_view_page($title, $body);
} else {
        $activity = elgg_view('groups/profile/closed_membership');
	$body = elgg_view_layout('two_sidebar_group_wall', array(
		'title' => $title,
		'content' => $activity,
		'entity' => $group,
	));
	echo elgg_view_page($title, $body);
}
