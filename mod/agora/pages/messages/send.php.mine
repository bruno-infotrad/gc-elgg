<?php
/**
* Compose a message
*
* @package ElggMessages
*/

gatekeeper();
elgg_load_library('elgg:messages');
$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('messages:add');

elgg_push_breadcrumb($title);

$params = messages_prepare_form_vars((int)get_input('send_to'));
//$params['friends'] = $page_owner->getFriends('', 500);
$params['friends'] = gc_get_user_friends($page_owner->getGUID(), '',500,0);
$content = elgg_view_form('messages/send', array(), $params);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);

function gc_get_user_friends($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0) {

        $dbprefix = elgg_get_config('dbprefix');
        return elgg_get_entities_from_relationship(array(
                'relationship' => 'friend',
                'relationship_guid' => $user_guid,
                'type' => 'user',
                'subtype' => $subtype,
                'limit' => $limit,
                'joins' => array("join {$dbprefix}users_entity u on e.guid = u.guid"),
                'wheres' => array("u.banned = 'no'"),
                'order_by' => 'u.name asc',
                'offset' => $offset
        ));
}
