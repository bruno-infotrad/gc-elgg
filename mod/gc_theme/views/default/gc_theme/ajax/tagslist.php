<?php
$offset = get_input('offset');
$base_url = get_input('base_url');
$count = get_input('count');
$limit = get_input('limit');
$selected_tab = get_input('page_type','all');
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
        'limit' => elgg_extract('limit', $vars, 60),
	'offset' => $offset,
        'tag_name' => 'tags',
);
if ($mtl) {
	$options['modified_time_lower']= $mtl;
}

$tags = elgg_get_tags($options);
$content = elgg_view('tags/tagslist',array('tags' => $tags));
if ($count - $offset > $limit) {
	$content .= elgg_view('navigation/pagination', array(
	                'already_viewed' => $already_viewed_string,
	                'base_url' => $base_url,
	                'offset' => $offset,
	                'count' => $count,
	                'limit' => $limit,
	                'offset_key' => $offset_key,
	));
}
echo $content;
