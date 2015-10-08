<?php 
$of="<div class=\"elgg-module elgg-module-aside\" id=\"top-border\">\n";
$of.="<h2>".elgg_echo('agora:popular_discussions')."</h2>\n";
$end=time();
$start=$end-3600*24*30;
$db_prefix = elgg_get_config('dbprefix');
$options = array(
    'type' => 'object',
    'limit' => '5',
    'selects' => array(("count( * ) AS views"),("MAX(ce.time_created) AS comment_time")),
    'joins' => array( "JOIN {$db_prefix}entities ce ON ce.container_guid = e.guid", "JOIN {$db_prefix}entity_subtypes cs ON ce.subtype = cs.id AND cs.subtype = 'comment'"),
    'wheres' => array("ce.time_created > ".$start." AND ce.time_created < ".$end),
    'group_by' => 'e.guid',
    'order_by' => "views DESC",
    'full_view' => false,
    'pagination' => false,
); 

$results = elgg_get_entities($options);
foreach ($results as $discussion) {
	$of.=elgg_view('page/elements/popular_discussion', array('discussion' => $discussion));
}
$of.="</div>\n";
echo $of;
