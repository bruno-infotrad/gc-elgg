<?php
/**
 * Group forum topic create river view.
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();
//$excerpt = strip_tags($object->description);
$excerpt = $object->description;
//$excerpt = elgg_get_excerpt($excerpt);
$excerpt_with_embed= elgg_view('output/longtext', array(
                        'value' => $excerpt,
                ));
$responses = '';
//NEED TO FIGURE THIS ONE OUT AS THIS SHOULD WORK. FOR NOW COMMENT OUT AS IT SEEMS TO HAVE NOT SIDE EFFECTS
//if (elgg_is_logged_in() && $object->canWriteToContainer()) {
	$responses = elgg_view('river/elements/discussion_replies', array('topic' => $object));
//}

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt_with_embed,
	'responses' => $responses,
));
