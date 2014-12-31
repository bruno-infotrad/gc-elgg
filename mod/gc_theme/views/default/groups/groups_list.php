<?php
$db_prefix = elgg_get_config("dbprefix");
$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
$tabs = array();
$ordre = 1;
foreach ($alpha as $char) {
        $search_filter = "like '".$char."%'";
	$count = elgg_get_entities(array( 'type' => 'group',
        	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
        	'wheres' => array("ge.name $search_filter"),
        	'count' => true,
        	'limit' => 10000
	));
	if ($count) {
		$tabs[$char] = array( 'text' => strtoupper($char), 'href' => 'groups/list?filter='.$char, 'link_class'=>'group-list-item', 'priority' => $ordre,);
	} else {
		$tabs[$char] = array( 'text' => strtoupper($char), 'href' => '#','link_class' => 'group-list-item', 'item_class' => 'pdg', 'priority' => $ordre,);
	}
	$ordre++;
}
$search_filter = "REGEXP '^[^A-Za-z]'";
$count = elgg_get_entities(array( 'type' => 'group',
       	'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
       	'wheres' => array("ge.name $search_filter"),
       	'count' => true,
       	'limit' => 10000
));
if ($count) {
	$tabs['nonalpha'] = array( 'text' => elgg_echo('groups:list:other'), 'href' => 'groups/list?filter=nonalpha', 'link_class' => 'group-list-item', 'priority' => $ordre,);
} else {
	$tabs['nonalpha'] = array( 'text' => elgg_echo('groups:list:other'), 'link_class' => 'group-list-item', 'item_class' => 'pdg', 'priority' => $ordre,);
}
// sets default selected item
if ($selected) {
	$tabs[$selected]['selected'] = true;
} elseif (strpos(full_url(), 'filter') === false) {
	$tabs['my_groups']['selected'] = true;
}

foreach ($tabs as $name => $tab) {
	$tab['name'] = $name;

	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
