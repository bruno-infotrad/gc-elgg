<?php
if (isset($vars['header'])) {
	echo $vars['header'];
	return true;
}
if (isset($vars['readonly'])) {
	echo "<h2 id='readonly'>".elgg_echo('gc_theme:groups:readonly:message')."</h2>";
}
if ($vars['readonly'] == ' readonly') { $class = " id='readonly2'";}
if (isset($vars['class'])) {
	$class = " class=\"{$vars['class']}\"";
}
echo "<h2{$class}>{$vars['title']}</h2>";
