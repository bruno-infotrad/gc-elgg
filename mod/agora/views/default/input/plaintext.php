<?php
/**
 * Elgg long text input (plaintext)
 * Displays a long text input field that should not be overridden by wysiwyg editors.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']    The current value, if any
 * @uses $vars['name']     The name of the input field
 * @uses $vars['class']    Additional CSS class
 * @uses $vars['disabled']
 */
elgg_load_js('elgg.user_handle');

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-plaintext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-plaintext";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);
//autocomplete
	$name = elgg_extract("name", $vars); // input name of the selected user
	$id = elgg_extract("id", $vars);
        $destination = $id . "_autocomplete_results";
	//$minChars = elgg_extract("minChars", $vars, 1);
	elgg_load_css("group_tools.autocomplete");
	
	$site_url = elgg_get_site_url();

?>

<textarea <?php echo elgg_format_attributes($vars); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false); ?>
</textarea>
<div id="<?php echo $destination; ?>"></div>
<div class="clearfloat"></div>
