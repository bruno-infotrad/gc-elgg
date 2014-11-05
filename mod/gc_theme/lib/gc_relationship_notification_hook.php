<?php
function gc_relationship_notification_hook($event, $type, $object) {

        $user_one = get_entity($object->guid_one);
        $user_two = get_entity($object->guid_two);

        return notify_user($object->guid_two,
                        $object->guid_one,
                        elgg_echo('friend:newfriend:subject', array($user_one->name)),
                        elgg_echo("friend:newfriend:body", array($user_one->name, $user_one->getURL(), elgg_get_site_url()))
        );
}
?>
