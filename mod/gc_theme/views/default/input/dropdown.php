<?php
/**
 * Elgg dropdown input
 * Displays a dropdown (select) input field
 *
 * @warning Default values of FALSE or NULL will match '' (empty string) but not 0.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options']        An array of strings representing the options for the dropdown field
 * @uses $vars['options_values'] An associative array of "value" => "option"
 *                               where "value" is the name and "option" is
 * 								 the value displayed on the button. Replaces
 *                               $vars['options'] when defined.
 * @uses $vars['class']          Additional CSS class
 */
//error_log(var_export($vars,true),3,'/tmp/bla');
if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-dropdown {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-dropdown";
}

$defaults = array(
	'disabled' => false,
	'value' => '',
	'options_values' => array(),
	'options' => array(),
);

$vars = array_merge($defaults, $vars);

$options_values = $vars['options_values'];
unset($vars['options_values']);

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

$guid = $vars['guid'];
unset($vars['guid']);

$GLOBALS['GC_THEME']->debug("input/dropdown value=".var_export($value,true));
$GLOBALS['GC_THEME']->debug("input/dropdown options_values=".var_export($options_values,true));
$GLOBALS['GC_THEME']->debug("input/dropdown elgg_get_page_owner_guid=".elgg_get_page_owner_guid());
$GLOBALS['GC_THEME']->debug("input/dropdown guid=".$guid);
$GLOBALS['GC_THEME']->debug("input/dropdown elgg_view_access_collections=".elgg_view_access_collections(elgg_get_page_owner_guid()));
$filter_guid=elgg_get_page_owner_guid();
$gguid=get_entity($filter_guid);
$GLOBALS['GC_THEME']->debug("input/dropdown gguid->membership=".$gguid->membership);

?>
<select <?php echo elgg_format_attributes($vars); ?>>
<?php

if ($options_values) {
// Check it is a group otherwise default
	if ($gguid instanceof ElggGroup) {
		$uri_atoms = preg_split("/\//",$_SERVER['REQUEST_URI']);
		$GLOBALS['GC_THEME']->debug("input/dropdown uri_atoms=".$uri_atoms[sizeof($uri_atoms)-2]);
$and_used = preg_split("/ and /i", $query);
// Check if group is closed and we are displaying the access drowpdown. If so, change the default value to be for the group
		//if ($options_values[0] == ACCESS_PRIVATE ) {
		if ($options_values[0] == ACCESS_PRIVATE && $gguid->membership == ACCESS_PRIVATE && $uri_atoms[sizeof($uri_atoms)-2] != 'edit') {
			$dbprefix = elgg_get_config('dbprefix');
			$gac=get_data_row("SELECT id FROM {$dbprefix}access_collections WHERE owner_guid='$filter_guid'");
			$GLOBALS['GC_THEME']->debug("input/dropdown group_access_collections ".$gac->id);
			$value=$gac->id;
		//} else {
			//$value = ACCESS_LOGGED_IN;
		}
	}
	foreach ($options_values as $opt_value => $option) {

		$GLOBALS['GC_THEME']->debug("input/dropdown opt_value=$opt_value");
		$option_attrs = elgg_format_attributes(array(
			'value' => $opt_value,
			'selected' => (string)$opt_value == (string)$value,
		));

		$GLOBALS['GC_THEME']->debug("input/dropdown option_attrs=$option_attrs");
		echo "<option $option_attrs>$option</option>";
	}
} else {
	if (is_array($options)) {
		foreach ($options as $option) {
			$GLOBALS['GC_THEME']->debug("input/dropdown option=$option");
			$option_attrs = elgg_format_attributes(array(
				'selected' => (string)$option == (string)$value
			));

			echo "<option $option_attrs>$option</option>";
		}
	}
}
?>
</select>
