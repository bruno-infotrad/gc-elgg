<?php


$group = elgg_get_page_owner_entity();
$user = elgg_get_logged_in_user_entity();

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('groups:notfound'));
	forward();
}

elgg_load_library('elgg:groups');
groups_register_profile_buttons($group);

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
			$options = array(
				'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
				'wheres' => array("(e.container_guid = $group->guid OR rv.object_guid = $group->guid) AND rv.action_type != 'vote' AND rv.action_type != 'join'"),
			);

	}
	$options['body_class'] = 'new-feed';
	$activity = elgg_list_river($options);
	
	if (!$activity) {
		$activity = elgg_view('output/longtext', array('value' => elgg_echo('group:activity:none')));
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
