<?php
if (elgg_is_logged_in()) {
        if (!$guid = get_input('guid')) {
                exit;
        }
	$replies_options = array(
		'guid' => $guid,
		'annotation_name' => 'group_topic_post',
	        'limit' => 0,
		'order_by' => 'n_table.time_created desc'
	);
	
	$replies = elgg_get_annotations($replies_options);
	
	if ($replies) {
		$replies = array_reverse($replies);
		echo elgg_view_annotation_list($replies, array('list_class' => 'elgg-river-replies', 'item_class' => 'elgg-river-participation'));
	
	}
}
