<?php 
$of="<div class=\"elgg-module elgg-module-aside\" id=\"top-border\">\n";
$of.="<h2>".elgg_echo('gc_theme:popular_discussions')."</h2>\n";
$end=time();
$start=$end-3600*24*30;
$options = array(
        'types' => 'object',
        'limit' => '5',
	'calculation' => 'count',
	'metadata_name' => 'generic_comment',
        'annotation_created_time_lower' => $start,
        'annotation_created_time_upper' => $end,
        'order_by' => 'annotation_calculation desc, e.time_updated desc',
        'full_view' => false,
        'pagination' => false,
);

$results = elgg_get_entities_from_annotation_calculation($options);
foreach ($results as $discussion) {
	$of.=elgg_view('page/elements/popular_discussion', array('discussion' => $discussion));
}
$of.="</div>\n";
echo $of;
