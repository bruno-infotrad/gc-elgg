<?php 
/**
 * Elgg activity contents
 *
 * You can override, extend, or pass content to it
 *
 * @uses $vars['html_alt] HTML content for the alternate html
 */
$items=$vars['activity'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$count = count($items);
//$limit=20;
//$base_url="http://192.168.1.20/elgg";

$list_class = 'elgg-list';
if (isset($vars['list_class'])) {
        $list_class = "$list_class {$vars['list_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
        $item_class = "$item_class {$vars['item_class']}";
}
$html = "";
if (is_array($items) && $count > 0) {
	foreach ($items as $item) {
		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";
		} else {
			$id = "item-{$item->getType()}-{$item->id}";
		}
		//$html .= "<p id=\"$id\" class=\"$item_class\">";
		$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary_with_tiny_pic', array('item' => $item)));
		//$summary = elgg_view_list_item($item);
		//$GLOBALS['GC_THEME']->debug("BRUNO activity:summary before elgg_view".$summary);
		if ($summary === false) {
			$GLOBALS['GC_THEME']->debug("BRUNO activity:summary FALSE".$summary);
		        $subject = $item->getSubjectEntity();
		        $summary = elgg_view('output/url', array(
		                'href' => $subject->getURL(),
		                'text' => $subject->name,
		                'class' => 'elgg-river-subject',
		                'is_trusted' => true,
		        ));
		}
		$GLOBALS['GC_THEME']->debug("BRUNO activity:summary ".$summary);
		                                $html .= $summary;
	}
}
echo $html;
