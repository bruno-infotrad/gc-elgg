<?php
/**
 * Action for adding and editing comments
 *
 * @package Elgg.Core
 * @subpackage Comments
 */

$entity_guid = (int) get_input('entity_guid', 0, false);
$comment_guid = (int) get_input('comment_guid', 0, false);
$comment_text = get_input('generic_comment');
$raw_comment_text = strip_tags($comment_text);
$is_edit_page = (bool) get_input('is_edit_page', false, false);

if (empty($comment_text)) {
	register_error(elgg_echo("generic_comment:blank"));
	forward(REFERER);
}
//Add profile URL
$comment_text = preg_replace('/(^|[^\w])@([\w]+)/', '$1<a href="' . elgg_get_site_url() . 'profile/$2">@$2</a>', $comment_text);
// email addresses
$comment_text = preg_replace('/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i', '$1<a href="mailto:$2@$3">$2@$3</a>', $comment_text);
// hashtags
$comment_text = preg_replace('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', '$1<a href="' . $CONFIG->wwwroot . 'thewire/tag/$2">#$2</a>', $comment_text);
if ($comment_guid) {
	// Edit an existing comment
	$comment = get_entity($comment_guid);

	if (!elgg_instanceof($comment, 'object', 'comment')) {
		register_error(elgg_echo("generic_comment:notfound"));
		forward(REFERER);
	}
	if (!$comment->canEdit()) {
		register_error(elgg_echo("actionunauthorized"));
		forward(REFERER);
	}

	$comment->description = $comment_text;
	if ($comment->save()) {
		system_message(elgg_echo('generic_comment:updated'));
	} else {
		register_error(elgg_echo('generic_comment:failure'));
	}
} else {
	// Create a new comment on the target entity
	$entity = get_entity($entity_guid);
	if (!$entity) {
		register_error(elgg_echo("generic_comment:notfound"));
		forward(REFERER);
	}

	$user = elgg_get_logged_in_user_entity();

	$comment = new ElggComment();
	$comment->description = $comment_text;
	$comment->owner_guid = $user->getGUID();
	$comment->container_guid = $entity->getGUID();
	$comment->access_id = $entity->access_id;
	$guid = $comment->save();

	if (!$guid) {
		register_error(elgg_echo("generic_comment:failure"));
		forward(REFERER);
	}

	// Notify if poster wasn't owner
	if ($entity->owner_guid != $user->guid) {
		$owner = $entity->getOwnerEntity();

		notify_user($owner->guid,
			$user->guid,
			elgg_echo('generic_comment:email:subject', array(), $owner->language),
			elgg_echo('generic_comment:email:body', array(
				$entity->title,
				$user->name,
				$comment_text,
				$entity->getURL(),
				$user->name,
				$user->getURL()
			), $owner->language),
			array(
				'object' => $comment,
				'action' => 'create',
			)
		);
	}
	//Notify any user referred by handle in comment text
	if ($atoms=preg_split('/\s+|<br>|,/', $raw_comment_text)) {
	        foreach ($atoms as $atom) {
	                $username=preg_replace('/^@/','',$atom,-1,$count);
	                if ($count) {
	                        if($to_user=get_user_by_username($username)) {
	                        	//$comment_text = preg_replace('/@'.$username.'/', '<a href="profile/'.$username.'">@'.$username.'</a>', $comment_text);
					if ($to_user->guid != $entity->owner_guid && $to_user->guid != $user->guid) {
						$to = $to_user->guid;
						notify_user($to,
							$user->guid,
							elgg_echo('gc_theme:comment:notify_ref:subject', array(), $to_user->language),
							elgg_echo('gc_theme:comment:notify_ref:body', array(
								$user->name,
								$entity->getURL(),
							), $to_user->language),
							array(
								'object' => $comment,
								'action' => 'create',
							)
						);
					}
				}
	                }
	        }
	}

	// Add to river
	elgg_create_river_item(array(
		'view' => 'river/object/comment/create',
		'action_type' => 'comment',
		'subject_guid' => $user->guid,
		'object_guid' => $guid,
		'target_guid' => $entity_guid,
	));

	system_message(elgg_echo('generic_comment:posted'));
}

if ($is_edit_page) {
	forward($comment->getURL());
}
echo $guid;
forward(REFERER);