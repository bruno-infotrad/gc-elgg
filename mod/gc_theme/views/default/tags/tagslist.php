<?php
$tags = elgg_extract('tags', $vars);
foreach ($tags as $tag) {
	$body .= '<div class="tag-block">';
	$body .= '<div class="tag-total">'.$tag->total.'</div>';
	$body .= '<div class="tag-name">'.$tag->tag.'</div>';
	$body .= '</div>';
	//echo var_export($tag,true);
}
echo $body;
