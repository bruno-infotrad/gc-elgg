<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
elgg_load_library('elgg:file_tools');
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$embed = elgg_extract('embed', $vars);
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
	$file_selected = $title;
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
	$file_selected = elgg_echo('gc_theme:no_file_selected');
}
?>
<script type="text/javascript">
if (! "<?php echo $guid; ?>" ) {
$(document).ready(function() {
        $('#multi-upload-button').css('pointer-events','none');
        $('#multi-upload-button').attr('disabled','disabled');
        $('#multi-upload-button').css('opacity',0.5);
});
}
function check_file(){
	var allowed_extensions_settings = "<?php echo elgg_get_plugin_setting('allowed_extensions', 'file_tools');?>";
	var allowed_extensions = allowed_extensions_settings.split(/, */);
        var ext = $('.elgg-input-file').val().split('.').pop().toLowerCase();
        if($.inArray(ext, allowed_extensions) == -1) {
                alert(elgg.echo('gc_theme:file:not_allowed'));
                $('.elgg-input-file').val('');
        }
        var filename = $('.elgg-input-file').val().split('\\').pop().toLowerCase();
	if (filename.length > 30) {
    		filename =  filename.substr(0, 15) + '...' + filename.substr(filename.length-10, filename.length);
  	}
	$('#gc-file-selected').html(filename);
        $('#multi-upload-button').css('opacity',1);
	$('#multi-upload-button').css('pointer-events','all');
	$('#multi-upload-button').removeAttr('disabled','disabled');
}
</script>
<div class="gc-input-file-row">
	<div class="gc-input-file-2em"><label><?php echo elgg_echo('title'); ?></label></div>
	<div class="gc-input-file-ib"><?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?></div>
	<div class="gc-input-file-browse"><?php echo elgg_view('input/file', array('name' => 'upload','js' => 'onchange="check_file()"')); ?></div>
	<div class="gc-input-file-1em" id="gc-file-selected"><?php echo $file_selected; ?></div>
</div>
<div class="gc-input-file-row">
	<div class="gc-input-file-2em"><label><?php echo elgg_echo('tags'); ?></label></div>
	<div class="gc-input-file-ib"><?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?></div>
	<div class="gc-input-file-1em"><label><?php echo elgg_echo('description'); ?></label></div>
	<div class="gc-input-file-desc"><?php echo elgg_view('input/plaintext', array('name' => 'description', 'value' => $desc)); ?></div>
</div>

<div class="gc-input-file-row">
<?php 
//CAREFUL! file_tools plugin has to be on for his to work. Since it has been in use, no point
// in adding a test
if(file_tools_use_folder_structure()){
	$parent_guid = 0;
	if($file = elgg_extract("entity", $vars)){
		if($folders = $file->getEntitiesFromRelationship(FILE_TOOLS_RELATIONSHIP, true, 1)){
			$parent_guid = $folders[0]->getGUID();
		}
	}
	?>
		<div id='gc_theme-parent-folder-label' class="gc-input-file-1em"><label><?php echo elgg_echo("file_tools:forms:edit:parent"); ?></label></div>
		<div class="gc-input-file-ib"><?php echo elgg_view("input/folder_select", array("name" => "folder_guid", "value" => $parent_guid));?></div>
<?php 
}

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
	<div class="gc-input-file-1em"><label><?php echo elgg_echo('access'); ?></label></div>
	<div class="gc-input-file-ib"><?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?></div>
</div>
<div class="elgg-foot gc-input-file-1em" id="multi-upload-div">
<?php

if ($embed) {
	echo elgg_view('input/hidden', array('name' => 'embed', 'value' => true));
}
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('id' => 'multi-upload-button', 'value' => $submit_label));

?>
</div>
