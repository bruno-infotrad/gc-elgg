<?php
/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */

$user = elgg_get_logged_in_user_entity();
$user_last_action = $user->feed_viewed_previous;

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');

$list_class = 'elgg-list';
if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$html = "";
$nav = "";
$ingroup_label=elgg_echo('river:ingroup:label');
$ingroups_label=elgg_echo('river:ingroups:label');
$ingroup_pattern=$ingroups_label.'|'.$ingroup_label.' ';

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'baseurl' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}
if (elgg_get_context() != 'admin') {
	$item_markup = 0;
	if (is_array($items) && count($items) > 0) {
		$previous_item_time_created = 0;
		$html .= "<ul class=\"$list_class\">";
		$premier=true;
		foreach ($items as $item) {
			$guid=0;
			$test[$guid]=0;
		 	if ($item instanceof ElggRiverItem) {
				// add separation bar to previously loaded content 
				if (elgg_is_logged_in()) {
					if ($user_last_action > $item->posted && $user_last_action < $previous_item_time_created) {
						$html .= "<li class=\"updated-item\">".elgg_echo('gc_theme:previously_loaded')."</li>";
					}
					$previous_item_time_created = $item->posted;
					$previous_item_user_guid = $item->subject_guid;
				}
		 		if ($item->type != 'group' && ! $test[$item->object_guid]) {
					$query_max = "SELECT rv.annotation_id from {$CONFIG->dbprefix}river rv where rv.object_guid = $item->object_guid ORDER BY rv.annotation_id DESC LIMIT 1";
					$annotation_id_max = get_data_row($query_max);
					if ($item->annotation_id == 0) {
						$banned_guid=$item->object_guid;
					} elseif ($item->annotation_id == $annotation_id_max->annotation_id) {
						$query1 = "SELECT rv.* from {$CONFIG->dbprefix}river rv where rv.object_guid = $item->object_guid and rv.action_type = 'create'";
						$item1 = get_data($query1, 'elgg_row_to_elgg_river_item');
						$item=$item1[0];
						$banned_guid=$item->object_guid;
					} else {
						$test[$item->object_guid] = 1;
					}
				}
			} else {
		 		if ($item instanceof ElggAnnotation) {
					unset($item_class);
					if (elgg_is_logged_in() && $user->guid != $item->owner_guid && $user_last_action < $item->time_created) {
						//$item_class=$item_class." marked-as-updated";
						$item_class="updated-annotation";
					}
				}
			}
			if ($item instanceof ElggRiverItem) {
				$guid=$item->object_guid;
			} else {
				$guid=0;
			}
			if (!$test[$guid]) {
				if (elgg_instanceof($item)) {
					$id = "elgg-{$item->getType()}-{$item->getGUID()}";
					$html .= "<li id=\"$id\" class=\"$item_class\">";
					$html .= elgg_view_list_item($item, $vars);
					$html .= '</li>';
					$banned_guid=$item->object_guid;
					$test[$banned_guid]=1;
				} else {
					$id = "item-{$item->getType()}-{$item->id}";
					$container_guid = get_entity($item->object_guid)->container_guid;
					if (get_entity($container_guid) instanceof ElggGroup) {
						$group = $container_guid;
						$container = get_entity($group);
						$params = array( 'href' => $container->getURL(), 'text' => $container->name, 'is_trusted' => true,);
        					$group_link = elgg_view('output/url', $params);
					} else {
						$group = 0;
					}
					//$html .= "previous_group=$previous_group group=$group"; 
					if ($premier) {
						$cached_html .= "<li id=\"$id\" class=\"$item_class\">";
						$cached_html .= elgg_view_list_item($item, $vars);
						$cached_html .= '</li>';
					} elseif ($previous_group && $group && $previous_group <> $group && $item->subject_guid == $previous_subject_guid && ($item->posted-$previous_posted) <=2) {
						$cached_html = preg_replace("/$ingroup_pattern/",$ingroups_label.' '.$group_link.', ',$cached_html);
						$cached_html = preg_replace('/<div class="wire-edit" onclick=.+?\><\/div\>/U','',$cached_html);
					} else {
						$html .= $cached_html;
						$cached_html = '';
						$html .= "<li id=\"$id\" class=\"$item_class\">";
						$html .= elgg_view_list_item($item, $vars);
						$html .= '</li>';
					}
					$banned_guid=$item->object_guid;
					$test[$banned_guid]=1;
					$previous_subject_guid=$item->subject_guid;
					$previous_posted=$item->posted;
					$previous_group = $group;
				}
			}
			$premier = $false;
		}
		$html .= '</ul>';
	}
} else {
	if (is_array($items) && count($items) > 0) {
        	$html .= "<ul class=\"$list_class\">";
        	foreach ($items as $item) {
                	if (elgg_instanceof($item)) {
                        	$id = "elgg-{$item->getType()}-{$item->getGUID()}";
                	} else {
                        	$id = "item-{$item->getType()}-{$item->id}";
                	}
                	$html .= "<li id=\"$id\" class=\"$item_class\">";
                	$html .= elgg_view_list_item($item, $vars);
                	$html .= '</li>';
        	}
        	$html .= '</ul>';
	}
}


if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;
