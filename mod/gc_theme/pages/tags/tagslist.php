<?php
$limit = elgg_extract('limit', $vars, 60);
$selected_tab = get_input('selected_tab','all');
switch ($selected_tab) {
	default:
	case 'all':
		$mtl=0;
		break;
	case 'one_year':
		$mtl=time() - 3600*24*365;
		break;
	case 'six_months':
		$mtl=time() - 3600*12*365;
		break;
	case 'one_month':
		$mtl=time() - 3600*24*30;
		break;
}

$type = 'object';
$options = array(
        'type' => $type,
        'subtype' => ELGG_ENTITIES_ANY_VALUE,
        'threshold' => 0,
        'limit' => 1000000,
        'tag_name' => 'tags',
);
if ($mtl) {
	$options['modified_time_lower']= $mtl;
}
$tags = elgg_get_tags($options);
$count = count($tags);
$options['limit']= $limit;
$tags = elgg_get_tags($options);
$title = elgg_echo('tagslist');
$filter = elgg_view('tags/nav', array('selected' => $selected_tab));
$tags = elgg_get_tags($options);
$content = $distinct_count.elgg_view('tags/tagslist',array('tags' => $tags));
if ($count > $limit) {
	$content .= elgg_view('navigation/pagination', array(
	                'already_viewed' => $already_viewed_string,
	                'base_url' => $base_url,
	                'offset' => $offset,
	                'count' => $count,
	                'limit' => $limit,
	                'offset_key' => $offset_key,
	));
}
$sidebar = elgg_view('tags/sidebar/find');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('tags:list'), $body);
