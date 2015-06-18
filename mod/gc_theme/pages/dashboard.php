<?php
gatekeeper();

$dbprefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
//PNS
$now = time();
$jour = 24*3600;
//$user->pns_viewed = 0;
if (!isset($user->pns_viewed) || (($now - $user->pns_viewed) > $jour)) {
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	echo elgg_view('output/url', array(
        	'text' => '',
        	'href' => 'pns',
        	'class' => 'elgg-lightbox elgg-button elgg-button-submit',
        	'id' => 'pns-hidden',
	));
}
$exec_posts = get_exec_content();
// 2014-05-01 00:00:00 1400630400
$update_date = 1400630400;
if (!isset($user->intro_viewed) || $user->intro_viewed < $update_date) {
	$page_type = 'intro';
	if (($user->pns_viewed+$jour) > $update_date) {
		$user->intro_viewed = $now;
	}
} else {
	$page_type = get_input('page_type');
	if (! $page_type) {
		$preferred_tab = $user->preferred_tab;
		$page_type = ($preferred_tab)?$preferred_tab:'all';
	}
}
//Code for marking non viewed stuff
$offset = get_input('offset');
if (((!isset($offset) || $offset == 0)) && ($user->pns_viewed ) && (($now - $user->pns_viewed) < $jour) && $page_type == 'all') {
	$last_viewed = $user->feed_viewed;
	$user->feed_viewed_previous = $last_viewed;
	$user->feed_viewed = time();
	$user->activity_viewed = $user->feed_viewed;
	//call this to delete unused time stamps right away
	//delete_orphaned_metastrings();
	elgg_log("BRUNO dashboard:feed_viewed $user->feed_viewed $user->feed_viewed_previous",'NOTICE');
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('newsfeed');

//$composer = elgg_view('page/elements/composer', array('entity' => $user, 'class' => 'elgg-composer-dashboard'));
$composer = elgg_view('compound/multi', array("id" => "invite_to_group",));
//$composer = elgg_view_form('compound/add', array('enctype' => 'multipart/form-data'));

$options = array();
$options['page_type'] = $page_type;
//$options['limit'] = 100;
$options['body_class'] = 'new-feed';
switch ($page_type) {
        case 'exec_content':
		$options = array(
			'type' => 'object',
			'full_view' => false,
		);
		$options['metadata_name_value_pairs'] = array(
                        array('name' => 'exec_content', 'value' => 'true'),
                );
		$stream = elgg_list_entities_from_metadata($options);
		if (!$stream) {
			$stream = '<h3>' . elgg_echo('gc_theme:exec_content:none') . '</h3>';
		}
		break;
        case 'my_groups':
		$title = elgg_echo('groups:yours');
		$page_filter = 'my_groups';
		$group_list = elgg_get_entities_from_relationship(array(
                                'type' => 'group',
                                'relationship' => 'member',
                                'relationship_guid' => elgg_get_logged_in_user_guid(),
                                'inverse_relationship' => false,
				'limit' => 0,
                        ));
		if (count($group_list)) {
			foreach($group_list as $group) {
				if ($group_guid) {
					$group_guid .= ' OR e.container_guid='.$group->guid;
				} else {
					$group_guid = 'e.container_guid='.$group->guid;
				}
			}
			$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' AND ".$group_guid);
			$options['joins'] = array("JOIN " . $dbprefix . "entities e ON (e.guid=rv.object_guid or e.guid=rv.target_guid) JOIN " . $dbprefix . "groups_entity ge ON e.container_guid=ge.guid");
			$stream = elgg_list_river($options);
		} else {
			$stream = '<h3>' . elgg_echo('gc_theme:mygroups:none') . '</h3>';
		}
                break;
        case 'groups':
		$title = elgg_echo('groups');
		$page_filter = 'groups';
		$options['wheres']=array("rv.access_id in('1','2') AND rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' or rv.type='group'");
		$options['joins'] = array("JOIN " . $dbprefix . "entities e ON (e.guid=rv.object_guid or e.guid=rv.target_guid) JOIN " . $dbprefix . "groups_entity ge ON e.container_guid=ge.guid");
		$options['order_by'] = "rv.posted desc";
		$stream = elgg_list_river($options);
                break;
        case 'all':
                $title = elgg_echo('river:all');
                $page_filter = 'all';
		$options['wheres']=array("rv.type != 'user' AND rv.type != 'site' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote' AND rv.action_type != 'event_relationship'");
		$stream = elgg_list_river($options);
                break;
	case 'intro':
                $title = elgg_echo('river:all');
                $page_filter = 'new_user';
                $stream = elgg_view('gc_theme/intro');
                break;

        case 'friends':
        default:
                $title = elgg_echo('river:friends');
                $page_filter = 'friends';
                $options['relationship_guid'] = elgg_get_logged_in_user_guid();
                $options['relationship'] = 'friend';
		$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
		$options['count']=TRUE;
		$count = elgg_get_river($options);
		if ($count == 0) {
			$stream = '<h3>' . elgg_echo('gc_theme:colleagues:none') . '</h3>';
		} else {
			$options['count']=FALSE;
			$stream = elgg_list_river($options);
		}
                break;
}

$filter = elgg_view('river/dashboard_sort_menu', array('page_type' => $page_type));

$sidebar = elgg_view('core/river/sidebar');

$body = elgg_view_layout('two_sidebar_river', array(
		'title' => '', 
		'content' => $composer.$exec_posts.$filter.$stream, 
		'sidebar' => $sidebar,
		'sidebar_alt' => $activity, 
		'filter_context' => $page_filter,
						)
	);

elgg_set_page_owner_guid(1);

echo elgg_view_page($title, $body);

function get_exec_content() {
	$options = array(
		'type' => 'object',
		'full_view' => false,
		'order_by' => 'e.time_updated desc',
	);
	$options['metadata_name_value_pairs'] = array(
       		array('name' => 'exec_content', 'value' => 'true'),
       	);
	$options['limit'] = 3;
	$exec_posts = elgg_get_entities_from_metadata($options);
	if ($exec_posts) {
		$exec_posts_html = '<div class="bandeau-exec-content-title">';
		$exec_posts_html .= elgg_echo('gc_theme:exec_content_title');
		$exec_posts_html .= '</div>';
		$exec_posts_html .= '<div class="bandeau-exec-content">';
		foreach ($exec_posts as $exec_post) {
			$exec_posts_html .= elgg_view('gc_theme/exec_content',array('entity' => $exec_post));
		}
		$exec_posts_html .= '</div>';
	}
	return $exec_posts_html;
}
?>
<script>
$(document).ready(function(){
        $("#pns-hidden").colorbox({showCloseButton:false,width:"75%"}).trigger('click');
});
</script>

