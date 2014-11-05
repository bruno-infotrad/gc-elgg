<?php 
/**
 * Elgg activity contents
 *
 * You can override, extend, or pass content to it
 *
 * @uses $vars['html_alt] HTML content for the alternate html
 */


//error_log("VARS ".var_export($vars,true),3,'/tmp/bla');
$items=$vars['activity'];

$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
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
$nav = "";
//error_log("===========================\n",3,'/tmp/bla');
//error_log("Offset     ".$offset."\n",3,'/tmp/bla');
//error_log("Limit      ".$limit."\n",3,'/tmp/bla');
//error_log("Count      ".$count."\n",3,'/tmp/bla');
//error_log("Base url   ".$base_url."\n",3,'/tmp/bla');
//error_log("Pagination ".$pagination."\n",3,'/tmp/bla');
//error_log("Offset_key ".$offset_key."\n",3,'/tmp/bla');
if ($pagination && $count) {
        $nav .= elgg_view('navigation/pagination', array(
                'baseurl' => $base_url,
                'offset' => $offset,
                'count' => $count,
                'limit' => $limit,
                'offset_key' => $offset_key,
        ));
}

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
		//elgg_log("BRUNO activity:summary before elgg_view".$summary, 'NOTICE');
		if ($summary === false) {
			elgg_log("BRUNO activity:summary FALSE".$summary, 'NOTICE');
		        $subject = $item->getSubjectEntity();
		        $summary = elgg_view('output/url', array(
		                'href' => $subject->getURL(),
		                'text' => $subject->name,
		                'class' => 'elgg-river-subject',
		                'is_trusted' => true,
		        ));
		}
		elgg_log("BRUNO activity:summary ".$summary, 'NOTICE');
		                                $html .= $summary;
	}
}

if ($position == 'before' || $position == 'both') {
        $html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
        $html .= $nav;
}

echo $html;

//error_log("===========================\n",3,'/tmp/bla');
//error_log("Offset     ".$offset."\n",3,'/tmp/bla');
//error_log("Limit      ".$limit."\n",3,'/tmp/bla');
//error_log("Count      ".$count."\n",3,'/tmp/bla');
//error_log("Base url   ".$base_url."\n",3,'/tmp/bla');
//error_log("Pagination ".$pagination."\n",3,'/tmp/bla');
//error_log("Offset_key ".$offset_key."\n",3,'/tmp/bla');
