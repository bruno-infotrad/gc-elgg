<?php
$username = get_input('owner');
$user_guid = get_user_by_username($username)->getGUID();
elgg_set_page_owner_guid($user_guid);
$offset = get_input('offset');
$list = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name' => 'toId',
	'metadata_value' => $user_guid,
	'owner_guid' => $user_guid,
	'full_view' => false,
));

$body_vars = array(
	'folder' => 'inbox',
	'list' => $list,
);
echo $list;
