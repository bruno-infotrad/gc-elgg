<?php
function gc_find_active_users($seconds, $limit, $offset, $count = false) {
        global $CONFIG;
        $seconds = (int)$seconds;
        $limit = (int)$limit;
        $offset = (int)$offset;
        $params = array('seconds' => $seconds, 'limit' => $limit, 'offset' => $offset, 'count' => $count);
        $time = time() - $seconds;
        $data = elgg_get_entities(array(
                'type' => 'user',
                'limit' => $limit,
                'offset' => $offset,
                'count' => $count,
                'joins' => array("join {$CONFIG->dbprefix}users_entity u on e.guid = u.guid"),
                'wheres' => array("u.last_action >= {$time}"),
                'order_by' => "u.username asc"
        ));
        return $data;
}
?>
