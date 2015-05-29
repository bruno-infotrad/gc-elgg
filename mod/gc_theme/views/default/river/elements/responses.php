<?php
/**
 * River item footer
 */

$item = $vars['item'];
$user=elgg_get_logged_in_user_entity();
elgg_log("BRUNO response user ".var_export($user,true),"NOTICE");
$object = $item->getObjectEntity();
elgg_log("BRUNO response object entity ".var_export($object,true),"NOTICE");
$canwrite = true;
if ($object->owner_guid != $object->container_guid) {
	$group = get_entity($object->container_guid);
	if ($group instanceof ElggGroup && !$group->isMember($user)) {
		$canwrite = false;
	}
}
elgg_log("BRUNO response canwrite ".$canwrite,"NOTICE");

// annotations do not have comments
if (!$object || $item->annotation_id) {
	return true;
}


$comment_count = $object->countComments();

$comments = elgg_get_annotations(array(
	'guid' => $object->getGUID(),
	'annotation_name' => 'generic_comment',
	'limit' => 3,
	'order_by' => 'n_table.time_created desc'
));


if ($comments) {
	$object_guid = $object->getGUID();
	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	if ($comment_count > count($comments)) {
		elgg_load_js('elgg.extra_feed_comments');
		$link = elgg_view('output/url', array(
			'href' => $object->getURL(),
			//'onclick' => "elgg.extra_feed_comments(\"$object_guid\");return false;",
			'id' => 'extra-feed-comments-'.$object_guid,
			'text' => elgg_echo('river:comments:all', array($comment_count)),
		));
		
		echo elgg_view_image_block(elgg_view_icon('speech-bubble-alt'), $link, array('class' => 'elgg-river-participation-'.$object_guid));
	}
	
	echo elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments-'.$object_guid, 'item_class' => 'elgg-river-participation', 'body_class' => $vars['body_class']));
}

if ($object->canAnnotate(0, 'generic_comment')) {
	if (! $vars['skip']||($vars['skip'] && $comment_count)) {
	$id = "comments-add-{$object->getGUID()}";
		echo elgg_view_form('comments/add', array(
			'id' => $id,
			'class' => 'elgg-river-participation elgg-form-small elgg-form-variable',
		), array('entity' => $object, 'inline' => true, 'id' => $id, 'canwrite' => $canwrite));
	}
}
