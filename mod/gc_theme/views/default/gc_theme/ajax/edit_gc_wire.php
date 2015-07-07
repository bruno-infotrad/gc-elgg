<?php
/**
 * Elgg ajax edit comment form
 *
 * @package Elgg
 * @subpackage Core
 */

$river_guid = get_input('guid');
$river_items = elgg_get_river(array('id' => $river_guid));
$thewire = $river_items[0]->getObjectEntity();
$container_guid = $thewire->getContainerGUID();

$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "elgg-form-gc_wire-save-{$river_guid}",
);
$body_vars = array('guid' => $thewire->getGuid(),'container_guid' => $container_guid, 'river_guid' => $river_guid, 'thewire' => $thewire);
echo '<div>'.elgg_view_form('compound/add', $form_vars, $body_vars).'</div>';
