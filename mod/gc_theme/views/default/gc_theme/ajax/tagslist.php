<?php
$offset = get_input('offset');
$base_url = get_input('base_url');
$count = get_input('count');
$limit = get_input('limit');
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
	'offset' => $offset,
        'tag_name' => 'tags',
);

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
echo $content;
