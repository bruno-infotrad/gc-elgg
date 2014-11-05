<?php
$container_guid = get_input('container_guid');
$offset = get_input('offset');
$user = get_entity($container_guid);
$options=array();
$options['subject_guid'] = $user->getGUID();
$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
$options['body_class'] = 'new-feed';

$stream = elgg_list_river($options);
echo $stream;
