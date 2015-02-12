<?php
/**
 * Elgg add comment action
 *
 * Added get hashtag from the wire
 * @package Elgg.Core
 * @subpackage Comments
 */

$entity_guid = (int) get_input('entity_guid');
$comment_text = get_input('generic_comment');
$raw_comment_text = $comment_text;

if (empty($comment_text)) {
	register_error(elgg_echo("generic_comment:blank"));
	forward(REFERER);
}

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("generic_comment:notfound"));
	forward(REFERER);
}
//Check river exists, if it does not create it (e.g. for zip files)
$options = array('object_guids' => $entity->getGUID(),'action_types' => 'create');
$river_item = elgg_get_river($options);
if (count($river_item) == 0) {
	add_to_river('river/object/'.$entity->getSubtype().'/create', 'create', $entity->owner_guid, $entity->getGUID(),"",$entity->time_created,0);
}

$user = elgg_get_logged_in_user_entity();
// links
$comment_text = parse_urls($comment_text);
//Filter text for email addresses twitter IDs and hastags
// usernames
//$comment_text = preg_replace('/(^|[^\w])@([\w]+)/', '$1<a href="' . $CONFIG->wwwroot . 'profile/$2">@$2</a>', $comment_text);
if ($atoms=preg_split('/\s+|<br>|,/', $raw_comment_text)) {
        foreach ($atoms as $atom) {
                $username=preg_replace('/^@/','',$atom,-1,$count);
                if ($count) {
                        if($to_user=get_user_by_username($username)) {
                        	$comment_text = preg_replace('/@'.$username.'/', '<a href="profile/'.$username.'">@'.$username.'</a>', $comment_text);
				if ($to_user->guid != $entity->owner_guid && $to_user->guid != $user->guid) {
					$from = $user->guid;
					$subject = elgg_echo("gc_theme:comment:notify_ref:subject");
					$to = $to_user->guid;
					$body = elgg_echo("gc_theme:comment:notify_ref:body",array($user->name,$entity->getURL()));
					notify_user($to, $from, $subject, $body);
					//notify_user($to, $from, $subject, $body,NULL,'site');
				}
			}
                }
        }
}
// email addresses
$comment_text = preg_replace('/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i', '$1<a href="mailto:$2@$3">$2@$3</a>', $comment_text);
// hashtags
$comment_text = preg_replace('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', '$1<a href="' . $CONFIG->wwwroot . 'thewire/tag/$2">#$2</a>', $comment_text);
$comment_text = trim($comment_text);
$annotation = create_annotation($entity->guid, 'generic_comment', $comment_text, "", $user->guid, $entity->access_id);
// tell user annotation posted
if (!$annotation) {
	register_error(elgg_echo("generic_comment:failure"));
	forward(REFERER);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {
	notify_user($entity->owner_guid, $user->guid, elgg_echo('generic_comment:email:subject'), elgg_echo('generic_comment:email:body', array( $entity->title, $user->name, $comment_text, $entity->getURL(), $user->name, $user->getURL())));
}

system_message(elgg_echo("generic_comment:posted"));

//add to river
add_to_river('river/annotation/generic_comment/create', 'comment', $user->guid, $entity->guid, "", 0, $annotation);

// Forward to the page the action occurred on
forward(REFERER);
