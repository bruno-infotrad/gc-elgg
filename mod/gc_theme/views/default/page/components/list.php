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
$already_viewed_string = elgg_extract('already_viewed', $vars, '');
$GLOBALS['GC_THEME']->debug("IN LIST ALREADY_VIEWED=$already_viewed_string");
if ($already_viewed_string) {
	$already_viewed = explode(',',$already_viewed_string);
	foreach ($already_viewed as $viewed_guid) {
		$GLOBALS['GC_THEME']->debug("IN LIST ALREADY_VIEWED GUID=$viewed_guid");
		$test[$viewed_guid] = 1;
	}
}

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
// For contribute to display
$river_label=elgg_echo('river:create:object:thewire:label');
$wire_label=elgg_echo('thewire:wire');
$ingroup_label=elgg_echo('river:ingroup:label');
$ingroups_label=elgg_echo('river:ingroups:label');
$ingroup_pattern=$ingroups_label.'|'.$ingroup_label.' ';

$html .= "<ul class=\"$list_class\">";
if (elgg_get_context() != 'admin') {
	$previous_item_time_created = 0;
	$premier=true;
	foreach ($items as $item) {
		$GLOBALS['GC_THEME']->debug("IN LIST ITEM=".var_export($item,true));
		$GLOBALS['GC_THEME']->debug("IN LIST TEST=".var_export($test,true));
		$GLOBALS['GC_THEME']->debug("IN LIST VERIF OGUID=".$item->object_guid." TGUID=".$item->target_guid." TEST_OGUID=".$test[$item->object_guid]." TEST_TGUID=".$test[$item->target_guid]);
		//if ($test[$item->object_guid]){
			//continue;
		//}
		//$guid=0;
		//$test[$guid]=0;
		$item_markup = 0;
		$dbprefix = elgg_get_config('dbprefix');
		$new_item = $item;
	 	if ($item instanceof ElggRiverItem) {
			$guid=$item->object_guid;
			// add separation bar to previously loaded content 
			if (elgg_is_logged_in()) {
				//if ($user->guid != $previous_item_user_guid && $user_last_action > $item->posted && $user_last_action < $previous_item_time_created) {
				if ($user_last_action > $item->posted && $user_last_action < $previous_item_time_created) {
					$html .= "<li class=\"updated-item\">".elgg_echo('gc_theme:previously_loaded')."</li>";
				}
				$previous_item_time_created = $item->posted;
				$previous_item_user_guid = $item->subject_guid;
			}
	 		if ($item->subtype == 'comment'||$item->subtype == 'discussion_reply') {
	 			if (! $test[$item->target_guid]) {
					$query = "SELECT rv.* from {$dbprefix}river rv where rv.object_guid = $item->target_guid and rv.action_type = 'create'";
					$res = get_data($query, "_elgg_row_to_elgg_river_item");
					$new_item = $res[0];
					$test[$item->target_guid] = 1;
					//$test[$item->object_guid] = 1;
					//unset($item_class);
					if (elgg_is_logged_in() && $user->guid != $item->owner_guid && $user_last_action < $item->time_created) {
						//$item_class=$item_class." marked-as-updated";
						$item_class="updated-annotation";
					}
				} else {
					$GLOBALS['GC_THEME']->debug("IN LIST ITEM SKIPPED COMMENT");
					continue;
				}
			} else {
				if ($test[$item->object_guid]){
					$GLOBALS['GC_THEME']->debug("IN LIST ITEM SKIPPED ITEM");
					continue;
				}
			}
		} else {
			$guid=0;
	 		if ($item instanceof ElggAnnotation||$item->getSubType() == 'comment'||$item->getSubType() == 'discussion_reply') {
				unset($item_class);
				if (elgg_is_logged_in() && $user->guid != $item->owner_guid && $user_last_action < $item->time_created) {
					//$item_class=$item_class." marked-as-updated";
					$item_class="updated-annotation";
				}
			}
		}
		$li = elgg_view_list_item($new_item, $vars);
		$GLOBALS['GC_THEME']->debug("IN LIST NEW_ITEM=".var_export($new_item,true));
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



			//if (!$test[$guid]) {
				if ($new_item->getType() != 'river' || $new_item ->subtype == 'comment' || strpos(current_page_url(), 'dashboard') === false) {
					//$html .= "PREMIERE CONDITION TYPE=".$new_item->getType()." SUBTYPE=".$new_item ->getSubType();
					$html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
					//$banned_guid=$item->object_guid;
					//$test[$banned_guid]=1;
				} else {
					//$html .= "DEUXIEME CONDITION TYPE=".$new_item->getType()." SUBTYPE=".$new_item ->getSubType();
					$container_guid = get_entity($new_item->object_guid)->container_guid;
					if (get_entity($container_guid) instanceof ElggGroup) {
						if ($container_guid == 34082) {
							$item_classes .= ' hide-cla';
						}
						$group = $container_guid;
						$container = get_entity($group);
						$params = array( 'href' => $container->getURL(), 'text' => $container->name, 'is_trusted' => true,);
        					$group_link = elgg_view('output/url', $params);
						$entity_guid = $new_item->object_guid;
						//$entity_guid = get_entity($item->object_guid)->guid;
					} else {
						$group = 0;
					}
					if ($premier) {
						$vars['skip'] = false;
						$cached_html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
						$previous_group_string = $group;
						$previous_item_string = $entity_guid;
						//$html .= "<br>previous_group_string=$previous_group_string group=$group"; 
					} elseif ($previous_group && $group && $previous_group <> $group && $new_item->subject_guid == $previous_subject_guid && get_entity($new_item->object_guid)->description == $previous_description && abs($previous_posted - $new_item->posted) <=2) {
					//} elseif ($previous_group && $group && $previous_group <> $group && $new_item->subject_guid == $previous_subject_guid && abs($previous_posted - $new_item->posted) <=2) {
						//$html .= "<br>previous_group_string=$previous_group_string group=$group"; 
						$cached_html = preg_replace("/$ingroup_pattern/",$ingroups_label.' '.$group_link.', ',$cached_html,1);
						//$cached_html = preg_replace('/<div class="wire-edit" onclick=.+?\><\/div\>/U','',$cached_html,1);
						$cached_html = preg_replace("/edits\['container_guid'\] = '".$previous_group_string."/","edits['container_guid'] = '".$previous_group_string.",".$group,$cached_html,1);
						$cached_html = preg_replace("/edits\['guid'\] = '".$previous_item_string."/","edits['guid'] = '".$previous_item_string.",".$entity_guid,$cached_html,1);
						$cached_html = preg_replace('/'.$river_label.'.+?\>'.$wire_label.'<\/a>/U',$river_label.$wire_label,$cached_html,1);
						$cached_html = preg_replace('/<li class="elgg-menu-item-comment"/','<li class="elgg-menu-item-comment" style="display:none;"',$cached_html);
						//$cached_html = preg_replace('/<form .+/','',$cached_html,1);
						//$vars['skip'] = true;
						$responses = elgg_view('river/elements/responses', array('item'=>$new_item,'skip'=>$vars['skip']));
						if ($responses) {
							$cached_html = preg_replace('/<div class="gc-multi-group-posts"><\/div>/',$responses.'<div class="gc-multi-group-posts"></div>',$cached_html,1);
						}
						//if ($previous_group_string) {
							$previous_group_string = $previous_group_string.','.$group;
							$previous_item_string = $previous_item_string.','.$entity_guid;
						//} else {
							//$previous_group_string = $previous_group;
						//}
						//$html .= "<br>previous_group_string=$previous_group_string group=$group"; 
					} else {
						//$html .= "<br>previous_group_string=$previous_group_string group=$group"; 
						$vars['skip'] = false;
						$html .= $cached_html;
						$cached_html = '';
						$previous_group_string = $group;
						$previous_item_string = $new_item->object_guid;
						//$previous_item_string = get_entity($item->object_guid)->guid;
						$cached_html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
						/*
						$cached_html .= "<li id=\"$id\" class=\"$item_classes\">";
						$cached_html .= elgg_view_list_item($new_item, $vars);
						$cached_html .= '</li>';
						*/
					}
					//$banned_guid=$item->object_guid;
					//$test[$banned_guid]=1;
					$previous_=$new_item;
					$previous_subject_guid=$new_item->subject_guid;
					$previous_description=get_entity($new_item->object_guid)->description;
					$previous_posted=$new_item->posted;
					$previous_group = $group;
				}
			//}
			$premier = $false;
			//$html .= "<li id=\"$id\" class=\"$item_classes\">$li</li>";
		}
	}
	$html .= $cached_html;
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

$already_viewed_string = implode(',',array_keys($test));
if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'already_viewed' => $already_viewed_string,
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;
