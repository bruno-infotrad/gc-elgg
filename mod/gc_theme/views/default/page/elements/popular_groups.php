<?php 
$of="<div class=\"elgg-module elgg-module-aside\" id=\"top-border\">\n";
$of.="<h2>".elgg_echo('gc_theme:popular_groups')."</h2>\n";
/*
$options = array(
	'type' => 'group',
	'relationship' => 'member',
	'inverse_relationship' => false,
	'full_view' => false,
);

$results = elgg_get_entities_from_relationship_count($options);
*/
$results = find_most_active_groups(30);
$group_num=0;
foreach ($results as $group_guid=>$count) {
	$group=get_entity($group_guid);
	$of.=elgg_view('page/elements/popular_group', array('group' => $group,'count' => $count));
	$group_num++;
	if ($group_num > 9) {
		break;
	}
}
$of.="</div>\n";
echo $of;

function find_most_active_groups($past) {
	$now = time();
	$then = $now-3600*24*$past;
	$db_prefix = elgg_get_config('dbprefix');
	$query_group_list = "SELECT distinct container_guid FROM {$db_prefix}river rv join {$db_prefix}entities e on rv.object_guid=e.guid join {$db_prefix}groups_entity eg on e.container_guid=eg.guid where rv.action_type not in ('create','join') and rv.posted>$then";
	//elgg_log("BRUNO ACTIVE GROUPS $query_group_list",'NOTICE');
	$group_list = get_data($query_group_list);
	//elgg_log("BRUNO ACTIVE GROUPS ".var_export($group_list,true),'NOTICE');
	foreach ($group_list as $group) {
		//elgg_log("BRUNO ACTIVE GROUP ".$group->container_guid,'NOTICE');
		$query_activity_count = "SELECT count(id) as count FROM {$db_prefix}river rv join {$db_prefix}entities e on rv.object_guid=e.guid where rv.posted>$then and e.container_guid=$group->container_guid";
		$result = get_data_row($query_activity_count);
		//elgg_log("BRUNO ACTIVE GROUP COUNT".var_export($result,true),'NOTICE');
		$counts[$group->container_guid] = $result->count;
		//elgg_log("BRUNO ACTIVE GROUP COUNT".$counts[$group->container_guid],'NOTICE');
	}
	arsort($counts,SORT_NUMERIC);
	return $counts;
}
