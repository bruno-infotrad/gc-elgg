<?php

$type = 'object';

$options = array(
        'type' => $type,
        'subtype' => ELGG_ENTITIES_ANY_VALUE,
        'threshold' => 0,
	'count' => true,
        'tag_name' => 'tags',
);
$count = elgg_get_tags($options);
$options = array(
        'type' => $type,
        'subtype' => ELGG_ENTITIES_ANY_VALUE,
        'threshold' => 0,
        'limit' => elgg_extract('limit', $vars, 60),
        'tag_name' => 'tags',
);

$title = elgg_echo('tagslist');
$tags = elgg_get_tags($options);
$content = elgg_view('tags/tagslist',array('tags' => $tags));
$content .= elgg_view('navigation/pagination', array(
                'already_viewed' => $already_viewed_string,
                'base_url' => $base_url,
                'offset' => $offset,
                'count' => $count,
                'limit' => $limit,
                'offset_key' => $offset_key,
));

/*
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
*/
$filter = elgg_view('tags/tagslist_filter', array('selected' => $selected_tab));

$sidebar = elgg_view('tags/sidebar/find');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('tags:list'), $body);
