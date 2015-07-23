<?php
$container_guid = get_input('container_guid');
$offset = get_input('offset');
$base_url = get_input('base_url');
$user = get_entity($container_guid);
$options=array();
$options['subject_guid'] = $user->getGUID();
$options['wheres']=array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.action_type != 'vote'");
$options['body_class'] = 'new-feed';
$options['base_url'] = $base_url;

$stream = elgg_list_river($options);
echo $stream;
