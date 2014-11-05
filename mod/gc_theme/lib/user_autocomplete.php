<?php 
	global $CONFIG;

	$q = sanitize_string(get_input("q"));
	$limit = (int) get_input("limit", 10);
	
	$result = array();
	
	if(($user = elgg_get_logged_in_user_entity()) && !empty($q)){
		// show hidden (unvalidated) users
		$hidden = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		if($relationship = "notfriends"){
			$dbprefix = elgg_get_config("dbprefix");
			
			// find existing users
			$query_options = array(
				"type" => "user",
				"limit" => $limit,
				"joins" => array("JOIN {$dbprefix}users_entity u ON e.guid = u.guid"),
				"wheres" => array("(u.name LIKE '%{$q}%' OR u.username LIKE '%{$q}%')", "u.banned = 'no'"),
				"order_by" => "u.name asc"
			);
			
			if($entities = elgg_get_entities($query_options)){
				foreach($entities as $entity){
					$result[] = array("type" => "user", "value" => $entity->username,"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name, "name" => $entity->name);
				}
			}
		}
		
		// restore hidden users
		access_show_hidden_entities($hidden);
	}
	
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	
	exit();
