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
$olddirection = $direction = elgg_extract('direction',$vars);
$switched = elgg_extract('switched',$vars);
$folder_guid = elgg_extract('folder_guid',$vars);
$selected = $vars['selected'];
if ($direction == 'asc') {
	$arrow = ' <div class="arrow-up"></div>';
} else {
	$arrow = ' <div class="arrow-down"></div>';
}
if ($selected == 'name') {
	$name_arrow = $arrow;
} elseif ($selected == 'type') {
	$type_arrow = $arrow;
} elseif ($selected == 'time_created') {
	$time_created_arrow = $arrow;
}
$folder_anchor = "";
if ($folder_guid) {
	$folder_anchor = "#$folder_guid";
//Only do this because it is a bad idea to use local anchors (#) as folder GUIDs
	if (! $switched) {
		if ($olddirection == 'asc') {
			$direction = 'desc';
		} elseif ($olddirection == 'desc') {
			$direction = 'asc';
		}
	}
}

$tabs = array(
	'name' => array(
		'title' => elgg_echo('file_tools:file_name').$name_arrow,
		'url' => $url."?folder_guid=$folder_guid&sort_by=oe.title&direction=$direction$folder_anchor",
		'selected' => $selected == 'name',
	),
	'type' => array(
		'title' => elgg_echo('file_tools:file_type').$type_arrow,
		'url' => $url."?folder_guid=$folder_guid&sort_by=simpletype&direction=$direction$folder_anchor",
		'selected' => $selected == 'type',
	),
	'time_created' => array(
		'title' => elgg_echo('file_tools:file_date_created').$time_created_arrow,
		'url' => $url."?folder_guid=$folder_guid&sort_by=e.time_created&direction=$direction$folder_anchor",
		'selected' => $selected == 'time_created',
	),
);
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
