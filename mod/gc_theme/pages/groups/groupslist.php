<?php
// all groups doesn't get link to self
$db_prefix = elgg_get_config("dbprefix");
elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('groups'));
elgg_register_title_button();
$selected_tab = get_input('filter', 'a');
if ($selected_tab == 'nonalpha') {
	$search_filter = "REGEXP '^[^A-Za-z]'";
} else {
	$search_filter = "like '".$selected_tab."%'";
}
$content = elgg_list_entities(array(
	'type' => 'group',
	'full_view' => 'gc_list_summary',
	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
	'wheres' => array("ge.name $search_filter"),
	'item_class' => 'groups-list',
	'order_by' => 'name asc',
	'limit' => 10000
));

$filter = elgg_view('groups/groups_list', array('selected' => $selected_tab));

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('page/elements/popular_groups', $vars);

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('groups:list'), $body);
