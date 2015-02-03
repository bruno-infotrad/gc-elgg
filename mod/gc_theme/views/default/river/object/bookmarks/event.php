<?php
/**
 * New bookmarks river entry
 *
 * @package Bookmarks
 */

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);
$event_label = elgg_echo('event_manager:event:view:event');

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $event_label .' '.$excerpt,
	'attachments' => elgg_view('output/url', array('href' => $object->address)),
));
