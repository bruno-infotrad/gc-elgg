<?php
$page_type = get_input('page_type');
$offset = get_input('offset');
$user = elgg_get_logged_in_user_entity();
if ($page_type == 'all') {
	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'thewire',
		'limit' => get_input('limit', 15),
	));
        echo $content;
} elseif ($page_type == 'friends') {
	$content = list_user_friends_objects($user->guid, 'thewire', 15, false);
        echo $content;
} elseif ($page_type == 'owner') {
	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'thewire',
		'container_guid' => $user->guid,
		'limit' => 15,
	));
        echo $content;
} elseif ($page_type == 'group') {
	$group_guid = get_input('group_guid');
	if ($group_guid) {
		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'thewire',
			'container_guid' => $group_guid,
			'limit' => 15,
		));
        	echo $content;
	}
}
