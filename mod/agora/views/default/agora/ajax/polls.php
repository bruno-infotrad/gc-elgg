<?php
//function polls_get_page_list($page_type, $container_guid = NULL) {
$page_type = get_input('page_type');
$container_guid = get_input('group_guid');
$offset = get_input('offset');
global $autofeed;
$autofeed = TRUE;
$user = elgg_get_logged_in_user_entity();
$params = array();
$options = array(
	'type'=>'object', 
	'subtype'=>'poll', 
	'full_view' => FALSE, 
	'limit'=>15,
);
$base_url = get_input('base_url');
$options['base_url'] = $base_url;

if ($page_type == 'group') {
	$group = get_entity($container_guid);
	if (!elgg_instanceof($group,'group') || !polls_activated_for_group($group)) {
		forward();
	}
	$crumbs_title = $group->name;
	$params['title'] = elgg_echo('polls:group_polls:listing:title', array(htmlspecialchars($crumbs_title)));
	$params['filter'] = "";
	
	// set breadcrumb
	elgg_push_breadcrumb($crumbs_title);
	
	elgg_push_context('groups');
	
	elgg_set_page_owner_guid($container_guid);
	group_gatekeeper();
	
	$options['container_guid'] = $container_guid;
	$user_guid = elgg_get_logged_in_user_guid();
	if (elgg_get_page_owner_entity()->canWriteToContainer($user_guid)){
		elgg_register_menu_item('title', array(
			'name' => 'add',
			'href' => "polls/add/".$container_guid,
			'text' => elgg_echo('polls:add'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}
	
} else {
	switch ($page_type) {
		case 'owner':
			$options['owner_guid'] = $container_guid;
			
			$container_entity = get_user($container_guid);
			elgg_push_breadcrumb($container_entity->name);
			
			if ($user->guid == $container_guid) {
				$params['title'] = elgg_echo('polls:your');
				$params['filter_context'] = 'mine';
			} else {
				$params['title'] = elgg_echo('polls:not_me',array(htmlspecialchars($container_entity->name)));
				$params['filter_context'] = "";
			}
			break;
		case 'friends':
			$container_entity = get_user($container_guid);
			$friends = $container_entity->getFriends(array('limit' => 0));
			//$friends = get_user_friends($container_guid, ELGG_ENTITIES_ANY_VALUE, 0);
			
			$options['container_guids'] = array();
			foreach ($friends as $friend) {
				$options['container_guids'][] = $friend->getGUID();
			}
			
			$params['filter_context'] = 'friends';
			$params['title'] = elgg_echo('polls:friends');
			
			elgg_push_breadcrumb($container_entity->name, "polls/owner/{$container_entity->username}");
			elgg_push_breadcrumb(elgg_echo('friends'));
			break;
		case 'all':
			$params['filter_context'] = 'all';
			$params['title'] = elgg_echo('item:object:poll');
			break;
	}
	
	$polls_site_access = elgg_get_plugin_setting('site_access', 'polls');
	
	if ((elgg_is_logged_in() && ($polls_site_access != 'admins')) || elgg_is_admin_logged_in()) {		
		elgg_register_menu_item('title', array(
			'name' => 'add',
			'href' => "polls/add",
			'text' => elgg_echo('polls:add'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}
}

if (($page_type == 'friends') && (count($options['container_guids']) == 0)) {
	// this person has no friends
	$params['content'] = '';
} else {
	$params['content'] = elgg_list_entities($options);
}
if (!$params['content']) {
	$params['content'] = elgg_echo('polls:none');
}

echo $params['content'];
