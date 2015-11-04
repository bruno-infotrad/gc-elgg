<?php
/**
 * Friends collection edit action
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_id = get_input('collection_id');
$name = get_input('name');

// check it exists and we can edit
if (!can_edit_access_collection($collection_id)) {
	system_message(elgg_echo('friends:collection:edit_failed'));
}

if (rename_access_collection($collection_id, $name)) {
	system_message(elgg_echo('friends:collection:renamed'));
} else {
	system_message(elgg_echo('friends:collection:rename_failed'));
}
