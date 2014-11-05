<?php 
$of="<div class=\"elgg-module elgg-module-aside\">";
$of.="<div class=\"elgg-head\">";
$of.="<h3>".elgg_echo('admin:users')."</h3>";
$of.= "</div>";
$of.="<div class=\"dropdown elgg-menu-page\">";
$of.="<i id=\"elgg-expandable\" class=\"elgg-menu-parent elgg-menu-closed\"></i>\n";
$of.="<ul class=\"no-bullets image-list\" style=\"display: none\">";


$all_objects = find_active_users(600, 32, 0,true);
if ($all_objects) {
	$objects = find_active_users(600, 32, 0);
	foreach ($objects as $user) {
		$icon= elgg_view_entity_icon(get_user($user->guid), 'small');
		$of.= "<li>";
		$of.= $icon;
		$of.= "</li>";
	}
}

if ($all_objects > 32 ) {
	$of .= elgg_view('output/url', array(
	'text' => '<p>'.elgg_echo('gc_theme:all_online_users').'</p>',
        'href' => $vars["url"].'members/online',
));
}
$of.="</ul></div></div>";
echo $of;
