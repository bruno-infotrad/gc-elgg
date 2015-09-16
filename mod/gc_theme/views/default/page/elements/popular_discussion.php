<?php 
$discussion=$vars['discussion'];
$owner = get_entity($discussion->owner_guid);
if ($discussion->title) {
	$text = $discussion->title;
} else {
	$text = $discussion->description;
}
$text=strip_tags($text);
$text = (strlen($text) > 203) ? substr($text,0,200).'...' : $text;
//$text = var_export($discussion,true).' '.$text;
$icon = elgg_view_entity_icon($owner, 'small');
$summary = elgg_view('output/url', array(
		'href' => $discussion->getURL(),
		'text' => $text,
	));
echo '<div class="elgg-image-block clearfix"><div class="elgg-image">'.$icon.'</div><div class="elgg-body sidebar-discussion-item">'.$summary.'<div class="gc-small-date">'.elgg_view_friendly_time($discussion->time_created).'</div></div></div>';
