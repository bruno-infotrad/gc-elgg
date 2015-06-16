<?php
/**
 * Elgg add multiple groups action
 */

gatekeeper();
$groups_guids = get_input("group_guid");
if (!empty($groups_guids) && !is_array($groups_guids)){
	$groups_guids = array($groups_guids);
}

// Get the GUID of the user to friend
if(!empty($groups_guids)){
	$user = elgg_get_logged_in_user_entity();
	foreach ($groups_guids as $group_guid) {
		$group = get_entity($group_guid);
		$errors = false;
		if (($user instanceof ElggUser) && ($group instanceof ElggGroup)) {
			// join or request
			$join = false;
			if ($group->isPublicMembership() || $group->canEdit($user->guid)) {
				// anyone can join public groups and admins can join any group
				$join = true;
			}
			if ($join) {
				if (groups_join_group($group, $user)) {
					//system_message(elgg_echo("groups:joined"));
				} else {
					$errors = true;
					register_error(elgg_echo("groups:cantjoin"));
				}
			} else {
				add_entity_relationship($user->guid, 'membership_request', $group->guid);
                		// Notify group owner
				$url = elgg_get_config('wwwroot') . "groups/requests/$group->guid";
				$subject = elgg_echo('groups:request:subject', array(
					$user->name,
					$group->name,
				));
				$body = elgg_echo('groups:request:body', array(
					$group->getOwnerEntity()->name,
					$user->name,
					$group->name,
					$user->getURL(),
					$url,
				));
				if (notify_user($group->owner_guid, $user->getGUID(), $subject, $body)) {
					//system_message(elgg_echo("groups:joinrequestmade"));
				} else {
					register_error(elgg_echo("groups:joinrequestnotmade"));
					$errors = true;
				}
			}
		} else {
			register_error(elgg_echo("groups:cantjoin"));
		}
	}
	if (! $errors) {
		system_message(elgg_echo("gc_theme:intro_join_groups"));
	}
}
// Forward back to the page you friended the user on
forward('/dashboard?page_type=intro');
