<?php

	if ($user = elgg_get_logged_in_user_entity()) {
		$group = elgg_extract("entity", $vars);
		
		if(!empty($group) && elgg_instanceof($group, "group")){
		    if($group->canEdit() || $user->isAdmin()){
			if(elgg_is_active_plugin("messages")){
				// maybe more members are being notified by site
				$notification_options["relationship"] = "notifysite";
				
				if($site_notification_count = elgg_get_entities_from_relationship($notification_options)){
					if($site_notification_count > $notification_count){
						$notification_count = $site_notification_count;
					}
				}
			}
			
			// start building content
			$title = elgg_echo("group_tools:admin_notifications:title");
			
			//$content = "<div class='mbm'>" . elgg_echo("group_tools:admin_notifications:description", array($member_count, $notification_count)) . "</div>";
			$content='';
			// enable notification for everyone
			//Enable disable admin notifications
			$content.='<div class="elgg-group-stats">';
			$content.='<div class="admin-group-notifications">';
			$content .= elgg_view("output/url", array(
				"href" => "action/group_tools/notifications?toggle=enable_admins&notification_method=email&guid=" . $group->getGUID(),
				"text" => elgg_echo("group_tools:notifications:email:admin_enable"),
				"confirm" => true,
				"class" => "elgg-button elgg-button-submit mrm"));
			$content .= '</div>';
			$content.='<div class="admin-group-notifications">';
			$content .= elgg_view("output/url", array(
				"href" => "action/group_tools/notifications?toggle=enable_admins&notification_method=site&guid=" . $group->getGUID(),
				"text" => elgg_echo("group_tools:notifications:site:admin_enable"),
				"confirm" => true,
				"class" => "elgg-button elgg-button-submit mrm"));
			$content.='</div>';
			$content.='</div>';
			$content.='<div class="elgg-group-stats">';
			$content.='<div class="admin-group-notifications">';
			$content .= elgg_view("output/url", array(
				"href" => "action/group_tools/notifications?toggle=disable_admins&notification_method=email&guid=" . $group->getGUID(),
				"text" => elgg_echo("group_tools:notifications:email:admin_disable"),
				"confirm" => true,
				"class" => "elgg-button elgg-button-submit mrm"));
			$content.='</div>';
			$content.='<div class="admin-group-notifications">';
			$content .= elgg_view("output/url", array(
				"href" => "action/group_tools/notifications?toggle=disable_admins&notification_method=site&guid=" . $group->getGUID(),
				"text" => elgg_echo("group_tools:notifications:site:admin_disable"),
				"confirm" => true,
				"class" => "elgg-button elgg-button-submit mrm"));
			$content.='</div>';
			$content.='</div>';
			
			// echo content
			echo elgg_view_module("info", $title, $content);
		}
	    }
	}
