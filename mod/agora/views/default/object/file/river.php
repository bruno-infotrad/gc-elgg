<?php

$file = $vars['entity'];
if (preg_match('/\.(gif|jpeg|jpg|png)$/i', $file->originalfilename)) {
	$img_class = 'gc-zoom';
}

$title = elgg_view('output/url', array(
	'text' => $file->title,
	'href' => $file->getURL(),
	'encode_text' => true,
));

$description = elgg_get_excerpt($file->description, 350);

echo elgg_view('river/elements/attachment', array(
	'image' => elgg_view_entity_icon($file, 'small', array('href' => "/file/download/$file->guid", 'img_class' => $img_class)),
	'title' => $title,
	'description' => $description,
));
