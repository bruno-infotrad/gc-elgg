<?php
if (isset($vars['header'])) {
	echo $vars['header'];
	return true;
}

$class = '';
if (isset($vars['class'])) {
	$class = " class=\"{$vars['class']}\"";
}
echo "<h2{$class}>{$vars['title']}</h2>";
