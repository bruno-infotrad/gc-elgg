<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 */

$item = $vars['item'];

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();
$target = $object->getContainerEntity();
$icon = elgg_view_entity_icon($subject, 'small');

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
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
} else {
	$object_link = elgg_view('output/url', array( 'href' => $object->getURL(), 'text' => $object->title ? $object->title : $object->name, 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$content = elgg_echo("river:$action:$type:$subtype", array($subject_link, $object_link));
}
/*
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup) {
	$params = array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	);
	$group_link = elgg_view('output/url', $params);
	$group_string = elgg_echo('river:ingroup', array($group_link));
}
$view = preg_replace('/\//',':',$item->view);
*/


echo elgg_view_image_block($icon, $content,array('body_class' => 'sidebar-activity-item'));
