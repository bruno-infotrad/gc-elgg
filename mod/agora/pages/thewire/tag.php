<?php
/**
 * Wire posts tagged with <tag>
 */

$tag = get_input('tag');
if (!$tag) {
	forward('thewire/all');
}

// remove # from tag
$tag = trim($tag, '# ');

$title = elgg_echo('agora:thewire:tags', array($tag));


$count = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'tags',
	'metadata_value' => $tag,
	'metadata_case_sensitive' => false,
	'type' => 'object',
	//'subtype' => 'blog',
	'limit' => 20,
	'count' => true,
));
$entities = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'tags',
	'metadata_value' => $tag,
	'metadata_case_sensitive' => false,
	'type' => 'object',
	//'subtype' => 'blog',
	'limit' => 20,
));
$results=array('entities' => $entities,'count' => $count);
foreach ($entities as $entity) {
	$title_tmp = $entity->title;
	$title_tmp = strip_tags($title_tmp);
	if (! $title_tmp) {
		$title_tmp = $tag;
	}
	if (elgg_strlen($title_tmp) > 297) {
		$title_str = elgg_substr($title_tmp, 0, 297) . '...';
	} else {
		$title_str = $title_tmp;
	}
	$desc_tmp = strip_tags($entity->description);
	if (elgg_strlen($desc_tmp) > 297) {
		$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
	} else {
		$desc_str = $desc_tmp;
	}
	//$tags_str = implode('. ', $matched_tags_strs);
	//$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);
	$entity->setVolatileData('search_matched_title', $title_str);
	$entity->setVolatileData('search_matched_description', $desc_str);
	//$entity->setVolatileData('search_matched_extra', $tags_str);
}
//foreach ($results as $result) {
	$current_params['search_type'] = 'tags';
	$current_params['type'] = ELGG_ENTITIES_ANY_VALUE;
	//unset($current_params['subtype']);
	if ($view = search_get_search_view($current_params, 'list')) {
		$content .= elgg_view($view, array( 'results' => $results, 'params' => $current_params,'thewire_tags' => 1));
	}
//}

//$body .= var_export($results,true);
$body .= elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar'),
));

echo elgg_view_page($title, $body);
