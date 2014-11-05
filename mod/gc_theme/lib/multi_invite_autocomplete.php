<?php 
	global $CONFIG;

	$q = sanitize_string(get_input("q"));
	$current_users = sanitize_string(get_input("user_guids"));
	$limit = (int) get_input("limit", 50);
	$relationship = sanitize_string(get_input("relationship", "none"));
	
	$include_self = get_input("include_self", false);
	if(!empty($include_self)){
		$include_self = true;
	}
	
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
			
			if(!$include_self){
				if(empty($current_users)){
					$current_users = $user->getGUID();
				} else {
					$current_users .= "," . $user->getGUID();
				}
			}
			
			if(!empty($current_users)){
				$query_options["wheres"][] = "e.guid NOT IN (" . $current_users . ")";
			}
			
			$query_options["relationship"] = "member_of_site";
			$query_options["relationship_guid"] = elgg_get_site_entity()->getGUID();
			$query_options["inverse_relationship"] = true;
			
			if($entities = elgg_get_entities_from_relationship($query_options)){
				if($relationship == "notfriends"){
					foreach($entities as $entity){
						if(!check_entity_relationship($user->getGUID(), "friend", $entity->getGUID())){
							$result[] = array("type" => "user", "value" => $entity->getGUID(),"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name, "name" => $entity->name);
						}	
					}
				} else {
					foreach($entities as $entity){
						$result[] = array("type" => "user", "value" => $entity->getGUID(),"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name, "name" => $entity->name);
					}
				}
			}
		}
		
		// restore hidden users
		access_show_hidden_entities($hidden);
	}
	
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	
	exit();
