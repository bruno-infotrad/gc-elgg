<?php
/**
 * Displays discussion replies for a discussion river item
 */

$item=$vars['item'];
$topic = $item->getObjectEntity();
if ($topic->getSubtype() !="groupforumtopic") {
	return true;
}
$object=$topic;
$target = $item->getTargetEntity();

$options = array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->guid,
	'count' => true,
	'distinct' => false,
);

$discussion_reply_count = elgg_get_entities($options);

$user=elgg_get_logged_in_user_entity();
$canwrite = true;

$group = get_entity($topic->container_guid);
if (!$group->isMember($user)) {
	$canwrite = false;
}

// annotations and comments do not have responses
if ($item->annotation_id != 0 || !$object || elgg_instanceof($target, 'object', 'discussion_reply')) {
	return true;
}

if ($discussion_reply_count) {
	$object_guid = $topic->getGUID();
	$discussion_replies = elgg_get_entities(array( 'type' => 'object', 'subtype' => 'discussion_reply', 'container_guid' => $object_guid, 'limit' => 3, 'order_by' => 'e.time_created desc', 'distinct' => false,));
	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$discussion_replies = array_reverse($discussion_replies);

	if ($discussion_reply_count > count($discussion_replies)) {
		elgg_load_js('elgg.extra_feed_discussion_replies');
		$link = elgg_view('output/url', array(
			'href' => $object->getURL(),
			'onclick' => "elgg.extra_feed_discussion_replies(\"$object_guid\");return false;",
			'text' => elgg_echo('river:discussion_replies:all', array($discussion_reply_count)),
		));
		
		echo elgg_view_image_block(elgg_view_icon('speech-bubble-alt'), $link, array('class' => 'elgg-river-participation-'.$object_guid));
	}
	
	echo elgg_view_entity_list($discussion_replies, array('list_class' => 'elgg-river-comments-'.$object_guid, 'item_class' => 'elgg-river-participation', 'body_class' => $vars['body_class']));

}

if ($object->canAnnotate(0, 'group_topic_post')) {
	// inline comment form
?>
<?php
	//if ($canwrite) {
		$id = "groups-reply-{$object_guid}";
		echo elgg_view_form('discussion/reply/save', array(
			'id' => $id,
			'class' => 'elgg-river-participation elgg-form-small elgg-form-variable',
		), array('entity' => $topic, 'inline' => true, 'id' => $id, 'canwrite' => $canwrite));
	//} else {
		//echo elgg_view_form('comments/add', array(
			//'id' => $id,
			//'class' => 'elgg-river-participation elgg-form-small',
		//), array('entity' => $object, 'inline' => true, 'id' => $id, 'canwrite' => $canwrite));
	//}
}
