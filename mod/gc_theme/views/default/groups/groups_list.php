<?php
$tabs = array(
	'a' => array( 'text' => 'A', 'href' => 'groups/list?filter=a', 'link_class'=>'group-list-item', 'priority' => 1,),
	'b' => array( 'text' => 'B', 'href' => 'groups/list?filter=b', 'link_class'=>'group-list-item', 'priority' => 2,),
	'c' => array( 'text' => 'C', 'href' => 'groups/list?filter=c', 'link_class'=>'group-list-item', 'priority' => 3,),
	'd' => array( 'text' => 'D', 'href' => 'groups/list?filter=d', 'link_class'=>'group-list-item', 'priority' => 4,),
	'e' => array( 'text' => 'E', 'href' => 'groups/list?filter=e', 'link_class'=>'group-list-item', 'priority' => 5,),
	'f' => array( 'text' => 'F', 'href' => 'groups/list?filter=f', 'link_class'=>'group-list-item', 'priority' => 6,),
	'g' => array( 'text' => 'G', 'href' => 'groups/list?filter=g', 'link_class'=>'group-list-item', 'priority' => 7,),
	'h' => array( 'text' => 'H', 'href' => 'groups/list?filter=h', 'link_class'=>'group-list-item', 'priority' => 8,),
	'i' => array( 'text' => 'I', 'href' => 'groups/list?filter=i', 'link_class'=>'group-list-item', 'priority' => 9,),
	'j' => array( 'text' => 'J', 'href' => 'groups/list?filter=j', 'link_class'=>'group-list-item', 'priority' => 10,),
	'k' => array( 'text' => 'K', 'href' => 'groups/list?filter=k', 'link_class'=>'group-list-item', 'priority' => 11,),
	'l' => array( 'text' => 'L', 'href' => 'groups/list?filter=l', 'link_class'=>'group-list-item', 'priority' => 12,),
	'm' => array( 'text' => 'M', 'href' => 'groups/list?filter=m', 'link_class'=>'group-list-item', 'priority' => 13,),
	'n' => array( 'text' => 'N', 'href' => 'groups/list?filter=n', 'link_class'=>'group-list-item', 'priority' => 14,),
	'o' => array( 'text' => 'O', 'href' => 'groups/list?filter=o', 'link_class'=>'group-list-item', 'priority' => 15,),
	'p' => array( 'text' => 'P', 'href' => 'groups/list?filter=p', 'link_class'=>'group-list-item', 'priority' => 16,),
	'q' => array( 'text' => 'Q', 'href' => 'groups/list?filter=q', 'link_class'=>'group-list-item', 'priority' => 17,),
	'r' => array( 'text' => 'R', 'href' => 'groups/list?filter=r', 'link_class'=>'group-list-item', 'priority' => 18,),
	's' => array( 'text' => 'S', 'href' => 'groups/list?filter=s', 'link_class'=>'group-list-item', 'priority' => 19,),
	't' => array( 'text' => 'T', 'href' => 'groups/list?filter=t', 'link_class'=>'group-list-item', 'priority' => 20,),
	'u' => array( 'text' => 'U', 'href' => 'groups/list?filter=u', 'link_class'=>'group-list-item', 'priority' => 21,),
	'v' => array( 'text' => 'V', 'href' => 'groups/list?filter=v', 'link_class'=>'group-list-item', 'priority' => 22,),
	'w' => array( 'text' => 'W', 'href' => 'groups/list?filter=w', 'link_class'=>'group-list-item', 'priority' => 23,),
	'x' => array( 'text' => 'X', 'href' => 'groups/list?filter=x', 'link_class'=>'group-list-item', 'priority' => 24,),
	'y' => array( 'text' => 'Y', 'href' => 'groups/list?filter=y', 'link_class'=>'group-list-item', 'priority' => 25,),
	'z' => array( 'text' => 'Z', 'href' => 'groups/list?filter=z', 'link_class'=>'group-list-item', 'priority' => 26,),
	'nonalpha' => array( 'text' => elgg_echo('groups:list:other'), 'href' => 'groups/list?filter=nonalpha', 'link_class'=>'group-list-item', 'priority' => 27,),
);

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
