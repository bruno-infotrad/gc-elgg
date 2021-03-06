<?php 

	$group_guid = (int) get_input("group_guid", 0);
	$user_guids = get_input("user_guids");
	$subject = get_input("title");
	$body = get_input("description");
	
	$forward_url = REFERER;
	$user_guids = group_tools_verify_group_members($group_guid, $user_guids);
	
	if(!empty($group_guid) && !empty($body) && !empty($user_guids)){
		
		if(($group = get_entity($group_guid)) && ($group instanceof ElggGroup)){
			if($group->canEdit()){
				set_time_limit(0);
				
				$subject = elgg_echo("group_tools:mail:message:from") . ": " . $group->name . " - ".$subject; 
				$body = elgg_echo("group_tools:mail:message:from") . ": " . $group->name . " [" . $group->getURL() . "]".PHP_EOL . PHP_EOL.$body; 
					
				foreach($user_guids as $guid){
					notify_user($guid, $group->getGUID(), $subject, $body, NULL, "email"); 
				}
				
				system_message(elgg_echo("group_tools:action:mail:success"));
				
				$forward_url = $group->getURL();
			} else {
				register_error(elgg_echo("group_tools:action:error:edit"));
			}
		} else {
			register_error(elgg_echo("group_tools:action:error:entity"));
		}
	} else {
		register_error(elgg_echo("group_tools:action:error:input"));
	}
	
	forward($forward_url);
	
