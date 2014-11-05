<?php
/**
 * Elgg delete like action
 *
 */
/*
$likes = elgg_get_annotations(array(
	'guid' => (int) get_input('guid'),
	'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
	'annotation_name' => 'likes',
));
if ($likes) {
	if ($likes[0]->canEdit()) {
		$likes[0]->delete();
		system_message(elgg_echo("likes:deleted"));
		forward(REFERER);
	}
}

register_error(elgg_echo("likes:notdeleted"));
forward(REFERER);
*/


// Ensure we're logged in
if (!elgg_is_logged_in()) {
        forward();
}

// Make sure we can get the comment in question
$annotation_id = (int) get_input('annotation_id');
if ($likes = elgg_get_annotation_from_id($annotation_id)) {
	elgg_log("BRUNO likes:delete likes".var_export($likes,true),'NOTICE');

        if ($likes->canEdit()) {
                $likes->delete();
                system_message(elgg_echo("generic_comment:deleted"));
                forward('dashboard');
        }

} else {
        $url = "";
}

register_error(elgg_echo("generic_comment:notdeleted"));
forward(REFERER);

