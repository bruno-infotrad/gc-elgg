<?php 
$of="<div class=\"elgg-module elgg-module-aside\">";
$of.="<div class=\"elgg-head\">";
$of.="<h3>".elgg_echo('admin:users')."</h3>";
$of.= "</div>";
$of.="<div class=\"dropdown elgg-menu-page\">";
$of.="<i id=\"elgg-expandable\" class=\"elgg-menu-parent elgg-menu-closed\"></i>\n";
$of.="<ul class=\"no-bullets image-list\" style=\"display: none\">";


$all_objects = find_active_users(array('seconds'=>600, 'limit'=>32, 'offset'=>0, 'count'=>true));
if ($all_objects) {
	$objects = find_active_users(array('seconds'=>600, 'limit'=>32, 'offset'=>0, 'count'=>false));
	foreach ($objects as $user) {
		$icon= elgg_view_entity_icon(get_user($user->guid), 'small');
		$of.= "<li>";
		$of.= $icon;
		$of.= "</li>";
	}
}

if ($all_objects > 32 ) {
	$of .= elgg_view('output/url', array(
	'text' => '<p>'.elgg_echo('agora:all_online_users').'</p>',
        'href' => elgg_get_site_url().'members/online',
));
}
$of.="</ul></div></div>";
echo $of;
