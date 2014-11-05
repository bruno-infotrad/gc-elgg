<?php
$user = elgg_get_logged_in_user_entity();
$selected = $vars['selected'];
$num_invitations = count(groups_get_invited_groups($user->getGUID()));
$request_options = array(
        "type" => "group",
        "relationship" => "membership_request",
        "relationship_guid" => $user->getGUID(),
	"count"=> true,
        "limit" => false
);
$num_requests = elgg_get_entities_from_relationship($request_options);
$num_tba = $num_invitations+$num_requests;
$text='';
if ($num_tba > 0) {
	$text = "<span id=\"gc-group-invitations\"> ".$num_tba."</span>";
}
$own_group_list = elgg_get_entities(array( 'type' => 'group', 'owner_guid' => $user->getGUID(),));
foreach ($own_group_list as $own_group) {
	$join_requests = $join_requests+elgg_get_entities_from_relationship(array(
		'type' => 'user',
		'relationship' => 'membership_request',
		'relationship_guid' => $own_group->getGUID(),
		'inverse_relationship' => true,
		'limit' => 0,
		'count' => true,
	));
}
if ($join_requests > 0) {
	$join_text = "<span id=\"gc-group-invitations\"> ".$join_requests."</span>";
}

$tabs = array(
	'newest' => array(
		'text' => elgg_echo('groups:newest'),
		'href' => 'groups/all?filter=newest',
		'priority' => 300,
	),
/*
	'popular' => array(
		'text' => elgg_echo('groups:popular'),
		'href' => 'groups/all?filter=popular',
		'priority' => 300,
	),
	'discussion' => array(
		'text' => elgg_echo('groups:latestdiscussion'),
		'href' => 'groups/all?filter=discussion',
		'priority' => 400,
	),
*/
	'groups_i_own' => array(
		'text' => elgg_echo('groups:owned').$join_text,
		'href' => 'groups/all?filter=groups_i_own',
		'priority' => 200,
	),
	'my_groups' => array(
		'text' => elgg_echo('groups:yours'),
		'href' => 'groups/all?filter=my_groups',
		'priority' => 100,
	),
	'group_invitations' => array(
		'text' => elgg_echo('groups:invitations').$text,
		'href' => 'groups/invitations/'.$user->username,
		'priority' => 400,
	),
	'crappola' => array(
		'text' => '',
		'href' => '',
		'priority' => 500,
	),
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
