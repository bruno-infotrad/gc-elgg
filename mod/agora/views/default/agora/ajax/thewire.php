<?php
$page_type = get_input('page_type');
$offset = get_input('offset');
$base_url = get_input('base_url');
$options['base_url'] = $base_url;
$user = elgg_get_logged_in_user_entity();
if ($page_type == 'all') {
	$content = elgg_list_entities(array(
		'base_url' => $base_url,
		'type' => 'object',
		'subtype' => 'thewire',
		'limit' => get_input('limit', 15),
	));
        echo $content;
} elseif ($page_type == 'friends') {
	$content .= elgg_list_entities_from_relationship(array(
		'base_url' => $base_url,
	        'type' => 'object',
	        'subtype' => 'thewire',
	        'full_view' => false,
	        'relationship' => 'friend',
	        'relationship_guid' => $user->guid,
	        'relationship_join_on' => 'container_guid',
	        'preload_owners' => true,
	));
        echo $content;
} elseif ($page_type == 'owner') {
	$content = elgg_list_entities(array(
		'base_url' => $base_url,
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
			'base_url' => $base_url,
			'type' => 'object',
			'subtype' => 'thewire',
			'container_guid' => $group_guid,
			'limit' => 15,
		));
        	echo $content;
	}
}
