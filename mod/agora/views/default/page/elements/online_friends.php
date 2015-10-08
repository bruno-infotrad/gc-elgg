<?php 
$dbprefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
$time = time() - 600;
$online_friends_count =  elgg_get_entities_from_relationship(array(
                'relationship' => 'friend',
                'relationship_guid' => $user->guid,
                'type' => 'user',
                'subtype' => ELGG_ENTITIES_ANY_VALUE,
		'joins' => array("join {$dbprefix}users_entity u on e.guid = u.guid"),
		'wheres' => array("u.last_action >= {$time}"),
		'order_by' => "u.last_action desc",
                'count' => true,
                'offset' => 0,
        ));
$of="<div class=\"elgg-module elgg-module-aside\">";
$of.="<h2>".elgg_echo('agora:online')."</h2>";
$of.="<div class=\"elgg-head\">";
$of.="<h3>".elgg_echo('friends')."</h3>";
$of.= "</div>";
$of.="<div class=\"dropdown elgg-menu-page\">";
$of.="<i id=\"elgg-expandable\" class=\"elgg-menu-parent elgg-menu-opened\"></i>";
$of.="<ul class=\"no-bullets image-list\">";
if ($online_friends_count > 0 ) {
	$online_friends =  elgg_get_entities_from_relationship(array(
                'relationship' => 'friend',
                'relationship_guid' => $user->guid,
                'type' => 'user',
                'subtype' => ELGG_ENTITIES_ANY_VALUE,
		'joins' => array("join {$dbprefix}users_entity u on e.guid = u.guid"),
		'wheres' => array("u.last_action >= {$time}"),
		'order_by' => "u.last_action desc",
                'limit' => 16,
                'offset' => 0,
        ));
	foreach ($online_friends as $friend) {
		$of.= "<li>";
		$of.= elgg_view_entity_icon(get_user($friend->guid), 'small');
		$of.= "</li>";
	}
}
if ($online_friends_count > 16 ) {
        $of .= elgg_view('output/url', array(
        'text' => '<p>'.elgg_echo('agora:all_online_colleagues').'</p>',
        'href' => elgg_get_site_url().'friends/'.$user->username,
));
}
$of.="</ul></div></div>";
echo $of;
