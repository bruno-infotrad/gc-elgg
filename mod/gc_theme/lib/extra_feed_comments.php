<?php
/**
 * Action called by AJAX periodically to get extra comments
 *
 * @package gc_theme
 */
if (elgg_is_logged_in()) {
	if (!$guid = get_input('guid')) {
                exit;
        }

	$comments = elgg_get_annotations(array(
	        'guid' => $guid,
	        'annotation_name' => 'generic_comment',
	        'limit' => 0,
	        'order_by' => 'n_table.time_created desc'
	));
	
	if ($comments) {
	        $comments = array_reverse($comments);
	        echo elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments', 'item_class' => 'elgg-river-participation', 'body_class' => 'new-feed'));
	
	}
}
