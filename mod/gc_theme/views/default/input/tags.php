<?php 
elgg_require_js('gc_theme/tags_autocomplete');
$name = elgg_extract("name", $vars);
$value = elgg_extract("value", $vars);
if (is_array($value)) {
	$value = implode(',',$value);
}
?>
<input type="text" name="<?php echo $name;?>" value="<?php echo $value;?>" id="elgg-input-tags-autocomplete" class="elgg-input-tags elgg-input-autocomplete" />
<div id="elgg-input-tags-autocomplete-results"></div>
