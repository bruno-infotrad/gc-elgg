<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value if any
 * @uses $vars['class'] Additional CSS class
 */

if (!empty($vars['value'])) {
	echo elgg_echo('fileexists') . "<br />";
}

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-file {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-file";
}

$defaults = array(
	'disabled' => false,
	'size' => 10,
);

$attrs = array_merge($defaults, $vars);

?>
<div id="upload-file"><?php echo elgg_echo('agora:upload_file'); ?></div>
<input id="file-browse-button" type="file" <?php echo elgg_format_attributes($attrs); ?> />
