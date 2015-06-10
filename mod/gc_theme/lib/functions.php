<?php
function elgg_view_agora_icon($name, $class = '') {
        if ($class === true) {
                $class = 'float';
        }
        return "<span class=\"elgg-agora-icon elgg-agora-icon-$name $class\"></span>";
}
function gc_get_group_members($group_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false) {

        // in 1.7 0 means "not set."  rewrite to make sense.
        if (!$site_guid) {
                $site_guid = ELGG_ENTITIES_ANY_VALUE;
        }
	$dbprefix = elgg_get_config("dbprefix");

        return elgg_get_entities_from_relationship(array(
                'relationship' => 'member',
                'relationship_guid' => $group_guid,
                'inverse_relationship' => TRUE,
                'type' => 'user',
		'joins' => array("JOIN " . $dbprefix . "users_entity u ON e.guid=u.guid"),
		'wheres' => array("(u.banned = 'no')"),
                'limit' => $limit,
                'offset' => $offset,
                'count' => $count,
                'site_guid' => $site_guid
        ));
}
