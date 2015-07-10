<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 */

$item = $vars['item'];

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();
$target = $item->getTargetEntity();

$icon = elgg_view_entity_icon($subject, 'small');

$name = preg_replace('/ -.+/','',$subject->name);
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	//'text' => $subject->name,
	'text' => $name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));


$action = $item->action_type;
$type = $item->type;
$subtype = $item->subtype ? $item->subtype : 'default';
if ($subtype == 'thewire' ) {
	$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => elgg_echo('thewire'), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$content = elgg_echo("river:$action:$type:$subtype", array($subject_link, $object_link));
} elseif ($subtype == 'event' && $action == 'event_relationship') {
	$user = get_entity($vars['item']->subject_guid);
        $event = get_entity($vars['item']->object_guid);
	$subject_link = elgg_view('output/url', array(
		'href' => $user->getURL(),
		'text' => $user->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	));
        $event_url = "<a href=\"" . $event->getURL() . "\">" . $event->title . "</a>";
        $relationtype = $event->getRelationshipByUser($user->getGUID());
        $content = elgg_echo("event_manager:river:event_relationship:create:" . $relationtype, array($subject_link, $event_url));
} elseif ($subtype == 'comment') {
	if ($target->getSubtype() == 'thewire') {
		$object_text = elgg_echo('thewire');
	} else {
		$object_text = $target->title ? $target->title : $target->name;
	}
	$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => elgg_get_excerpt($object_text, 100), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$key = "river:$action:$type:$subtype";
	$content = elgg_echo($key, array($subject_link, $object_link));

	if ($content == $key) {
        	$key = "river:$action:$type:default";
        	$content = elgg_echo($key, array($subject_link, $object_link));
	}
} elseif ($subtype == 'discussion_reply') {
	$object_text = $target->title ? $target->title : $target->name;
	$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => elgg_get_excerpt($object_text, 100), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$content = elgg_echo('river:reply:object:groupforumtopic', array($subject_link, $object_link));
} else {
	$object_text = $object->title ? $object->title : $object->name;
	//$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => elgg_get_excerpt($object_text, 100), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => $object_text, 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$key = "river:$action:$type:$subtype";
	$content = elgg_echo($key, array($subject_link, $object_link));

	if ($content == $key) {
        	$key = "river:$action:$type:default";
        	$content = elgg_echo($key, array($subject_link, $object_link));
	}
}
echo elgg_view_image_block($icon, $content,array('body_class' => 'sidebar-activity-item'));
