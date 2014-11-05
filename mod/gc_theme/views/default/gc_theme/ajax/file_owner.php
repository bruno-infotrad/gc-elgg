<?php
/**
 * Individual's or group's files
 *
 * @package ElggFile
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();
$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'file',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
));
if (!$content) {
	$content = elgg_echo("file:none");
}
echo $content;
