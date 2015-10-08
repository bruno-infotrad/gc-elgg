<?php
$entity = elgg_extract("entity", $vars, false);
$of='<li class="exec-content-box">';
$user = get_entity($entity->owner_guid);
$icon = elgg_view_entity_icon($user, 'tiny');
$of .= $icon;
$title = strip_tags($entity->title);
if ($title) {
	$text = (strlen($title) > 103) ? elgg_get_excerpt($title,100) : $title;
} else {
	$text = strip_tags($entity->description);
	$text = (strlen($text) > 103) ? elgg_get_excerpt($text,100) : $text;
}
$of .= elgg_view('output/url',array('text'=>$text,'href'=>$entity->getURL(),'class'=>'exec-content-url'));
$of.='</li>';
echo $of;
