<?php
$page_owner = elgg_get_page_owner_entity();

$form_body = "<div>" . elgg_echo("file_tools:upload:form:zip:info") . "</div>";
$form_body .= "<div id='zip' class='gc-input-file-row'>";
$form_body .= "<label>" . elgg_echo("file_tools:upload:form:choose") . "</label>";
$form_body .= '<div class="gc-input-file-browse">';
$form_body .= elgg_view("input/file", array("name" => "zip_file","id" => "zip_file"));
$form_body .= "</div>";
$file_selected = elgg_echo('gc_theme:no_file_selected');
$form_body .= '<div class="gc-input-file-1em gc-file-selected">'.$file_selected.'</div>';
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
?>
<script type="text/javascript">
$('#zip').filter(":input").live('change',function() {check_zip();});
function check_zip(){
        var ext,filename;
        	filename = $('#zip_file').val().split('\\').pop().toLowerCase();
        	ext = $('#zip_file').val().split('.').pop().toLowerCase();
        if( ext != 'zip') {
                alert(elgg.echo('gc_theme:file:not_allowed'));
                $('.elgg-input-file').val('');
        } else {
		if (filename.length > 30) {
    			filename =  filename.substr(0, 15) + '...' + filename.substr(filename.length-10, filename.length);
  		}
		$('.gc-file-selected').html(filename);
	}
}
</script>
