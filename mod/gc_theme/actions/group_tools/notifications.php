<?php
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethods();
	$toggle = get_input("toggle");
	$notification_method = get_input("notification_method");
	$guid = (int) get_input("guid");
	
	$forward_url = REFERER;
	
	if(!empty($guid) && !empty($toggle)){
		if(($group = get_entity($guid)) && elgg_instanceof($group, "group")){
		   if ($group->canEdit()) {
			if($toggle == "enable"||$toggle == "disable"){
				// get group members
				$members = $group->getMembers(array("count" => true));
				if (!empty($members)) {
					$options = array(
						"type" => "user",
						"relationship" => "member",
						"relationship_guid" => $group->getGUID(),
						"inverse_relationship" => true,
						"limit" => false,
					);
					$members = new ElggBatch("elgg_get_entities_from_relationship", $options);
					if($toggle == "enable"){
						// fix notifications settings for site and email
						$auto_notification_handlers = array(
							"site",
							"email"
						);
						
						// enable notification for everyone
						foreach($members as $member){
							foreach($NOTIFICATION_HANDLERS as $method => $dummy){
								if(in_array($method, $auto_notification_handlers)){
									add_entity_relationship($member->getGUID(), "notify" . $method, $group->getGUID());
								}
							}
						}
						
						system_message(elgg_echo("group_tools:action:notifications:success:enable"));
						$forward_url = $group->getURL();
					} elseif($toggle == "disable"){
						// disable notification for everyone
						foreach($members as $member){
							foreach($NOTIFICATION_HANDLERS as $method => $dummy){
								remove_entity_relationship($member->getGUID(), "notify" . $method, $group->getGUID());
							}
						}
						
						system_message(elgg_echo("group_tools:action:notifications:success:disable"));
						$forward_url = $group->getURL();
					} else {
						register_error(elgg_echo("group_tools:action:notifications:error:toggle"));
					}
				}
			} elseif($toggle == "enable_admins"||$toggle == "disable_admins"){
				$dbprefix = elgg_get_config("dbprefix");
				$options = array(
				        "relationship" => "group_admin",
				        "relationship_guid" => $group->getGUID(),
				        "inverse_relationship" => true,
				        "type" => "user",
				        "limit" => false,
				);
				if($group_admins = elgg_get_entities_from_relationship($options)){
					$owner=$group->getOwnerEntity();
        				$group_admins=array_merge(array($owner),$group_admins);
					if($toggle == "enable_admins"){
						// fix notifications settings for site and email
						$auto_notification_handlers = array(
							"site",
							"email"
						);
						
						// enable notification for everyone
						foreach($group_admins as $group_admin){
							if(in_array($notification_method, $auto_notification_handlers)){
								add_entity_relationship($group_admin->getGUID(), "notify" . $notification_method, $group->getGUID());
							}
						}
						
						system_message(elgg_echo("group_tools:action:admin_notifications:success:enable"));
						$forward_url = $group->getURL();
					} elseif($toggle == "disable_admins"){
						// disable notification for everyone
						foreach($group_admins as $group_admin){
							remove_entity_relationship($group_admin->getGUID(), "notify" . $notification_method, $group->getGUID());
						}
						
						system_message(elgg_echo("group_tools:action:admin_notifications:success:disable"));
						$forward_url = $group->getURL();
					} else {
						register_error(elgg_echo("group_tools:action:admin_notifications:error:toggle"));
					}
				}
			}
		    } else {
			 register_error(elgg_echo('actionunauthorized'));
		    }
		} else {
			register_error(elgg_echo("group_tools:action:error:entity"));
		}
	} else {
		register_error(elgg_echo("group_tools:action:error:input"));
	}
	
	forward($forward_url);
