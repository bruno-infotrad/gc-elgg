<?php
$current_user = elgg_get_logged_in_user_entity();
$user_guid = (int) get_input('user_guid', 0);
$group_guid = (int) get_input('group_guid', 0);
$method = get_input('method');
$status = get_input('status');
if (!$user_guid || !$group_guid || !$method || !$status) {
	exit();
}
if (($user_guid != $current_user->guid) && !$current_user->isAdmin()) {
	exit();
}
$user = get_entity($user_guid);
$group = get_entity($group_guid);
if (!$group->isMember($user)) {
	system_message(elgg_echo('membershiprequired'));
	exit();
}
$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach ($NOTIFICATION_HANDLERS as $defined_method => $foo) {
	if ($method == $defined_method) {
		if ($status == 'on') {
			elgg_add_subscription($user_guid, $method, $group_guid);
		} else {
			elgg_remove_subscription($user_guid, $method, $group_guid);
		}
	}
}
exit();
