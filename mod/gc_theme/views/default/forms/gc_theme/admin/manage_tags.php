<?php
echo '<h4>'.elgg_echo('gc_theme:search_tags').'<h4>';
echo elgg_view("input/tags", array('name' => 'tags', 'value' => elgg_extract('value', $vars)));
echo elgg_view("input/submit", array("value" => elgg_echo("search")));
