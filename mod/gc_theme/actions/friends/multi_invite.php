<?php
/**
 * Elgg add multiple friends action
 */

gatekeeper();
$forward_url = get_input("forward_url");
$user_guids = get_input("user_guid");
if (!empty($user_guids) && !is_array($user_guids)){
	$user_guids = array($user_guids);
}

// Get the GUID of the user to friend
if(!empty($user_guids)){
	foreach ($user_guids as $friend_guid) {
		$friend = get_entity($friend_guid);
		$errors = false;
		try {
			if (!elgg_get_logged_in_user_entity()->addFriend($friend_guid)) {
				$errors = true;
			}
		} catch (Exception $e) {
			register_error(elgg_echo("friends:add:failure", array($friend->name)));
			$errors = true;
		}
		if (!$errors) {
			// add to river
			add_to_river('river/relationship/friend/create', 'friend', elgg_get_logged_in_user_guid(), $friend_guid);
			system_message(elgg_echo("friends:add:successful", array($friend->name)));
		}
	}
}

// Forward back to the page you friended the user on
forward($forward_url);
