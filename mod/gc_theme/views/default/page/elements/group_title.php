<?php
if (isset($vars['header'])) {
	echo $vars['header'];
	return true;
}

if (isset($vars['readonly'])) {
	echo "<h2{$class}>".elgg_echo('gc_theme:groups:readonly:message')."</h2>";
}
$class = '';
if (isset($vars['class'])) {
	$class = " class=\"{$vars['class']}\"";
}
echo "<h2{$class}>{$vars['title']}</h2>";
