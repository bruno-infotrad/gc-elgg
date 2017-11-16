<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
elgg_load_library('elgg:file_tools');
elgg_load_js('elgg.dropzone');
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$container = get_entity($container_guid);
if (elgg_instanceof($container, 'group')) {
	$group_page = 1;
} else {
	$group_page = 0;
}

$embed = elgg_extract('embed', $vars,0);
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
	$file_selected = $title;
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
	$file_selected = elgg_echo('gc_theme:no_file_selected');
	$add_to_river = elgg_view('input/checkbox',array('name'=>'add_to_river','value'=>'true'));
	$add_to_river = '<div class="gc-addtoriver-row"><div class="gc-input-file-2em"><label>'.elgg_echo('gc_theme:show_in_feed').'</label></div><div class="gc-addtoriver-cb">'.$add_to_river.'</div><span class="elgg-icon elgg-icon-info elgg-icon-info-top" title="'.elgg_echo('gc_theme:show_in_feed:help').'"></span></div>';
}
?>
<script type="text/javascript">
if (! "<?php echo $guid; ?>" ) {
	$(document).ready(function() {
	        //$('#multi-upload-button').css('pointer-events','none');
	        //$('#multi-upload-button').attr('disabled','disabled');
	        //$('#multi-upload-button').css('opacity',0.5);
	});
}
$('input#multi-upload-button[type=submit]').live('click',function(e) {
	if ($('.dropzone').get(0).dropzone.files.length > 0) {
		e.preventDefault();
		$('.dropzone').get(0).dropzone.processQueue();
		if (<?php echo $group_page; ?>) {
			window.location.replace(elgg.get_site_url()+"/file/group/<?php echo $container_guid;?>/all?sort_by=e.time_created&direction=desc");
		} else {
			window.location.replace(elgg.get_site_url()+"/file/owner/<?php echo elgg_get_logged_in_user_entity()->username;?>?sort_by=e.time_created&direction=desc");
		}
	}
});
$('input[type=file]').live('change',function() {check_file();});
function check_file(){
	var allowed_extensions_settings = "<?php echo elgg_get_plugin_setting('allowed_extensions', 'file_tools');?>";
	var allowed_extensions = allowed_extensions_settings.split(/, */);
        var ext,filename;
	if (<?php echo $embed; ?>){
        	filename = $('.elgg-input-file-embed').val().split('\\').pop().toLowerCase();
        	ext = $('.elgg-input-file-embed').val().split('.').pop().toLowerCase();
	} else {
        	filename = $('.elgg-input-file').val().split('\\').pop().toLowerCase();
        	ext = $('.elgg-input-file').val().split('.').pop().toLowerCase();
	}
        if(ext && $.inArray(ext, allowed_extensions) == -1) {
                alert(elgg.echo('gc_theme:file:not_allowed'));
                $('.elgg-input-file').val('');
        } else {
		if (filename.length > 30) {
    			filename =  filename.substr(0, 15) + '...' + filename.substr(filename.length-10, filename.length);
  		}
		$('.gc-file-selected').html(filename);
	}
        $('#multi-upload-button').css('opacity',1);
	$('#multi-upload-button').css('pointer-events','all');
	$('#multi-upload-button').removeAttr('disabled','disabled');
}
</script>
<div class="gc-input-file-row">
	<div class="gc-input-file-2em"><label><?php echo elgg_echo('title'); ?></label></div>
	<div class="gc-input-file-ib"><?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?></div>
	<div class="gc-input-file-browse">
<?php
if ($embed) {
	echo elgg_view('input/file', array('class' => 'elgg-input-file-embed', 'name' => 'upload'));
	$preview = "preview-embed";
	$button = "button-embed";
} else {
	echo elgg_view('input/file', array('name' => 'upload'));
	$preview = "preview";
	$button = "button";
}
?>
</div>
	<div class="gc-input-file-1em gc-file-selected"><?php echo $file_selected; ?></div>
</div>
<div id="elgg-dropzone-<?php echo $preview;?>"><div class="dz-message" data-dz-message><span><?php echo elgg_echo('gc_theme:dropzone:message'); ?></span></div></div>
<div class="gc-input-file-row">
	<div class="gc-input-file-2em"><label><?php echo elgg_echo('tags'); ?></label></div>
	<div class="gc-input-file-ib"><?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?></div>
	<div class="gc-input-file-1em"><label><?php echo elgg_echo('description'); ?></label></div>
	<div class="gc-input-file-desc"><?php echo elgg_view('input/plaintext', array('name' => 'description', 'value' => $desc)); ?></div>
</div>

<?php
	//allow user to add to river but not on by default
	if (! $guid) {
		echo $add_to_river;
	}
?>
<div id="gc-access-input" class="gc-input-file-row">
<?php 
//CAREFUL! file_tools plugin has to be on for his to work. Since it has been in use, no point
// in adding a test
if(file_tools_use_folder_structure()){
	$parent_guid = 0;
	if($file = elgg_extract("entity", $vars)){
		if($folders = $file->getEntitiesFromRelationship(array('relationship'=>FILE_TOOLS_RELATIONSHIP, 'inverse_relationship'=>true, 'limit'=>1))){
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

echo elgg_view('input/submit', array('id' => "multi-upload-$button", 'value' => $submit_label));

?>
</div>
