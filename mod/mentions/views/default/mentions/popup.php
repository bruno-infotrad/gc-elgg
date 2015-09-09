<?php

//elgg_log('VAR_EXPORT '.var_export($vars,true),'NOTICE');
$id = 'mentions-popup';
$entity_guid = elgg_extract('entity_guid',$vars,'');
if ($entity_guid) {
	$id = $id.'-'.$entity_guid;
}
$vars = array(
	'class' => 'mentions-popup hidden',
	'id' => $id,
);

echo elgg_view_module('popup', '', elgg_view('graphics/ajax_loader', array('hidden' => false)), $vars);

