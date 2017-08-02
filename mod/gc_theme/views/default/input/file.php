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
	'size' => 40,
);

$attrs = array_merge($defaults, $vars);

?>
<div class="input-file"><p class="elgg-button elgg-file-button"><?php echo elgg_echo('file_tools:forms:browse');?></p>
<input type="file" <?php echo elgg_format_attributes($attrs);?> multiple/>
</div>
