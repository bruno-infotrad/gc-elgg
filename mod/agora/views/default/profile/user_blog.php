<?php
if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$user = elgg_get_page_owner_entity();

$options=array();
$options = array(
                'type' => 'object',
                'subtype' => 'blog',
                'full_view' => false,
                'subject_guid' => $user->getGUID(),
        );

$options['body_class'] = 'new-feed';

$stream = elgg_list_river($options);
echo $stream;
