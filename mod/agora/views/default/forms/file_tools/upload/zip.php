<?php

	$page_owner = elgg_get_page_owner_entity();
	
	$form_body = "<div>" . elgg_echo("file_tools:upload:form:zip:info") . "</div>";
	
	$form_body .= "<div>";
	$form_body .= "<label>" . elgg_echo("file_tools:upload:form:choose") . "</label><br />";
	$form_body .= elgg_view("input/file", array("name" => "zip_file"));
	$form_body .= "</div>";
	
/*
<div id="gc-input-file-row">
        <?php if(file_tools_use_folder_structure()){ ?>
                <div id="gc-input-file-1em"><label><?php echo elgg_echo("file_tools:forms:edit:parent"); ?></label></div>
                <div id="gc-input-file-ib"><?php echo elgg_view("input/folder_select", array("name" => "folder_guid", "value" => get_input('parent_guid'), "id" => "file_tools_file_parent_guid"));?></div>
        <?php }?>

                        <div id="gc-input-file-1em"><label><?php echo elgg_echo('access'); ?></label></div>
                        <div id="gc-input-file-ib"><?php echo elgg_view('input/access', array('name' => 'access_id', 'id' => 'file_tools_file_access_id')); ?></div>
        </div>
*/
	$form_body .= "<div class=\"gc-input-file-row\">";
	if(file_tools_use_folder_structure()){
		$form_body .= "<div class=\"gc-input-file-1em\">";
		$form_body .= "<label>".elgg_echo("file_tools:forms:edit:parent") . "</label></div>";
		$form_body .= "<div class=\"gc-input-file-ib\">";
		$form_body .= elgg_view("input/folder_select", array("name" => "parent_guid", "container_guid" => $page_owner->getGUID()));
		$form_body .= "</div>";
	}
	
	$form_body .= "<div class=\"gc-input-file-1em\">";
	$form_body .= "<label>" . elgg_echo("access") . "</label></div>";
	$form_body .= "<div class=\"gc-input-file-ib\">";
	$form_body .= elgg_view("input/access", array("name" => "access_id"));
	$form_body .= "</div>";
	$form_body .= "</div>";
	
	$form_body .= "<div><div class='elgg-foot gc-input-file-1em'>";
	$form_body .= elgg_view("input/hidden", array("name" => "container_guid", "value" => $page_owner->getGUID()));
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("upload")));
	$form_body .= "</div>";
	$form_body .= "</div>";
	
	echo $form_body;
