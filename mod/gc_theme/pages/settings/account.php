<?php
// Only logged in users
gatekeeper();
// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner_entity()) || (!elgg_get_page_owner_entity()->canEdit())) {
	//elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
	register_error(elgg_echo('noaccess'));
	forward('/');

}

$title = elgg_echo('usersettings:user');

$content = elgg_view('core/settings/account');

$params = array(
        'content' => $content,
        //'sidebar' => elgg_view('friends/sidebar'),
        'title' => $title,
        'filter_override' => elgg_view('settings/nav', array('selected' => 'settings')),
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
