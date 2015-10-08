<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */

//echo elgg_view('page/elements/owner_block', $vars);

elgg_load_js('elgg.new_feeds');
elgg_load_js('elgg.new_messages');
$user = elgg_get_logged_in_user_entity();
echo "<div class='elgg-profile-avatar'>";
echo elgg_view_entity_icon($user, 'small');
$username = preg_replace('/ -\w+/','',$user->name);
echo '<p>'.$username.'</p>';
echo "</div>";
 
echo elgg_view_menu('page', array('sort_by' => 'priority','show_section_headers' => true));

// optional 'sidebar' parameter
if (isset($vars['sidebar'])) {
	echo $vars['sidebar'];
}

// @todo deprecated so remove in Elgg 2.0
// optional second parameter of elgg_view_layout
if (isset($vars['area2'])) {
	echo $vars['area2'];
}

// @todo deprecated so remove in Elgg 2.0
// optional third parameter of elgg_view_layout
if (isset($vars['area3'])) {
	echo $vars['area3'];
}
echo elgg_view_menu('extras', array(
	'sort_by' => 'priority',
));
echo elgg_view('page/elements/sidebar_bottom');
?>
