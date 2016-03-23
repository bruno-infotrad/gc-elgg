<?php
$tags = elgg_extract('tags', $vars);
foreach ($tags as $tag) {
	$tag_url = "search?q=".$tag->tag."&advanced_type=&contributed_by=&approximate_date=&offset=0&search_type=tags&exact=yes";
	$tag_link = elgg_view('output/url', array( 'href' => $tag_url, 'text' => $tag->tag, 'is_trusted' => true,));
	$body .= '<div class="tag-block">';
	$body .= '<div class="tag-total">'.$tag->total.'</div>';
	$body .= '<div class="tag-name">'.$tag_link.'</div>';
	$body .= '</div>';
}
echo $body;
