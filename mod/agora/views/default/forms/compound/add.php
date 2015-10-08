<?php

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
//elgg_load_js('lightbox');
//elgg_load_css('lightbox');
//elgg_load_js('elgg.thewire');
elgg_load_js('elgg.contribute_to');
//elgg_require_js('dropzone/dropzone');

$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
$guid = elgg_extract('guid', $vars, null);
$river_guid = elgg_extract('river_guid', $vars, null);

if (! $container_guid) {
	$container_guid = elgg_get_page_owner_guid();
	if (!$container_guid) {
		$container_guid = elgg_get_logged_in_user_guid();
	}
}
if ($guid) {
	$id = 'thewire-textarea-'.$guid;
} else {
	$id = 'thewire-textarea';
}
$thewire = elgg_extract('thewire', $vars);
// If thewire exists, it is an edit (or ajax edit)

echo elgg_view('input/longtext', array(
	'name' => 'gc_wire',
	'value' => $thewire->description,
	'class' => 'mtm',
	'id' => $id
));
?>
<div id="browser" class="elgg-foot mts">
<?php
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
        echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
	echo elgg_view('input/hidden', array( 'name' => 'river_guid', 'value' => $river_guid,));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
		//'id' => 'thewire-submit-button',
		'class' => 'agora-submit-button',
	));
	echo elgg_view('input/button', array(
		'value' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel mlm',
		'id' => 'agora-cancel-button',
		'href' => $entity ? $entity->getURL() : '#',
	));

} else {
	echo elgg_view('input/checkbox',array('name'=>'exec_content','value' => 'true','id' => 'thewire-exec-content'));
	echo elgg_echo('agora:exec_content');
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('agora:contribute'),
		'id' => 'thewire-submit-button',
		'disabled' =>'disabled',
	));
	echo elgg_view('output/url', array(
		'text' => elgg_echo('agora:contribute_to'),
		'href' => elgg_get_site_url().'contribute_to',
		'class' => 'elgg-lightbox elgg-button elgg-button-submit',
		//"class" => "elgg-button elgg-button-action profile-manager-popup",
		'id' => 'thewire-contribute-to',
	));
}
?>
</div>
