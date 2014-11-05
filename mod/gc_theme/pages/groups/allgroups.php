<?php
        // all groups doesn't get link to self
	$db_prefix = elgg_get_config("dbprefix");
        elgg_pop_breadcrumb();
        elgg_push_breadcrumb(elgg_echo('groups'));

        elgg_register_title_button();

        $selected_tab = get_input('filter', 'my_groups');

        switch ($selected_tab) {
                case 'groups_i_own':
			$page_owner = elgg_get_page_owner_entity();
                        $content = elgg_list_entities(array(
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
				'type' => 'group',
				'relationship' => 'member',
				'relationship_guid' => elgg_get_logged_in_user_guid(),
				'inverse_relationship' => false,
				'full_view' => 'gc_summary',
				'joins' => array("JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid"),
				'order_by' => 'ge.name asc',
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
                        break;
                case 'newest':
                        $content = elgg_list_entities(array(
                                'type' => 'group',
				'full_view' => 'gc_summary',
                        ));
                        if (!$content) {
                                $content = elgg_echo('groups:none');
                        }
                        break;
        }

        $filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));

        $sidebar = elgg_view('groups/sidebar/find');
        //$sidebar .= elgg_view('groups/sidebar/featured');
        $sidebar .= elgg_view('page/elements/popular_groups', $vars);

        $params = array(
                'content' => $content,
                'sidebar' => $sidebar,
                'filter' => $filter,
        );
        $body = elgg_view_layout('content', $params);

        echo elgg_view_page(elgg_echo('groups:all'), $body);
