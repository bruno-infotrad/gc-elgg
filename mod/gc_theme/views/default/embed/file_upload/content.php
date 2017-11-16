<?php
/**
 * Upload a file through the embed interface
 */
$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form-embed dropzone-embed',
);
$body_vars = array('container_guid' => elgg_get_page_owner_guid(),'embed' => true);
echo elgg_view_form('file/upload', $form_vars, $body_vars);

// the tab we want to be forwarded to after upload is complete
echo elgg_view('input/hidden', array(
	'name' => 'embed_forward',
	'value' => 'file',
));
?>
<script type="text/javascript">
$(".dropzone-embed").dropzone({previewsContainer: "#elgg-dropzone-preview-embed"});
$('input#multi-upload-button-embed[type=submit]').live('click',function(e) {
	if ($('.dropzone-embed').get(0).dropzone.files.length > 0) {
        	e.preventDefault();
        	$('.dropzone-embed').get(0).dropzone.processQueue();
		var forward = $('input[name=embed_forward]').val();
		var url = elgg.normalize_url('embed/tab/' + forward);
		url = elgg.embed.addContainerGUID(url);
		$('.embed-wrapper').parent().load(url);
	}
});

</script>

