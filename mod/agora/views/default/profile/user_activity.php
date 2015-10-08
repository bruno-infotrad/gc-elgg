<?php
if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$user = elgg_get_page_owner_entity();
$options=array();
$options['subject_guid'] = $user->getGUID();
$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
$options['body_class'] = 'new-feed';

$stream = elgg_list_river($options);
echo $stream;

