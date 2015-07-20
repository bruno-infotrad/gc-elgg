<?php
$username = get_input('owner');
$user_guid = get_user_by_username($username)->getGUID();
elgg_set_page_owner_guid($user_guid);
$offset = get_input('offset');
$list = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $user_guid,
	'full_view' => false,
	'metadata_name' => 'read',
	'metadata_value' => false,
));
echo $list;
