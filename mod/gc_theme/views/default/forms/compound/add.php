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
//elgg_require_js('dropzone/dropzone');

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

//echo elgg_view('input/plaintext', array(
echo elgg_view('input/longtext', array(
	'name' => 'body',
	'value' => $post->description,
	'class' => 'mtm',
	'id' => 'thewire-textarea',
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
	'href' => elgg_get_site_url().'contribute_to',
	'class' => 'elgg-lightbox elgg-button elgg-button-submit',
	//"class" => "elgg-button elgg-button-action profile-manager-popup",
	'id' => 'thewire-contribute-to',
));
?>
</div>
