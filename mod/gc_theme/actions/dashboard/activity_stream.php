<?php
/**
 * Action called by AJAX periodically to update activity
 *
 * @package gc_theme
 */

if (elgg_is_logged_in()) {
	$dbprefix = elgg_get_config('dbprefix');
	$debut = get_input("debut");
	$page_owner_guid = get_input("page_owner_guid");
	elgg_set_page_owner_guid($page_owner_guid);
	$page_owner = get_entity($page_owner_guid);
	$user = elgg_get_logged_in_user_entity();
	if ($page_owner instanceof ElggGroup) {
		$activity_label = elgg_echo('gc_theme:group:recent_activity');
		$group_options = array(
			'joins' => array("JOIN {$dbprefix}entities e ON e.guid = rv.object_guid"),
			'wheres' => array("(e.container_guid = $page_owner_guid OR rv.object_guid = $page_owner_guid) AND rv.action_type != 'vote'"),
		);
	} else {
		$activity_label = elgg_echo('gc_theme:river:recent_activity');
	}
	$activity_last_viewed = 0;
	$limit = 12;
	if ($debut == 'false') {
		//$limit = 12;
		if ($user->activity_viewed) {
			if (sizeof($user->activity_viewed) > 1) {
        	                $user->activity_viewed=$user->activity_viewed['0'];
        	        }
			$activity_last_viewed = $user->activity_viewed;
		} else {
			$activity_last_viewed = $user->feed_viewed;
		}
	}
	$username=$user->username;
	if ($debut == 'true') {
		$options=array('limit' => $limit, 'posted_time_lower' => $activity_last_viewed, 'order_by' => 'rv.posted desc');
		if ($group_options) {
			$options = array_merge($options, $group_options);
		}
		$activity = elgg_get_river($options);
		$item[0] = '<h4 id="elgg-sidebar-alt-river-activity">'.$activity_label.'</h4>';
		$item[0] .= '<span id="elgg-sidebar-alt-river">';
		foreach ($activity as $activity_item) {
			$item[0] .= elgg_view('page/elements/jquery_activity', array('activity' => array($activity_item)));
		}
		$item[0] .= '</span>';
		$user->activity_viewed = time();
	} else {
		if ($activity_last_viewed) {
			$options=array('limit' => $limit, 'posted_time_lower' => $activity_last_viewed, 'order_by' => 'rv.posted asc');
			if ($group_options) {
				$options = array_merge($options, $group_options);
			}
			$activity = elgg_get_river($options);
			if ($activity) {
				$user->activity_viewed = $activity[count($activity)-1]->posted+1;
				$i=0;
				foreach ($activity as $activity_item) {
					if ($activity_item->posted == $activity_last_viewed && $activity_item->subject_guid == $user->guid) {continue;}
					$river_posted=$activity_item->posted;
					$item[$i] = elgg_view('page/elements/jquery_activity', array('activity' => array($activity_item)));
					$i++;
				}
			}
		}
	}
	$user_activity_viewed = $user->activity_viewed;
	$json = array('success' => TRUE, 'item' => $item,'debut' => $debut);
	echo json_encode($json);
}
// For no forwarding?
exit;
