<?php
$db_prefix = elgg_get_config("dbprefix");
$user = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($user->guid);
$options = array();
$offset = get_input('offset');
$options['offset'] = $offset;
$base_url = get_input('base_url');
$selected_tab = get_input('filter', 'my_groups');
switch ($selected_tab) {
        case 'groups_i_own':
		$page_owner = elgg_get_page_owner_entity();
                $content = elgg_list_entities(array(
			'base_url' => $base_url,
                        'type' => 'group',
        		'owner_guid' => elgg_get_logged_in_user_guid(),
			'full_view' => 'gc_summary',
			'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
			'order_by' => 'ge.name asc',
                ));
                if (!$content) {
                        $content = elgg_echo('groups:none');
                }
                break;
        case 'my_groups':
        default:
		$page_owner = elgg_get_page_owner_entity();
		$content = elgg_list_entities_from_relationship(array(
			'base_url' => $base_url,
			'type' => 'group',
			'relationship' => 'member',
			'relationship_guid' => elgg_get_logged_in_user_guid(),
			'inverse_relationship' => false,
			'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
			'order_by' => 'ge.name asc',
			'full_view' => 'gc_summary',
		));
		if (!$content) {
			$content = elgg_echo('groups:none');
		}
                break;
        case 'newest':
                $content = elgg_list_entities(array(
			'base_url' => $base_url,
                        'type' => 'group',
			'full_view' => 'gc_summary',
                ));
                if (!$content) {
                        $content = elgg_echo('groups:none');
                }
                break;
}
echo $content;
