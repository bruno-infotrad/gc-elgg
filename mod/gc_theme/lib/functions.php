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

function gc_pages_get_navigation_tree($container) {
	if (!elgg_instanceof($container)) {
		return;
	}
	$dbprefix = elgg_get_config("dbprefix");

	$top_pages = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'page_top',
		'container_guid' => $container->getGUID(),
		'joins' => array("JOIN " . $dbprefix . "objects_entity o ON e.guid=o.guid"),
		'order_by' => 'o.title asc',
		'limit' => false,
	));

	/* @var ElggBatch $top_pages Batch of top level pages */

	$tree = array();
	$depths = array();

	foreach ($top_pages as $page) {
		$tree[] = array(
			'guid' => $page->getGUID(),
			'title' => $page->title,
			'url' => $page->getURL(),
			'depth' => 0,
		);
		$depths[$page->guid] = 0;

		$stack = array();
		array_push($stack, $page);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				$tree[] = array(
					'guid' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_guid' => $parent->getGUID(),
					'depth' => $depths[$parent->guid] + 1,
				);
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
			}
		}
	}
	return $tree;
}
