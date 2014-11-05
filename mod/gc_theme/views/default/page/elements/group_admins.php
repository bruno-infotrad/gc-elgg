<?php 
$of="<div class=\"elgg-module elgg-module-aside\">\n";
$of.="<div class=\"elgg-head\">\n";
$of.="<h3>".elgg_echo('group_tools:multiple_admin:group_admins')."</h3>\n";
$of.= "</div>\n";
$of.="<div class=\"dropdown elgg-menu-page\">\n";
$of.="<ul class=\"no-bullets image-list\">\n";
$group = $vars["group"];
$dbprefix = elgg_get_config("dbprefix");
$options = array(
	"relationship" => "group_admin",
	"relationship_guid" => $group->getGUID(),
	"inverse_relationship" => true,
	"type" => "user",
	"limit" => false,
	"list_type" => "gallery",
	"gallery_class" => "elgg-gallery-users",
);
if (! elgg_is_admin_logged_in()) {
	$options['joins'] = array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid");
	$options['wheres'] = array("(u.banned = 'no')");
}
$owner=$group->getOwnerEntity();
if($users = elgg_get_entities_from_relationship($options)){
	$users=array_merge(array($owner),$users);
	$body = elgg_view_entity_list($users, $options);
        foreach ($users as $user) {
                $icon= elgg_view_entity_icon(get_user($user->guid), 'small');
                $of.= "<li>\n";
                $of.= $icon;
                $of.= "</li>\n";
        }
} else {
	$icon= elgg_view_entity_icon($owner, 'small');
	$of.= "<li>\n";
	$of.= $icon;
	$of.= "</li>\n";
}
$of.="</ul>";
$of.="</div></div>\n";
echo $of;
