<?php 
/**
 * Group entity view
 * 
 * @package ElggGroups
 */

$group = $vars['entity'];

$icon = elgg_view_entity_icon($group, 'medium',array('width' => 80, 'height' => 80));

$metadata = elgg_view_menu('entity', array(
	'entity' => $group,
	'handler' => 'groups',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if ((elgg_in_context('owner_block') || elgg_in_context('widgets')) && !elgg_in_context("widgets_groups_show_members")) {
	$metadata = '';
}

if ($vars['full_view']) {
	if ($vars['full_view']=='gc_summary') {
		$params = array(
			'entity' => $group,
			'metadata' => $metadata,
			'subtitle' => $group->briefdescription,
		);
		if (! $group->isPublicMembership()) {
			$vars['class'] = $vars['class'].' is-locked';
		}
		$params = $params + $vars;
		$list_body = elgg_view('group/elements/gc_summary', $params);

		echo elgg_view_image_block($icon, $list_body, $vars);
	} elseif ($vars['full_view']=='gc_list_summary') {
		$params = array(
			'entity' => $group,
			'metadata' => $metadata,
			'subtitle' => $group->briefdescription,
		);
		$params = $params + $vars;
		echo elgg_view('group/elements/gc_list_summary', $params);

	} else {
		echo elgg_view('groups/profile/summary', $vars);
	}
} else {
	// brief view
	$params = array(
		'entity' => $group,
		'metadata' => $metadata,
		'subtitle' => $group->briefdescription,
	);
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body, $vars);
}
