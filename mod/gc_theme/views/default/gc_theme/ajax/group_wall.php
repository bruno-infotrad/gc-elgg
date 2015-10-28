<?php
$group = get_entity(get_input('group_guid'));
$user = elgg_get_logged_in_user_entity();
$offset = get_input('offset');
$options['offset'] = $offset;
$base_url = get_input('base_url');

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('groups:notfound'));
	exit();
}

//elgg_load_library('elgg:groups');

if (group_gatekeeper(false)) {
	if (!$group instanceof ElggGroup) {
        	exit;
	}
//put test in if user not part of group don't show composer otherwise goes in lalaland
	$db_prefix = elgg_get_config('dbprefix');
	$options = array(
		'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
		'wheres' => array("(e.container_guid = $group->guid OR rv.object_guid = $group->guid) AND rv.action_type != 'vote' AND rv.action_type != 'join'"),
	);
	$options['body_class'] = 'new-feed';
	$options['base_url'] = $base_url;
	$activity = elgg_list_river($options);
	
	if (!$activity) {
		$activity = elgg_view('output/longtext', array('value' => elgg_echo('groups:activity:none')));
	}
	echo $activity;
}
