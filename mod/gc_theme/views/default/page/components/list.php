<?php
/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page. Only used as input to pagination.
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 * @uses $vars['no_results']  Message to display if no results (string|Closure)
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

$user = elgg_get_logged_in_user_entity();
$user_last_action = $user->feed_viewed_previous;

if (!$items && $no_results) {
	if ($no_results instanceof Closure) {
		echo $no_results();
		return;
	}
	echo "<p>$no_results</p>";
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

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

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

$html .= "<ul class=\"$list_class\">";
if (elgg_get_context() != 'admin') {
	foreach ($items as $item) {
		if ($test[$item->object_guid]){
			continue;
		}
		$guid=0;
		$test[$guid]=0;
		$item_markup = 0;
		$dbprefix = elgg_get_config('dbprefix');
		$new_item = $item;
	 	if ($item instanceof ElggRiverItem) {
			// add separation bar to previously loaded content 
			if (elgg_is_logged_in()) {
				//if ($user->guid != $previous_item_user_guid && $user_last_action > $item->posted && $user_last_action < $previous_item_time_created) {
				if ($user_last_action > $item->posted && $user_last_action < $previous_item_time_created) {
					$html .= "<li class=\"updated-item\">".elgg_echo('gc_theme:previously_loaded')."</li>";
				}
				$previous_item_time_created = $item->posted;
				$previous_item_user_guid = $item->subject_guid;
			}
	 		if ($item->subtype == 'comment') {
	 			if (! $test[$item->target_guid]) {
					$query = "SELECT rv.* from {$dbprefix}river rv where rv.object_guid = $item->target_guid and rv.action_type = 'create'";
					$res = get_data($query, "_elgg_row_to_elgg_river_item");
					$new_item = $res[0];
					$test[$item->target_guid] = 1;
					$test[$item->object_guid] = 1;
					//unset($item_class);
					if (elgg_is_logged_in() && $user->guid != $item->owner_guid && $user_last_action < $item->time_created) {
						//$item_class=$item_class." marked-as-updated";
						$item_class="updated-annotation";
					}
				} else {
					continue;
				}
			}
		} else {
	 		if ($item instanceof ElggAnnotation || $item->getSubtype() == 'comment') {
				unset($item_class);
				if (elgg_is_logged_in() && $user->guid != $item->owner_guid && $user_last_action < $item->time_created) {
					//$item_class=$item_class." marked-as-updated";
					$item_class="updated-annotation";
				}
			}
		}
		$li = elgg_view_list_item($new_item, $vars);
		if ($li) {
			$item_classes = array($item_class);
			
			if (elgg_instanceof($new_item)) {
				$id = "elgg-{$new_item->getType()}-{$new_item->getGUID()}";
				
				$item_classes[] = "elgg-item-" . $new_item->getType();
				$subtype = $new_item->getSubType();
				if ($subtype) {
					$item_classes[] = "elgg-item-" . $new_item->getType() . "-" . $subtype;
				}
			} else {
				$id = "item-{$new_item->getType()}-{$new_item->id}";
			}
			
			$item_classes = implode(" ", $item_classes);
			
			$html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
		}
	}
} else {
	foreach ($items as $item) {
		$li = elgg_view_list_item($item, $vars);
		if ($li) {
			$item_classes = array($item_class);
			
			if (elgg_instanceof($item)) {
				$id = "elgg-{$item->getType()}-{$item->getGUID()}";
				
				$item_classes[] = "elgg-item-" . $item->getType();
				$subtype = $item->getSubType();
				if ($subtype) {
					$item_classes[] = "elgg-item-" . $item->getType() . "-" . $subtype;
				}
			} else {
				$id = "item-{$item->getType()}-{$item->id}";
			}
			
			$item_classes = implode(" ", $item_classes);
			
			$html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
		}
	}
}
$html .= '</ul>';

if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;
