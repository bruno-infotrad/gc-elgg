<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 */

	$page_owner = elgg_get_page_owner_entity();
	$container_guid = $page_owner->getGUID();
	$site_url = elgg_get_site_url();
	
	if(elgg_instanceof($page_owner, "group", null, "ElggGroup")){
		$return_url = $site_url . "file/group/" . $page_owner->getGUID() . "/all";
	} else {
		$return_url = $site_url . "file/owner/" . $page_owner->username;
	}
	
	// load JS
	elgg_load_js("elgg.dropzone");
?>

<fieldset>
<div id="elgg-dropzone-preview-multi"><div class="dz-message" data-dz-message><span><?php echo elgg_echo('gc_theme:dropzone_multi:message'); ?></span></div></div>
	
	<?php echo elgg_view('input/hidden', array('class' => 'hidden', 'name' => 'upload')); ?>
	<div id="gc-access-input" class="gc-input-file-row">
	<?php if(file_tools_use_folder_structure()){ ?>
		<div class="gc-input-file-1em"><label><?php echo elgg_echo("file_tools:forms:edit:parent"); ?></label></div>
		<div class="gc-input-file-ib"><?php echo elgg_view("input/folder_select", array("name" => "folder_guid", "value" => get_input('parent_guid'), "id" => "file_tools_file_parent_guid"));?></div>
	<?php }?>
	
			<div class="gc-input-file-1em"><label><?php echo elgg_echo('access'); ?></label></div>
			<div class="gc-input-file-ib"><?php echo elgg_view('input/access', array('name' => 'access_id', 'id' => 'file_tools_file_access_id')); ?></div>
		<div class="elgg-foot gc-input-file-1em">
		<?php 
			echo elgg_view('input/securitytoken');
			echo elgg_view("input/hidden", array("name" => "container_guid", "value" => $container_guid));
			echo elgg_view("input/hidden", array("name" => "PHPSESSID", "value" => session_id()));
							
			echo elgg_view("input/submit", array("value" => elgg_echo("save"), "id" => "multi-upload-button2"));
		?>
		</div>
	</div>
</fieldset>
<script type="text/javascript">
//Dropzone.options.previewsContainer = "#elgg-dropzone-preview-multi";
//new Dropzone(".dropzone-multi");
new Dropzone(".dropzone-multi", {previewsContainer: "#elgg-dropzone-preview-multi"});
$('input#multi-upload-button2[type=submit]').live('click',function(e) {
	e.preventDefault();
	$('.dropzone-multi').get(0).dropzone.processQueue();
	window.location.replace(elgg.get_site_url()+"/file/owner/<?php echo elgg_get_logged_in_user_entity()->username;?>");
});
</script>
