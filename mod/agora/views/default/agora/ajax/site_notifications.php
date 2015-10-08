<?php
$username = get_input('owner');
$base_url = get_input('base_url');
$user_guid = get_user_by_username($username)->getGUID();
elgg_set_page_owner_guid($user_guid);
$offset = get_input('offset');
$list = elgg_list_entities_from_metadata(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $user_guid,
	'full_view' => false,
	'metadata_name' => 'read',
	'metadata_value' => false,
));
echo $list;
