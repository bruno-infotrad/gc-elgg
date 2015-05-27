<?php
/**
 * File river view.
 */

$object = $vars['item']->getObjectEntity();
$container_guid = $object->container_guid;
$excerpt = strip_tags($object->description,'<br>');
$excerpt = thebetterwire_filter($excerpt);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
//change display to remove extra info if not in group
if (elgg_instanceof($object->getContainerEntity(), 'group')) {
	$object_link = elgg_view('output/url', array( 'href' => "thewire_group/group/$container_guid/all", 'text' => elgg_echo('thewire:wire'), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$summary = elgg_echo("river:create:object:thewire", array($subject_link, $object_link));
} else {
	$object_link = elgg_view('output/url', array( 'href' => "thewire/owner/$subject->username", 'text' => elgg_echo('thewire:wire'), 'class' => 'elgg-river-object', 'is_trusted' => true,));
	$summary = elgg_echo("river:create:object:thewire", array($subject_link, $object_link));
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'summary' => $summary,
	'body_class' => $vars['body_class'],
	'skip' => $vars['skip']
));
