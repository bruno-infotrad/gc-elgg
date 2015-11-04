<?php
/**
 * Members navigation
 */

$user = get_entity(elgg_get_logged_in_user_guid());
$tabs = array(
	'online' => array(
		'title' => elgg_echo('members:label:online'),
		'url' => "members/online",
		'selected' => $vars['selected'] == 'online',
	),
	'friendsof' => array(
		'title' => elgg_echo('friends:of'),
		'url' => "friendsof/".$user->username,
		'selected' => $vars['selected'] == 'friendsof',
	),
	'friends' => array(
		'title' => elgg_echo('friends'),
		'url' => "friends/".$user->username,
		'selected' => $vars['selected'] == 'friends',
	),
	'collections' => array(
		'title' => elgg_echo('friends:collections'),
		'url' => "collections/owner/".$user->username,
		'selected' => $vars['selected'] == 'collections',
	),
	'all' => array(
		'title' => elgg_echo('members:label:alphabetical'),
		'url' => "members/all",
		'selected' => $vars['selected'] == 'all',
	),
	'crappola' => array(
		'title' => '',
		'url' => "",
	),
);
if ($vars['selected'] == 'collections') {
	$add_button = elgg_view('output/url',array('href' => "/collections/add",
		'text' => elgg_echo('friends:collections:add'),
		'id' => 'new-colleague-collection',
		'class' => 'elgg-button elgg-button-action elgg-button-submit form-uneditable-input',));
} elseif ($vars['selected'] == 'friends') {
	$add_button = elgg_view('output/url',array('href' => "/friends/multi_invite",
		'text' => elgg_echo('friends:multi_invite'),
		'id' => 'new-colleague-collection',
		'class' => 'elgg-button elgg-button-action elgg-button-submit form-uneditable-input',));
}
//echo '<div class="elgg-head">'.$add_button.'<h1>'.elgg_echo('gc_theme:people').'</h1></div>';
echo elgg_view('members/navtabs', array('tabs' => $tabs));
