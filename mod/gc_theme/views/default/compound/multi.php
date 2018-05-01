<?php
//elgg_load_js('elgg.contribute_to');
$owner = elgg_get_page_owner_entity();
if (!($owner instanceof ElggGroup)||($owner instanceof ElggGroup && $owner->thewire_enable != 'no')) {
	$thewire_enabled = 1;
	$tabs["thewire"] = array(
		'text' => elgg_view_agora_icon('wire') . elgg_echo("composer:object:thewire"),
		"href" => "#",
		"rel" => "users",
		"priority" => 300,
		"onclick" => "compound_switch_tab(\"thewire\");",
		"selected" => true
	);
	//$vars['class'] = 'dropzone';
	$form_data = "<div id='ckeditor_file_loader'></div>";
	$form_data .= "<div id='compound_thewire'>";
	$form_data .= elgg_view_form('compound/add',$vars);
	$form_data .= "</div>";
} else {
	elgg_load_library('elgg:pages');
	if (elgg_instanceof($owner, 'object')) {
        	$parent_guid = $owner->getGUID();
	}
	elgg_set_page_owner_guid($owner->getGUID());
	$vars = pages_prepare_form_vars(null, $parent_guid);
	$thewire_enabled = 0;
	$tabs["page"] = array(
		'text' => elgg_view_agora_icon('pages') . elgg_echo("composer:object:page"),
		"href" => "#",
		"rel" => "users",
		"priority" => 300,
		"onclick" => "compound_switch_tab(\"page\");",
		"selected" => true
	);
	//$vars['class'] = 'dropzone';
	$form_data = "<div id='ckeditor_file_loader'></div>";
	$form_data .= "<div id='compound_page'>";
	$form_data .= elgg_view_form('pages/edit',array(),$vars);
	$form_data .= "</div>";
}
$polls_enabled = 0;
if (!($owner instanceof ElggGroup)||($owner instanceof ElggGroup && $owner->polls_enable != 'no')) {
	$polls_enabled = 1;
	$tabs["polls"] = array(
		'text' => elgg_view_agora_icon('addpoll') . elgg_echo("composer:object:poll"),
		"href" => "#",
		"rel" => "users",
		"priority" => 400,
		"onclick" => "compound_switch_tab(\"polls\");"
	);
	$form_data .= "<div id='compound_poll'>";
	$form_data .= elgg_view_form('polls/edit',$vars);
	$form_data .= "</div>";
}
$files_enabled = 0;
if (!($owner instanceof ElggGroup)||($owner instanceof ElggGroup && $owner->file_enable != 'no')) {
	$files_enabled = 1;
	$tabs["files"] = array(
		'text' => elgg_view_agora_icon('addfile') . elgg_echo("composer:object:file"),
		"href" => "#",
		"rel" => "users",
		"priority" => 500,
		"onclick" => "compound_switch_tab(\"files\");"
	);
	elgg_load_library('elgg:file');
	$form_vars = array('enctype' => 'multipart/form-data', 'class' => 'dropzone');
	$body_vars = file_prepare_form_vars();
	$form_data .= "<div id='compound_file'>";
	$form_data .= elgg_view_form('file/upload', $form_vars, array_merge($body_vars, $vars));
	$form_data .= "</div>";
}
// build tabs
if(!empty($tabs)){
	foreach($tabs as $name => $tab){
		$tab["name"] = $name;
			
		elgg_register_menu_item("compound", $tab);
	}
	echo elgg_view_menu("compound", array("sort_by" => "priority",'class' => 'elgg-menu elgg-menu-composer elgg-menu-hz elgg-menu-composer-default ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all'));
}

// show form
echo '<div class="elgg-compound">';
echo '<div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
echo $form_data;
echo '</div>';
echo '</div>';
?>
<script type="text/javascript">
	function compound_switch_tab(tab){
		$('.elgg-menu-compound li').removeClass('elgg-state-selected');

                $('.elgg-menu-compound li.elgg-menu-item-' + tab).addClass('elgg-state-selected');
		var thewire_enabled = <?php echo $thewire_enabled; ?>;
		if (thewire_enabled) {
			switch(tab) {
				case "thewire":
				default:
					$('#compound_file').hide();
					$('#compound_poll').hide();
					
					$('#compound_thewire').show();
					break;
				case "polls":
					$('#compound_thewire').hide();
					$('#compound_file').hide();
					
					$('#compound_poll').show();
					break;
				case "files":
					$('#compound_thewire').hide();
					$('#compound_poll').hide();
					
					$('#compound_file').show();
					break;
			}
		} else {
			switch(tab) {
				case "page":
				default:
					$('#compound_file').hide();
					$('#compound_poll').hide();
					
					$('#compound_page').show();
					break;
				case "polls":
					$('#compound_page').hide();
					$('#compound_file').hide();
					
					$('#compound_poll').show();
					break;
				case "files":
					$('#compound_page').hide();
					$('#compound_poll').hide();
					
					$('#compound_file').show();
					break;
			}
		}
	}
</script>
