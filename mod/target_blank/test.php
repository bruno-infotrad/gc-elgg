<?php 

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

admin_gatekeeper();

$body .= "<a href='http://www.google.nl'>Google.nl</a><br/>";
$body .= "<a href='http://www.google.nl' style='display: none;'>Google.nl (display: none)</a><br/>";
$body .= "<a href='https://www.google.nl'>Google.nl (HTTPS)</a><br/>";
$body .= "<a href='http://www.google.nl' target='_self'>Google.nl (target=_self)</a><br/>";
$body .= "<a href='/news'>/news</a><br/>";
$body .= "<a href='#'>#</a><br/>";
$body .= "<a href='javascript:void(0);'>javascript:void(0);</a><br/>";

$body .= elgg_view("output/url", array("text" => "demo link as button", "class" => "elgg-button elgg-button-action", "href" => "http://google.nl"));

$content = elgg_view_layout("one_column", array("title" => elgg_echo("Test"), "content" => $body));

echo elgg_view_page(elgg_echo("Test"), $content);