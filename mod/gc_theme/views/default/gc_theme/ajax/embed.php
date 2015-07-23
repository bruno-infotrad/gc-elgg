<?php
$user = elgg_get_logged_in_user_entity();
//elgg_set_page_owner_guid($user->guid);
$options = array();
$offset = get_input('offset');
$options['type'] = 'object';
$options['subtype'] = 'file';
$options['offset'] = $offset;
$options['limit'] = 6;
$base_url = get_input('base_url');
$options['base_url'] = $base_url;
$container_guid = get_input('group_guid');
if ($container_guid) {
	$options['container_guids'] = array(elgg_get_logged_in_user_guid(),$container_guid);
} else {
	$options['container_guids'] = array(elgg_get_logged_in_user_guid());
}
$options['item_class'] = 'embed-item';
$content = elgg_list_entities(
	$options,
	'elgg_get_entities',
	'embed_list_items'
);
echo $content;
