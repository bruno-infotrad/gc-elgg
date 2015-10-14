<?php
/**
 * List replies with optional add form
 *
 * @uses $vars['entity']        ElggEntity the group discission
 * @uses $vars['show_add_form'] Display add form or not
 */

$topic_guid = get_input('owner');
$base_url = get_input('base_url');
echo '<div id="group-replies" class="elgg-comments">';
$replies = elgg_list_entities(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic_guid,
	'reverse_order_by' => true,
	'distinct' => false,
));
echo $replies;
echo '</div>';
