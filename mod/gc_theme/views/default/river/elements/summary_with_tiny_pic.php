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
	$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => elgg_echo('thewire'),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));
} else {
	$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title ? $object->title : $object->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));
}

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


$content = elgg_echo("river:$action:$type:$subtype", array($subject_link, $object_link));
echo elgg_view_image_block($icon, $content,array('body_class' => 'sidebar-activity-item'));
