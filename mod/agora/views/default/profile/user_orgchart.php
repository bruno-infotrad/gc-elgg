<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$user = get_entity($vars['container_guid']);
echo "<div id=\"elgg-profile-gadgets\" >";
if (elgg_is_active_plugin('dfait_adsync')) {
//      // file path to the page scripts
	$lib_path = elgg_get_plugins_path() . 'dfait_adsync/lib';
	require_once("$lib_path/functions.php");

	$nbToGet = 4;
	$hierarchy = adsync_get_hierarchy($user->user_dn, $nbToGet);
	$vars['hierarchy'] = $hierarchy;
 	$hierarchy_widget = elgg_view('widgets/dfait_adsync_hierarchy/content', $vars);
	echo "<br/>";
 	echo "<div id=\"elgg-teammates\"><h3>" . elgg_echo("adsync:hierarchy") . "</h3>";
		echo $hierarchy_widget;
 	echo "</div>";
 		
	$teammates = adsync_get_teammates($user->manager_dn);
	$vars['teammates'] = $teammates;
	$teammates_widget = elgg_view('widgets/dfait_adsync_teammates/content', $vars);
	echo "<br/>";
	echo "<div id=\"elgg-teammates\"><h3>" . elgg_echo("adsync:teammates") . "</h3>";
	echo $teammates_widget;
	echo "</div>";
	
	$org_members = adsync_get_org_members($user->org_dn);
	$vars['org_members'] = $org_members;
	$org_members_widget = elgg_view('widgets/dfait_adsync_org_members/content', $vars);
	echo "<br/>";
	echo "<div id=\"elgg-org_members\"><h3>" . elgg_echo("adsync:org_members") . "</h3>";
	echo $org_members_widget;
	echo "</div>";
}
