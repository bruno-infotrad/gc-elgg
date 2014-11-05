<?php
$user = elgg_get_logged_in_user_entity();
$form_data .= "<div id='intro-join-group'>";
$form_data .= "<div>" . elgg_echo("gc_theme:intro:join_group:description") . "</div>";
$form_data .= elgg_view("input/groups_autocomplete", array("name" => "group_guid", 
			"id" => "intro-join-group-autocomplete",
			//"relationship" => "site"
			));
$form_data .= "</div>";
// show form
// show buttons '<div class="elgg-foot">';
if (preg_match('/intro/', $_SERVER['HTTP_REFERER'])) {
	$forward_url = $_SERVER['HTTP_REFERER'];
} else {
	$forward_url = elgg_get_site_url().'groups/all?filter=my_groups';
}
$form_data .= elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
$form_data .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('gc_theme:intro:join_group:button')));
$form_data .= '</div>';
echo $form_data;
?>
