<?php
	/**
	 * Form for inviting mutiple users at once
	 */

$user = elgg_get_logged_in_user_entity();
$form_data .= "<div id='multi_invite_users'>";
$form_data .= "<div>" . elgg_echo("agora:friends:multi_invite:description") . "</div>";
$form_data .= elgg_view("input/colleagues_autocomplete", array("name" => "user_guid", 
			"id" => "multi_invite_autocomplete",
			//"relationship" => "notfriends"
			"relationship" => "site"
			));
$form_data .= "</div>";
// show form
// show buttons '<div class="elgg-foot">';
if (preg_match('/intro/', $_SERVER['HTTP_REFERER'])) {
	$forward_url = $_SERVER['HTTP_REFERER'];
} else {
	$forward_url = elgg_get_site_url().'friends/'.$user->username;
}
$form_data .= elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
$form_data .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('add')));
$form_data .= '</div>';
echo $form_data;
?>
