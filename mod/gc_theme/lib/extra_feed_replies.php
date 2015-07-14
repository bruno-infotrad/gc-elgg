<?php
if (elgg_is_logged_in()) {
        if (!$guid = get_input('guid')) {
                exit;
        }
        $discussion_replies = elgg_get_entities(array( 'type' => 'object', 'subtype' => 'discussion_reply', 'container_guid' => $guid, 'limit' => 0, 'order_by' => 'e.time_created desc', 'distinct' => false,));
	if ($discussion_replies) {
		$discussion_replies = array_reverse($discussion_replies);
		echo elgg_view_entity_list($discussion_replies, array('list_class' => 'elgg-river-replies', 'item_class' => 'elgg-river-participation'));
		//echo elgg_view_annotation_list($discussion_replies, array('list_class' => 'elgg-river-replies', 'item_class' => 'elgg-river-participation'));
	
	}
}
