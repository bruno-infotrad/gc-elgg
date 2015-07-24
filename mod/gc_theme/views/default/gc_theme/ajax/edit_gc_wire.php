<?php
/**
 * Elgg ajax edit comment form
 *
 * @package Elgg
 * @subpackage Core
 */

$river_guid = get_input('guid');
$guid_string = get_input('guid_string');
if ($guid_string) {
	$river_guids = explode('_',$guid_string);
	$river_guid = '';
	foreach ($river_guids as $river_guid_tmp) {
		$river_guid = (empty($river_guid) ? $river_guid_tmp : $river_guid.','.$river_guid_tmp);
		$river_items = elgg_get_river(array('id' => $river_guid_tmp));
		$thewire = $river_items[0]->getObjectEntity();
		$guid = (empty($guid) ? $thewire->getGuid() : $guid.','.$thewire->getGuid());
		$container_guid = (empty($container_guid) ? $thewire->getContainerGuid() : $container_guid.','.$thewire->getContainerGuid());
	}
} else {
	$river_items = elgg_get_river(array('id' => $river_guid));
	$thewire = $river_items[0]->getObjectEntity();
	$guid = $thewire->getGuid();
	$container_guid = $thewire->getContainerGUID();
}
$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "elgg-form-gc_wire-save-{$river_guid}",
);
$body_vars = array('guid' => $guid,'container_guid' => $container_guid, 'river_guid' => $river_guid, 'thewire' => $thewire);
echo '<div>'.elgg_view_form('compound/add', $form_vars, $body_vars).'</div>';
