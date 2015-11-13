<?php 
elgg_require_js('gc_theme/tags_autocomplete');
$value = elgg_extract("value", $vars);
if ($value) {
	$value = implode(',',$value);
}
?>
<input type="text" name="interests" value="<?php echo $value;?>" id="elgg-input-group-tags-autocomplete" class="elgg-input-tags elgg-input-autocomplete" />
<div id="elgg-input-group-tags-autocomplete-results"/>
