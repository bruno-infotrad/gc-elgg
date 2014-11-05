<?php

	/**
	 * Elgg long text input with the tinymce text editor intacts
	 * Displays a long text input field
	 * 
	 * @package ElggTinyMCE
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
<textarea class="input-textarea mceEditor" name="<?php echo $vars['internalname']; ?>" <?php echo $vars['js']; ?>><?php echo htmlentities($vars['value'], null, 'UTF-8'); ?></textarea> 
	 */

	global $tinymce_js_loaded;
	
	$input = rand(0,9999);
	
	if (!isset($tinymce_js_loaded)) $tinymce_js_loaded = false;

	if (!$tinymce_js_loaded) {
		$site_url = elgg_get_site_url();
	
?>
<!-- include tinymce -->
<script language="javascript" type="text/javascript" src="<?php $site_url; ?>mod/extended_tinymce/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
<!-- intialise tinymce, you can find other configurations here http://wiki.moxiecode.com/examples/tinymce/installation_example_01.php -->
<script language="javascript" type="text/javascript">
tinyMCE.init({
	selector : "mceEditor",
        theme: "modern",
        skin : "lightgray",
        language : "<?php echo extended_tinymce_get_site_language(); ?>",
        relative_urls : false,
        remove_script_host : false,
        document_base_url : elgg.config.wwwroot,
        plugins: "advlist autolink autosave charmap code compat3x emoticons fullscreen hr image insertdatetime link lists paste preview print searchreplace table textcolor wordcount",
        menubar: false,
        toolbar1: "preview fullscreen | searchreplace | styleselect | fontselect | fontsizeselect",
        toolbar2: "undo redo | bullist numlist | outdent indent | bold italic underline | alignleft aligncenter alignright alignjustify",
        toolbar3: "inserttime | charmap | hr | table | forecolor backcolor | link unlink | image | emoticons | blockquote" + (elgg.is_admin_logged_in() ? " | code" : ""),
        width : "99%",
        browser_spellcheck : true,
        image_advtab: true,
        paste_data_images: false,
        insertdate_formats: ["%I:%M:%S %p", "%H:%M:%S", "%Y-%m-%d", "%d.%m.%Y"],
        setup : function(ed) {
                    ed.on('Init', function() {
                        var edDoc = ed.getDoc();
                        if ("addEventListener" in edDoc) {
                            edDoc.addEventListener("drop", function(e) {
                                if (e.dataTransfer.files.length > 0) {
                                    e.preventDefault();
                                }
                            }, false);
                        }
                    });
                },
        content_css: elgg.config.wwwroot + 'mod/extended_tinymce/css/elgg_extended_tinymce.css'
    });
function toggleEditor(id) {
if (!tinyMCE.get(id))
	tinyMCE.execCommand('mceAddControl', false, id);
else
	tinyMCE.execCommand('mceRemoveControl', false, id);
}
</script>
<?php

		$tinymce_js_loaded = true;
	}

?>

<!-- show the textarea -->
<textarea class="input-textarea mceEditor" name="<?php echo $vars['internalname']; ?>" ><?php echo htmlentities($vars['value'], null, 'UTF-8'); ?></textarea> 
<div class="toggle_editor_container"><a class="toggle_editor" href="javascript:toggleEditor('<?php echo $vars['internalname']; ?>');"><?php echo elgg_echo('tinymce:remove'); ?></a></div>

<script type="text/javascript">
	$(document).ready(function() {
		$('textarea').parents('form').submit(function() {
			tinyMCE.triggerSave();
		});
	});
</script>
