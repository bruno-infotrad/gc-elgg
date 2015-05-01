<?php
function gc_get_last_group_activity($group_guid, $site_guid = 0) {
	$dbprefix = elgg_get_config("dbprefix");
	$query = "SELECT max(r.posted) as posted FROM ".$dbprefix."river r JOIN ".$dbprefix."entities e ON r.object_guid = e.guid JOIN ".$dbprefix."entities eg ON e.container_guid = eg.guid JOIN ".$dbprefix."groups_entity ge ON eg.guid = ge.guid where e.container_guid = ".$group_guid;
	$posted = get_data_row($query)->posted;
	
	return $posted;
}

function gc_event_manager_get_registration_fiedtypes()     {
/* Very bad but not mine: attaching language dependent field types to metadata/metastring 
* and used with in_array test in * mod/event/views/default/event_manager/registration/question.php
* So the strings below have to match the contents of the language file put it cannot be achieved
* programmatically, hence the hack
*/
	$result = array(
		'Textfield' => 'text',
		'Texte' => 'text',
		'Textarea' => 'plaintext',
		'Boîte de texte' => 'plaintext',
		'Dropdown' => 'dropdown',
		'Liste' => 'dropdown',
		"Radiobutton" => 'radio',
		"Bouton d'options" => 'radio'
	);
	return $result;
}
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
