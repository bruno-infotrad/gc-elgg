<?php
/**
 * Polls river view.
 */

$object = $vars['item']->getObjectEntity();

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'body_class' => $vars['body_class'],
));
