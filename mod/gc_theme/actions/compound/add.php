<?php
/**
 * Action for adding either a wire post
 * or a file object 
 */

// don't filter since we strip and filter escapes some characters
$user_id = elgg_get_logged_in_user_guid();
$access_id = ACCESS_LOGGED_IN;
$container_guid = get_input('container_guid', 0);
$exec_content = get_input('exec_content');
//Check for multiple container
if (preg_match('/,/',$container_guid)) {
	$mv_container_guid = true;
	$container_guids = preg_split('/,/',$container_guid);
}
$parent_guid = (int) get_input('parent_guid');
$guid = get_input('guid',0);
if (preg_match('/,/',$guid)) {
	$mv_guid = true;
	$guids = preg_split('/,/',$guid);
}
elgg_log("BRUNO compound/add=".$container_guid,'NOTICE');
elgg_log("BRUNO compound/add=".$guid,'NOTICE');
if ($container_guid) {
	if ($mv_container_guid) {
		foreach($container_guids as $container) {
			$gguid=get_entity($container);
			elgg_log("multiple BRUNO compound/add=".$gguid->membership,'NOTICE');
			if ($gguid instanceof ElggGroup && $gguid->membership == ACCESS_PRIVATE) {
				$gac=get_data_row("SELECT id FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid='$container'");
				elgg_log("multiple BRUNO compound/add group_access_collections ".$gac->id,'NOTICE');
				$access_ids[$container]=$gac->id;
			} else {
				$access_ids[$container] = ACCESS_LOGGED_IN;
			}
		}
	} else {
		$gguid=get_entity($container_guid);
		elgg_log("BRUNO compound/add=".$gguid->membership,'NOTICE');
		if ($gguid instanceof ElggGroup && $gguid->membership == ACCESS_PRIVATE) {
			$gac=get_data_row("SELECT id FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid='$container_guid'");
			elgg_log("BRUNO compound/add group_access_collections ".$gac->id,'NOTICE');
			$access_id=$gac->id;
		} else {
			$access_id = ACCESS_LOGGED_IN;
		}
	}
}

$body = get_input('body', '', false);
$jeditable = false;
$river_guid = get_input('river_guid',0);
if (empty($body)) {
	$body = get_input('value', '', false);
	$guid = get_input('guid');
	//$container_guid = get_input('container_guid');
	if (! $guid || ! $container_guid || ! $river_guid) {
		register_error(elgg_echo("thewire:missing_guids"));
		forward(REFERER);
	}
	$jeditable = true;
}
elgg_log("BRUNO compound/add jeditable=$jeditable",'NOTICE');
$method = 'site';
// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
	forward(REFERER);
}
if ($container_guid == 0) {
	$container_guid = $user_id;
}
if ($mv_container_guid) {
	$i = 0;
	foreach($container_guids as $container) {
		elgg_log("BRUNO compound/add multiple post container_guid=$container guid=$guids[$i]",'NOTICE');
		if ($guid == 0) {
			elgg_log("BRUNO compound/add multiple post new post in container_guid=$container",'NOTICE');
			$guid = thebetterwire_save_post($guid, $body, $user_id, $container, $access_ids[$container], $parent_guid, $method,$exec_content,false,0);
			if (!$guid) {
				register_error(elgg_echo("thewire:error"));
				elgg_log("BRUNO compound/add ERROR multiple post new post in container_guid=$container",'NOTICE');
				forward(REFERER);
			}
			// Reset guid because of jeditable
			$guid = 0;
		} else {
			elgg_log("BRUNO compound/add multiple post jeditable edited post in container_guid=$container guid=$guids[$i]",'NOTICE');
			$guid = thebetterwire_save_post($guids[$i], $body, $user_id, $container, $access_ids[$container], $parent_guid, $method,$exec_content,$jeditable,$river_guid);
			if (!$guid) {
				elgg_log("BRUNO compound/add ERROR multiple post jeditable edited post in container_guid=$container guid=$guids[$i]",'NOTICE');
				register_error(elgg_echo("thewire:error"));
				forward(REFERER);
			}
			$i++;
		}
	}
} else {
	elgg_log("BRUNO compound/add single post jeditable edited post in container_guid=$container guid=$guid",'NOTICE');
	$guid = thebetterwire_save_post($guid, $body, $user_id, $container_guid, $access_id, $parent_guid, $method,$exec_content,$jeditable,$river_guid);
	//$thewire_entity = get_entity($guid);
	//$thewire_entity->save();
	if (!$guid) {
		elgg_log("BRUNO compound/add ERROR single post jeditable edited post in container_guid=$container guid=$guid",'NOTICE');
		register_error(elgg_echo("thewire:error"));
		forward(REFERER);
	}
}
// Send response to original poster if not already registered to receive notification
if ($parent_guid) {
	thewire_send_response_notification($guid, $parent_guid, $user);
	$parent = get_entity($parent_guid);
	forward("thewire/thread/$parent->wire_thread");
}

if ($jeditable) {
	echo $body;
} else {
	system_message(elgg_echo("thewire:posted"));
	if (preg_match ('/\/group.+/',$_SERVER['HTTP_REFERER'])) {
		forward(REFERER);
	} else {
		forward('/dashboard?page_type=all');
	
	}
}

/**
 * Override wire post to remove 200 character limit
 * @param string $text        The post text
 * @param int    $userid      The user's guid
 * @param int    $access_id   Public/private etc
 * @param int    $parent_guid Parent post guid (if any)
 * @param string $method      The method (default: 'site')
 * @return guid or false if failure
 */
function thebetterwire_save_post($guid = 0,$text, $userid, $container_guid, $access_id, $parent_guid = 0, $method = "site",$exec_content, $is_jeditable = false, $river_guid = 0) {
	if ($guid == 0) {
	//New post
        	$post = new ElggObject();
        	$post->subtype = "thewire";
        	$post->owner_guid = $userid;
        	$post->container_guid = $container_guid;
        	$post->access_id = $access_id;
		if ($exec_content == 'true') {
        		$post->exec_content = $exec_content;
		} else {
        		$post->exec_content = false;
		}
        	//Hack to allow for line break. First convert to crazy string
        	$text = preg_replace('/\n/',':br:',$text);

        	// no html tags allowed so we escape
        	$post->description = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        	//Hack to allow for line break. now convert back to br
        	$post->description = preg_replace('/:br:/','<br>',$post->description);

        	$post->method = $method; //method: site, email, api, ...

        	$tags = thewire_get_hashtags($text);
        	if ($tags) {
        	        $post->tags = $tags;
        	}

        	// must do this before saving so notifications pick up that this is a reply
        	if ($parent_guid) {
        	        $post->reply = true;
        	}

        	$guid = $post->save();

        	// set thread guid
        	if ($parent_guid) {
        	        $post->addRelationship($parent_guid, 'parent');

        	        // name conversation threads by guid of first post (works even if first post deleted)
        	        $parent_post = get_entity($parent_guid);
        	        $post->wire_thread = $parent_post->wire_thread;
        	} else {
        	        // first post in this thread
        	        $post->wire_thread = $guid;
        	}

        	if ($guid) {
        	        add_to_river('river/object/thewire/create', 'create', $post->owner_guid, $post->guid);

        	        // let other plugins know we are setting a user status
        	        $params = array(
        	                'entity' => $post,
        	                'user' => $post->getOwnerEntity(),
        	                'message' => $post->description,
        	                'url' => $post->getURL(),
        	                'origin' => 'thewire',
        	        );
        	        elgg_trigger_plugin_hook('status', 'user', $params);
        	}
        } else {
	// Edit existing post
		$db_prefix = elgg_get_config('dbprefix');
		$post = get_entity($guid);
		if ($exec_content) {
        		$post->exec_content = $exec_content;
		}
        	//Hack to allow for line break. First convert to crazy string
        	//$text = preg_replace('/\n/',':br:',$text);

        	// no html tags allowed so we escape
        	//$post->description = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        	//Hack to allow for line break. now convert back to br
        	//$post->description = preg_replace('/:br:/','<br>',$post->description);
		$post->description = $text;
        	$tags = thewire_get_hashtags($text);
        	if ($tags) {
        	        $post->tags = $tags;
        	}
		$time_updated = time();
		$post->time_updated = $time_updated;
        	$guid = $post->save();
		/*
		elgg_log("BRUNO JEDITABLE TEST ".$river_guid,'NOTICE');
		if ($guid) {
			$river_items = elgg_get_river(array('id' => $river_guid));
			elgg_log("BRUNO JEDITABLE RIVER ".var_export($river_items,true),'NOTICE');
			$sql_query = "UPDATE {$db_prefix}river SET  posted  = '{$time_updated}' WHERE id = {$river_guid};";
                	$result = update_data($sql_query);
			elgg_log("BRUNO JEDITABLE AFTER ".$river_items[0]->id." ".$river_items[0]->posted,'NOTICE');
		}
		*/
	}
        return $guid;
}
