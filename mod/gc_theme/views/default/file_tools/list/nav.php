<?php
/**
 * Members navigation
 */

$owner = elgg_get_page_owner_entity();
if (elgg_instanceof($owner, 'user')) {
	$url = "file/owner/".$owner->username;
} elseif (elgg_instanceof($owner, 'group')) {
	$url = "file/group/".$owner->getGUID();
}
$direction = elgg_extract('direction',$vars);
$selected = $vars['selected'];
if ($direction == 'asc') {
	if ($selected == 'name') {
		$name_arrow = ' <div class="arrow-up"></div>';
	} elseif ($selected == 'type') {
		$type_arrow = ' <div class="arrow-up"></div>';
	} elseif ($selected == 'time_created') {
		$time_created_arrow = ' <div class="arrow-up"></div>';
	}
} else {
	if ($selected == 'name') {
		$name_arrow = ' <div class="arrow-down"></div>';
	} elseif ($selected == 'type') {
		$type_arrow = ' <div class="arrow-down"></div>';
	} elseif ($selected == 'time_created') {
		$time_created_arrow = ' <div class="arrow-down"></div>';
	}
}
$tabs = array(
	'name' => array(
		'title' => elgg_echo('file_tools:file_name').$name_arrow,
		'url' => $url."?sort_by=oe.title&direction=$direction",
		'selected' => $selected == 'name',
	),
	'type' => array(
		'title' => elgg_echo('file_tools:file_type').$type_arrow,
		'url' => $url."?sort_by=simpletype&direction=$direction",
		'selected' => $selected == 'type',
	),
	'time_created' => array(
		'title' => elgg_echo('file_tools:file_date_created').$time_created_arrow,
		'url' => $url."?sort_by=e.time_created&direction=$direction",
		'selected' => $selected == 'time_created',
	),
);
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
