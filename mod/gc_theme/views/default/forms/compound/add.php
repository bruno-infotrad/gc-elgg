<?php
/**
 * Wire add form body
 *
 * @uses $vars['post']
 */
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
elgg_load_js('lightbox');
elgg_load_css('lightbox');
elgg_load_js('elgg.thewire');
elgg_load_js('elgg.contribute_to');

$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
//$container_guid = elgg_extract('container_guid', $vars);
$container_guid = elgg_get_page_owner_guid();
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

$post = elgg_extract('post', $vars);
$text = elgg_echo('post');
/*
if ($post) {
	$text = elgg_echo('thewire:reply');
}
*/

if ($post) {
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $post->guid,
	));
}

echo elgg_view('input/plaintext', array(
	'name' => 'body',
	'value' => $post->description,
	'class' => 'mtm',
	'id' => 'thewire-textarea',
	'onfocus' => "elgg.user_handle('thewire-textarea')",
));
?>
<div id="browser" class="elgg-foot mts">
<div id="thewire-characters-remaining">
	<span>0</span> <?php echo elgg_echo('thewire:chartyped'); ?>
</div>
<?php
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
        echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}
echo elgg_view('input/checkbox',array('name'=>'exec_content','value' => 'true','id' => 'thewire-exec-content'));
echo elgg_echo('gc_theme:exec_content');

echo elgg_view('input/submit', array(
	'value' => elgg_echo('gc_theme:contribute'),
	'id' => 'thewire-submit-button',
	'disabled' =>'disabled',
));
//echo elgg_view_menu('contribute_to');
echo elgg_view('output/url', array(
	'text' => elgg_echo('gc_theme:contribute_to'),
	'href' => $vars["url"].'contribute_to',
	'class' => 'elgg-lightbox elgg-button elgg-button-submit',
	//"class" => "elgg-button elgg-button-action profile-manager-popup",
	'id' => 'thewire-contribute-to',
));
?>
</div>
<script>
var exec_content_warning = "<?php echo elgg_echo('gc_theme:exec_content:warning');?>";
$('input#thewire-exec-content').live('click',function(event){
	if ($('input#thewire-exec-content').is(':checked')) {
		if (confirm(exec_content_warning) == false) {
        	        event.preventDefault();
        	        return;
        	}
        }
});
$('#thewire-contribute-to').attr('disabled','disabled');
$('#thewire-contribute-to').css('pointer-events','none');
$("#thewire-textarea").live('keyup', function(){
	if ($.trim($("#thewire-textarea").val())) {
		if (!$('input#thewire-exec-content').is(':checked')) {
			$('#thewire-contribute-to').css('opacity','1.0');
			$('#thewire-contribute-to').css('pointer-events','all');
			$('#thewire-contribute-to').removeAttr('disabled');
		};
		$('input#thewire-submit-button:disabled').val(false);
		$('#thewire-submit-button').css('opacity','1.0');
	} else {
		$('#thewire-contribute-to').css('opacity','0.5');
		$('#thewire-contribute-to').css('pointer-events','none');
		$('#thewire-contribute-to').attr('disabled','disabled');
		$('input#thewire-submit-button.elgg-button.elgg-button-submit:disabled').val(true);
		$('#thewire-submit-button').css('opacity','0.5');
	};
});
$('input#thewire-exec-content').change(function () {
	if ($.trim($("#thewire-textarea").val())) {
		if (!$('input#thewire-exec-content').is(':checked')) {
			$('#thewire-contribute-to').css('opacity','1.0');
			$('#thewire-contribute-to').css('pointer-events','all');
			$('#thewire-contribute-to').removeAttr('disabled');
		} else {
			$('#thewire-contribute-to').css('opacity','0.5');
			$('#thewire-contribute-to').css('pointer-events','none');
			$('#thewire-contribute-to').attr('disabled','disabled');
		};
	};
});
</script>
