<?php
/**
 * Elgg ajax edit group forum topic
 *
 * @package Elgg
 * @subpackage Core
 */

$river_guid = get_input('guid');
$river_items = elgg_get_river(array('id' => $river_guid));
$gft = $river_items[0]->getObjectEntity();
$container_guid = $gft->getContainerGUID();

$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "elgg-form-gc_gft-save-{$river_guid}",
);
$body_vars = array('guid' => $gft->getGuid(),'container_guid' => $container_guid, 'river_guid' => $river_guid, 'gft' => $gft);
echo '<div>'.elgg_view_form('discussion/save', $form_vars, $body_vars).'</div>';
