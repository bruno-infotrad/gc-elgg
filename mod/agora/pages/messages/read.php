<?php
/**
* Read a message page
*
* @package ElggMessages
*/

gatekeeper();

$message = get_entity(get_input('guid'));
$show_editor = get_input('show_editor');
if (!$message) {
	forward();
}

// mark the message as read
$message->readYet = true;

elgg_set_page_owner_guid($message->getOwnerGUID());
$page_owner = elgg_get_page_owner_entity();

$title = $message->title;

$inbox = false;
if ($page_owner->getGUID() == $message->toId) {
	$inbox = true;
	elgg_push_breadcrumb(elgg_echo('messages:inbox'), 'messages/inbox/' . $page_owner->username);
} else {
	elgg_push_breadcrumb(elgg_echo('messages:sent'), 'messages/sent/' . $page_owner->username);
}
elgg_push_breadcrumb($title);

$content = elgg_view_entity($message, array('full_view' => true));
if ($inbox) {
	if ($show_editor) {
		$class='';
	} else {
		$class = 'hidden mtl';
		if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid() && get_entity($message->fromId) instanceof ElggUser) {
			elgg_register_menu_item('title', array(
				'name' => 'reply',
				'href' => '#messages-reply-form',
				'text' => elgg_echo('messages:answer'),
				'link_class' => 'elgg-button elgg-button-action',
				'rel' => 'toggle',
			));
		}
	}
	$form_params = array(
		'id' => 'messages-reply-form',
		'class' => $class,
		'action' => 'action/messages/send',
	);
	$body_params = array('message' => $message);
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);

}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
