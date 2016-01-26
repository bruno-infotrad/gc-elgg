<?php
function gc_search_objects_hook($hook, $type, $value, $params) {

	$db_prefix = elgg_get_config('dbprefix');

	$join = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
	if ($params['gc_group_content_joins']) {
		$join = $params['gc_group_content_joins'].' '.$join;
	}
	if ($params['contributed_by']) {
		$user_guid = get_user_by_username($params['contributed_by'])->guid;
		$where_contributed_by = "e.owner_guid=\"$user_guid\"";
	}
	$GLOBALS['GC_THEME']->debug("APPROXIMATE_DATE=".$params['approximate_date']);
	if ($params['approximate_date']) {
		$approx_after = $params['approximate_date'] + 3*24*3600;
		$approx_before = $params['approximate_date'] - 3*24*3600;
		$GLOBALS['GC_THEME']->debug("APPROXIMATE_=$approx_before $approx_after");
		$where_approximate_date = "e.time_created < $approx_after AND e.time_created > $approx_before";
	}
	$params['joins'] = array($join);
	$fields = array('title', 'description');

	$where = search_get_where_sql('oe', $fields, $params);
	if ($params['contributed_by']) {
		$where = $where ." AND ".$where_contributed_by;
	}
	if ($params['approximate_date']) {
		$where = $where ." AND ".$where_approximate_date;
	}

	$params['wheres'] = array($where);
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'oe', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

function gc_search_groups_hook($hook, $type, $value, $params) {
	//Return if search IN groups or contributed by)
	if ($params['gc_group_content_joins'] || $params['contributed_by']) {
		return;
	}
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	$join = "JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid";
	$params['joins'] = array($join);
	$fields = array('name', 'description');
	$where = search_get_where_sql('ge', $fields, $params);
	if ($params['approximate_date']) {
		$approx_after = $params['approximate_date'] + 3*24*3600;
		$approx_before = $params['approximate_date'] - 3*24*3600;
		$where_approximate_date = "e.time_created < $approx_after AND e.time_created > $approx_before";
		$where = $where ." AND ".$where_approximate_date;
	}

	$params['wheres'] = array($where);

	// override subtype -- All groups should be returned regardless of subtype.
	$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ge', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $description);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
		'full_view' => 'gc_summary',
	);
}
function gc_search_comments_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	$limit = sanitise_int($params['limit']);
	$offset = sanitise_int($params['offset']);
	$params['annotation_names'] = array('generic_comment', 'group_topic_post');

	$params['joins'] = array(
		$params['gc_group_content_joins'],
		"JOIN {$db_prefix}annotations a on e.guid = a.entity_guid",
		"JOIN {$db_prefix}metastrings msn on a.name_id = msn.id",
		"JOIN {$db_prefix}metastrings msv on a.value_id = msv.id"
	);
	$where_contributed_by = '';
	if ($params['contributed_by']) {
		$user_guid = get_user_by_username($params['contributed_by'])->guid;
		$where_contributed_by = " AND a.owner_guid=\"$user_guid\"";
	}
	if ($params['approximate_date']) {
		$approx_after = $params['approximate_date'] + 3*24*3600;
		$approx_before = $params['approximate_date'] - 3*24*3600;
		$where_approximate_date = " AND e.time_created < $approx_after AND e.time_created > $approx_before";
	}
	$fields = array('string');

	// force IN BOOLEAN MODE since fulltext isn't
	// available on metastrings (and boolean mode doesn't need it)
	$search_where = search_get_where_sql('msv', $fields, $params, FALSE);

	$container_and = '';
	if ($params['container_guid'] && $params['container_guid'] !== ELGG_ENTITIES_ANY_VALUE) {
		$container_and = 'AND e.container_guid = ' . sanitise_int($params['container_guid']);
	}


	//$e_access = get_access_sql_suffix('e');
	$e_access = _elgg_get_access_where_sql(array('table_alias' => 'e'));
	//$a_access = get_access_sql_suffix('a');
	$e_access = _elgg_get_access_where_sql(array('table_alias' => 'a'));
	// @todo this can probably be done through the api..
	$q = "SELECT count(DISTINCT a.id) as total FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid ".
		$params['gc_group_content_joins'] 
		." WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
			$where_contributed_by
			$where_approximate_date
		";

	if (!$result = get_data($q)) {
		return FALSE;
	}
	
	$count = $result[0]->total;
	
	// don't continue if nothing there...
	if (!$count) {
		return array ('entities' => array(), 'count' => 0);
	}
	
	$order_by = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	if ($order_by) {
		$order_by = "ORDER BY $order_by";
	}
	
	$q = "SELECT DISTINCT a.*, msv.string as comment FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid ".
		$params['gc_group_content_joins'] 
		." WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
			$where_contributed_by
			$where_approximate_date
		
		$order_by
		LIMIT $offset, $limit
		";

	$comments = get_data($q);

	// @todo if plugins are disabled causing subtypes
	// to be invalid and there are comments on entities of those subtypes,
	// the counts will be wrong here and results might not show up correctly,
	// especially on the search landing page, which only pulls out two results.

	// probably better to check against valid subtypes than to do what I'm doing.

	// need to return actual entities
	// add the volatile data for why these entities have been returned.
	$entities = array();
	foreach ($comments as $comment) {
		$entity = get_entity($comment->entity_guid);

		// hic sunt dracones
		if (!$entity) {
			//continue;
			$entity = new ElggObject();
			$entity->setVolatileData('search_unavailable_entity', TRUE);
		}

		$comment_str = search_get_highlighted_relevant_substrings($comment->comment, $query);
		$entity->setVolatileData('search_match_annotation_id', $comment->id);
		$entity->setVolatileData('search_matched_comment', $comment_str);
		$entity->setVolatileData('search_matched_comment_owner_guid', $comment->owner_guid);
		$entity->setVolatileData('search_matched_comment_time_created', $comment->time_created);
		$entities[] = $entity;
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}
function gc_search_users_hook($hook, $type, $value, $params) {
	//Return if search IN groups or contributed by)
	if ($params['gc_group_content_joins'] || $params['contributed_by'] || $params['approximate_date']) {
		return;
	}
	$db_prefix = elgg_get_config('dbprefix');

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);
//Core user piece
        $join = "JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid";
        $params['joins'] = array($join);
	$where = array("(ue.name LIKE \"%{$query}%\" OR ue.username LIKE \"%{$query}%\")","(ue.banned = 'no')");

        $params['wheres'] = $where;

        // override subtype -- All users should be returned regardless of subtype.
        $params['subtype'] = ELGG_ENTITIES_ANY_VALUE;

        $params['count'] = TRUE;
	if (get_entity($params['container_guid']) instanceof ElggGroup) {
        	$params['relationship'] = 'member';
        	$params['relationship_guid'] = $params['container_guid'];
        	$params['inverse_relationship'] = true;
		//need to keep it for now
		$tmp_container_guid = $params['container_guid'];
		unset($params['container_guid']);
		$count = elgg_get_entities_from_relationship($params);
		$params['container_guid'] = $tmp_container_guid;
		$GLOBALS['GC_THEME']->debug("RIGHT AFTER SEARCH HOOK ".$count);
	} else {
        	$count = elgg_get_entities($params);
	}

        // no need to continue if nothing here.
        if (!$count) {
                return array('entities' => array(), 'count' => $count);
        }
        $params['count'] = FALSE;
	if (get_entity($params['container_guid']) instanceof ElggGroup) {
		$GLOBALS['GC_THEME']->debug("SEARCH HOOK ".$params['container_guid']);
        	$params['relationship'] = 'member';
        	$params['relationship_guid'] = $params['container_guid'];
        	$params['inverse_relationship'] = true;
		unset($params['container_guid']);
		$user_entities = elgg_get_entities_from_relationship($params);
		$GLOBALS['GC_THEME']->debug("RIGHT AFTER SEARCH HOOK ".var_export($user_entities,true));
	} else {
        	$user_entities = elgg_get_entities($params);
	}
        // add the volatile data for why these entities have been returned.
        foreach ($user_entities as $entity) {
                $username = search_get_highlighted_relevant_substrings($entity->username, $query);
                $entity->setVolatileData('search_matched_title', $username);

                $name = search_get_highlighted_relevant_substrings($entity->name, $query);
                $entity->setVolatileData('search_matched_description', $name);
        }

        $params['joins'] = NULL;
        $params['wheres'] = NULL;

//Additional piece for dealing with substring search

	if (!(get_entity($params['container_guid']) instanceof ElggGroup)) {
	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";

	//$access = get_access_sql_suffix('md');
	$access = _elgg_get_access_where_sql(array('table_alias' => 'md'));
	$and_used = preg_split("/ and /i", $query);
	$or_used = preg_split("/ or /i", $query);
	$new_query = '';
	if (sizeof($and_used) > 1) {
		foreach ($and_used as $atom) {
			$new_query = ($new_query)?($new_query . " AND (msv.string like '%$atom%')"):("(msv.string like '%$atom%')");
		}
	}
	if (sizeof($or_used) > 1) {
		foreach ($or_used as $atom) {
			$new_query = ($new_query)?($new_query . " OR (msv.string like '%$atom%')"):("(msv.string like '%$atom%')");
		}
	}
	if ((sizeof($and_used) > 1) || (sizeof($or_used) > 1)) {
		$params['wheres'][] = "(msn.string IN (\"briefdescription\",\"description\") AND $new_query AND $access)";
	} else {
		$params['wheres'][] = "(msn.string IN (\"briefdescription\",\"description\") AND msv.string like '%$query%' AND $access)";
	}
	
	$params['count'] = FALSE;
	$other_entities = elgg_get_entities($params);


	// add the volatile data for why these entities have been returned.
	foreach ($other_entities as $entity) {
	    //if ($entity->type != 'user') {
		$matched_tags_strs = array();

		// get tags for each tag name requested to find which ones matched.
		foreach ($search_tag_names as $tag_name) {
			$tags = $entity->getTags($tag_name);

			// @todo make one long tag string and run this through the highlight
			// function.  This might be confusing as it could chop off
			// the tag labels.
			if (in_array(strtolower($query), array_map('strtolower', $tags))) {
				if (is_array($tags)) {
					$tag_name_str = elgg_echo("tag_names:$tag_name");
					$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
				}
			}
		}

		// different entities have different titles
		switch($entity->type) {
			case 'site':
			case 'user':
			case 'group':
				$title_tmp = $entity->name;
				break;

			case 'object':
				$title_tmp = $entity->title;
				break;
		}

		// Nick told me my idea was dirty, so I'm hard coding the numbers.
		$title_tmp = strip_tags($title_tmp);
		if (! $title_tmp) {
                        $title_tmp = $params['query'];
                }
		$GLOBALS['GC_THEME']->debug("gc_search__users_hook title_tmp=".$title_tmp);

		if (elgg_strlen($title_tmp) > 297) {
			$title_str = elgg_substr($title_tmp, 0, 297) . '...';
		} else {
			$title_str = $title_tmp;
		}

		$desc_tmp = strip_tags($entity->description);
		if (elgg_strlen($desc_tmp) > 297) {
			$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
		} else {
			$desc_str = $desc_tmp;
		}

		$briefdesc_tmp = strip_tags($entity->briefdescription);
		if (elgg_strlen($briefdesc_tmp) > 297) {
			$briefdesc_str = elgg_substr($briefdesc_tmp, 0, 297) . '...';
		} else {
			$briefdesc_str = $briefdesc_tmp;
		}
		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);
		$desc_str = search_get_highlighted_relevant_substrings($desc_str, $params['query']);
		$briefdesc_str = search_get_highlighted_relevant_substrings($briefdesc_str, $params['query']);

		$entity->setVolatileData('search_matched_title', $title_str);
		$entity->setVolatileData('search_matched_description', $desc_str);
		$entity->setVolatileData('search_matched_briefdescription', $briefdesc_str);
	    //}
	}
	}
	$entities = array_merge($user_entities,$other_entities);

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

function gc_search_tags_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$valid_tag_names = elgg_get_registered_tag_metadata_names();

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);

	// if passed a tag metadata name, only search on that tag name.
	// tag_name isn't included in the params because it's specific to
	// tag searches.
	if ($tag_names = get_input('tag_names')) {
		if (is_array($tag_names)) {
			$search_tag_names = $tag_names;
		} else {
			$search_tag_names = array($tag_names);
		}

		// check these are valid to avoid arbitrary metadata searches.
		foreach ($search_tag_names as $i => $tag_name) {
			if (!in_array($tag_name, $valid_tag_names)) {
				unset($search_tag_names[$i]);
			}
		}
	} else {
		$search_tag_names = $valid_tag_names;
	}

	if (!$search_tag_names) {
		return array('entities' => array(), 'count' => $count);
	}

	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";

	//$access = get_access_sql_suffix('md');
	$access = _elgg_get_access_where_sql(array('table_alias' => 'md'));
	$sanitised_tags = array();

	foreach ($search_tag_names as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}

	$tags_in = implode(',', $sanitised_tags);
	// Perform wildcard search instead
	//$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' AND $access)";
	//Note We can't use the same trick as for the user hook because the tag value for one single tag for one user are stored in different metastrings
	$where_contributed_by = '';
	if ($params['contributed_by']) {
		$user_guid = get_user_by_username($params['contributed_by'])->guid;
		$where_contributed_by = " AND md.owner_guid=\"$user_guid\"";
	}
	if ($params['exact'] == 'yes') {
		$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' collate utf8_bin AND $access $where_contributed_by)";
	} else {
		$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string like '%$query%' AND $access $where_contributed_by)";
	}


	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$matched_tags_strs = array();

		// get tags for each tag name requested to find which ones matched.
		foreach ($search_tag_names as $tag_name) {
			$tags = $entity->getTags($tag_name);

			// @todo make one long tag string and run this through the highlight
			// function.  This might be confusing as it could chop off
			// the tag labels.
			if (in_array(strtolower($query), array_map('strtolower', $tags))) {
				if (is_array($tags)) {
					$tag_name_str = elgg_echo("tag_names:$tag_name");
					$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
				}
			}
		}

		// different entities have different titles
		switch($entity->type) {
			case 'site':
			case 'user':
			case 'group':
				$title_tmp = $entity->name;
				break;

			case 'object':
				$title_tmp = $entity->title;
				break;
		}

		// Nick told me my idea was dirty, so I'm hard coding the numbers.
		$title_tmp = strip_tags($title_tmp);
		if (! $title_tmp) {
			$title_tmp = $params['query'];
		}
		$GLOBALS['GC_THEME']->debug("gc_search_tags_hook title_tmp=".$title_tmp);
		if (elgg_strlen($title_tmp) > 297) {
			$title_str = elgg_substr($title_tmp, 0, 297) . '...';
		} else {
			$title_str = $title_tmp;
		}

		$desc_tmp = strip_tags($entity->description);
		if (elgg_strlen($desc_tmp) > 297) {
			$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
		} else {
			$desc_str = $desc_tmp;
		}

		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);

		$entity->setVolatileData('search_matched_title', $title_str);
		$entity->setVolatileData('search_matched_description', $desc_str);
		$entity->setVolatileData('search_matched_extra', $tags_str);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

function gc_register($hook, $type, $value,$params) {
	$db_prefix = elgg_get_config('dbprefix');
	$GLOBALS['GC_THEME']->debug("gc_register:params ".var_export($params,true));
	$GLOBALS['GC_THEME']->debug("gc_register:params guid ".$params['user']->guid);
	// Use raw method to update user to GCuser because user entity not validated so not yet available
	$user=$params['user'];
	$metaname = 'collections_notifications_preferences_email';
	$user->$metaname = '-1';
	return true;
}
/**
 * Return the write access for the current group if the user has write access to it.
 */
function gc_groups_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params) {
        $page_owner = elgg_get_page_owner_entity();
        $user_guid = $params['user_id'];
        $user = get_entity($user_guid);
        if (!$user) {
                return $returnvalue;
        }

        // only insert group access for current group
        if ($page_owner instanceof ElggGroup) {
                if ($page_owner->canWriteToContainer($user_guid)) {
                        $returnvalue[$page_owner->group_acl] = elgg_echo('groups:group');

                        unset($returnvalue[ACCESS_FRIENDS]);
                }
        } else {
                // if the user owns the group, remove all access collections manually
                // this won't be a problem once the group itself owns the acl.
                $groups = elgg_get_entities_from_relationship(array(
                                        'relationship' => 'member',
                                        'relationship_guid' => $user_guid,
                                        'inverse_relationship' => FALSE,
                                        'limit' => 999
                                ));

                if ($groups) {
                        foreach ($groups as $group) {
                                unset($returnvalue[$group->group_acl]);
                        }
                }
        }

        return $returnvalue;
}

function gc_theme_index_handler() {
	if (elgg_is_logged_in()) {
		forward("/dashboard");
	} else {
		$login_box = elgg_view('core/account/login_box');
		elgg_set_page_owner_guid(1);
		$content = elgg_view_layout('two_sidebar_river', array( 'title' => $title, 'sidebar'=> $login_box,'content' => "", 'sidebar_alt' => "",));
		echo elgg_view_page($title, $content);
		return true;
	}
}
function gc_theme_user_settings_save() {
        include(elgg_get_plugins_path() . "gc_theme/actions/gc_theme/settings/usersettings/save.php");
}

function gc_reportedcontent_user_hover_menu($hook, $type, $return, $params) {
        $user = $params['entity'];

        $profile_url = urlencode($user->getURL());
        $name = urlencode($user->name);
        $url = "reportedcontent/add?address=$profile_url&title=$name";

        if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
                $item = new ElggMenuItem(
                                'reportuser',
                                elgg_echo('reportedcontent:user'),
                                $url);
                $item->setSection('report_user');
                $return[] = $item;
        }

        return $return;
}

function gc_user_entities($hook, $type, $return, $params) {
	if (elgg_is_admin_logged_in()){
        	$user = $params['entity'];
        	$number_of_entities_owned_by_user = elgg_get_entities(array(
						'types' => 'object',
						'subtypes' => array('blog','bookmarks','file','folder','groupforumtopic','page','page_top','poll','poll_choice','thewire'),
						'owner_guids' => $user->getGUID(),
						'count' => TRUE,
						));
        	$number_of_annotations_owned_by_user = elgg_get_annotations(array(
						//'annotation_names' => array('generic_comment', 'group_topic_post'),
						'annotation_owner_guid' => $user->getGUID(),
						'count' => TRUE,
						));
		$nomoobu = $number_of_entities_owned_by_user + $number_of_annotations_owned_by_user;
                $item = new ElggMenuItem(
                                'user:number_of_entities',
                                $nomoobu.' objets',false);
                $item->setSection('admin');
                $return[] = $item;
        }

        return $return;
}

function gc_theme_river_menu_handler($hook, $type, $items, $params) {
	$GLOBALS['GC_THEME']->debug("gc_theme_river_menu_handler ".var_export($params,true));
	$item = $params['item'];
	$object = $item->getObjectEntity();

	if (!elgg_in_context('widgets') && !$item->annotation_id && $object instanceof ElggEntity && $item->action_type != 'join') {
		$group = $object->getContainerEntity();
		if ($group instanceof ElggGroup ) {
			if  ($group->canWriteToContainer() || elgg_is_admin_logged_in()) {
				if ($object->canAnnotate(0, 'generic_comment')) {
					//if ($object->getSubtype() == 'thewire') {
						$items[] = ElggMenuItem::factory(array(
							'name' => 'comment',
							//'href' => "#comments-add-$object->guid",
							//'text' => elgg_echo('comment'),
							'text' => elgg_view_icon('speech-bubble'),
							'title' => elgg_echo('comment:this'),
							'rel' => "toggle",
							'priority' => 101,
							'link_class'=>'elgg-comment-add',
							'id'=> $object->getGUID(),
						));
					//}
				}
			}
		} else {
			if ($object->canAnnotate(0, 'generic_comment')) {
				//if ($object->getSubtype() == 'thewire') {
					$items[] = ElggMenuItem::factory(array(
						'name' => 'comment',
						//'href' => "#comments-add-$object->guid",
						//'text' => elgg_echo('comment'),
						'text' => elgg_view_icon('speech-bubble'),
						'title' => elgg_echo('comment:this'),
						'rel' => "toggle",
						'priority' => 101,
						'link_class'=>'elgg-comment-add',
						'id'=> $object->getGUID(),
					));
				//}
			}
		}
		if (elgg_instanceof($object, 'object', 'groupforumtopic')) {
			$group = $object->getContainerEntity();

			if ($group && ($group->canWriteToContainer() || elgg_is_admin_logged_in())) {
				$items[] = ElggMenuItem::factory($options = array(
					'name' => 'reply',
					//'href' => "#discussion-reply-{$object->guid}",
					'text' => elgg_view_icon('speech-bubble'),
					'title' => elgg_echo('reply:this'),
					'rel' => 'toggle',
					'priority' => 50,
					'link_class'=>'elgg-discussion-reply-add',
					'id'=> $object->getGUID(),
				));
			}
		} else {
			if (elgg_instanceof($object, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
				// Group discussion replies cannot be commented
				foreach ($return as $key => $item) {
					if ($item->getName() === 'comment') {
						unset($return[$key]);
					}
				}
			}
		}
		
		if (elgg_is_logged_in()) {
			if ($object instanceof ElggUser && !$object->isFriend()) {
				$items[] = ElggMenuItem::factory(array(
					'name' => 'addfriend',
					'href' => "/action/friends/add?friend=$object->guid",
					'text' => elgg_echo('friend:user:add', array($object->name)),
					'is_action' => TRUE,
				));
			}
		}
	}

	return $items;
}

function gc_thewire_discussion_reply_setup_entity_menu_items($hook, $type, $value, $params) {
        $handler = elgg_extract('handler', $params, false);
        if ($handler != 'thewire' && $handler != 'blog' && $handler != 'comment' && $handler != 'discussion_reply') {
                return $value;
        }
        $entity = $params['entity'];
	if ($handler == 'thewire') {
        	foreach ($value as $index => $item) {
        	        $name = $item->getName();
        	        if ($name == 'access') {
        	        //if ($name == 'access' || $name == 'edit') {
			//Not ready yet
        	        //if ($name == 'access' ) {
        	                unset($value[$index]);
        	        }
        	}
        	$options = array(
        	        'name' => 'thread',
        	        'text' => elgg_echo('thewire:thread'),
        	        'href' => "thewire/thread/$entity->wire_thread",
        	        'priority' => 170,
        	);
        	$value[] = ElggMenuItem::factory($options);
        }
	if ($entity->canEdit()) {
		if ($handler == 'comment') {
        		foreach ($value as $index => $item) {
        		        $name = $item->getName();
        		        if ($name == 'edit') {
					$item->setText('');
					$item->setLinkClass('comment-edit');
        		        }
        		}
        	} elseif ($handler == 'discussion_reply') {
        		foreach ($value as $index => $item) {
        		        $name = $item->getName();
        		        if ($name == 'edit') {
					$item->setText('');
					$item->setLinkClass('discussion-reply-edit');
        		        }
        		}
        	}
        }
        if (elgg_is_logged_in()) {
		if ($handler == 'thewire') {
		$group = $entity->getContainerEntity();
			if ($group instanceof ElggGroup ) {
				if  ($group->canWriteToContainer() || elgg_is_admin_logged_in()) {
                			$options = array(
                			        'name' => 'comment',
						'text' => elgg_view_icon('speech-bubble'),
                			        'href' => "thewire/thread/$entity->guid",
						'link_class'=>'elgg-comment-add',
                			        'priority' => 150,
                			);
                			$value[] = ElggMenuItem::factory($options);
				}
			} else {
                		$options = array(
                		        'name' => 'comment',
					'text' => elgg_view_icon('speech-bubble'),
                		        'href' => "thewire/thread/$entity->guid",
					'link_class'=>'elgg-comment-add',
                		        'priority' => 150,
                		);
                		$value[] = ElggMenuItem::factory($options);
			}
		}
		if (elgg_is_admin_logged_in() || roles_has_role(elgg_get_logged_in_user_entity(),'im_admin')) {
			$post = get_entity($entity->getGuid());
        		if ($post->exec_content == 'true') {
				$options = array(
					'name' => 'remove_exec_content',
					'text' => elgg_echo('gc_theme:remove_exec_content'),
					'title' => elgg_echo('gc_theme:remove_exec_content'),
					'href' => elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/compound/remove_exec_content?guid={$entity->getGUID()}"),
					'priority' => 180,
					);
                		$value[] = ElggMenuItem::factory($options);
        		}
        	}
        }

        if ($entity->reply) {
                $options = array(
                        'name' => 'previous',
                        'text' => elgg_echo('thewire:previous'),
                        'href' => "thewire/previous/$entity->guid",
                        'priority' => 160,
                        'link_class' => 'thewire-previous',
                        'title' => elgg_echo('thewire:previous:help'),
                );
                $value[] = ElggMenuItem::factory($options);
        }

        return $value;
}

function gc_dashboard_delete_item($hook, $type, $fields, $params) {
	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	$GLOBALS['GC_THEME']->debug("HANDLER $handler");

	$options = array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete:this'),
		'href' => "action/$handler/delete?guid={$entity->getGUID()}",
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 300,
		);
	$return[] = ElggMenuItem::factory($options);
	return $return;
}

function gc_theme_owner_block_menu_handler($hook, $type, $items, $params) {
	$owner = elgg_get_page_owner_entity();
	if ($owner instanceof ElggGroup) {
		if ($owner->isMember($user)) {
/*	
			$items['info'] = ElggMenuItem::factory(array(
				'name' => 'info', 
				'text' => elgg_view_icon('info') . elgg_echo('profile:groupinfo'), 
				'href' => "/groups/info/$owner->guid/" . elgg_get_friendly_title($owner->name),
				'priority' => 2,
			));
*/			
			$items['profile'] = ElggMenuItem::factory(array(
				'name' => 'wall',
				//'text' => elgg_view_icon('speech-bubble') . elgg_echo('profile:wall'),
				'text' => elgg_echo('profile:wall'),
				'href' => "/groups/profile/$owner->guid/" . elgg_get_friendly_title($owner->name),
				'priority' => 1,
			));

			if ($params['entity']->forum_enable != "no") {
				$items['discussion'] = ElggMenuItem::factory(array(
					'name' => 'discussion',
					//'text' => elgg_view_icon('users') . elgg_echo('profile:discussion'),
					'text' => elgg_echo('profile:discussion'),
					'href' => "/discussion/owner/$owner->guid/",
					'priority' => 4,
				));
			}


			if ($params['entity']->pages_enable != "no") {
				$items['pages'] = ElggMenuItem::factory(array(
					'name' => 'pages',
					//'text' => elgg_view_icon('list') . elgg_echo('profile:pages'),
					'text' => elgg_echo('profile:pages'),
					'href' => "/pages/group/$owner->guid/all",
					'priority' => 5,
				));
			}

			$items['thewire'] = ElggMenuItem::factory(array(
				'name' => 'thewire',
				'text' => elgg_echo('profile:thewire'),
				'href' => "/thewire_group/group/$owner->guid/all",
				'priority' => 8,
			));

			if ($params['entity']->blog_enable != "no") {
				$items['blog'] = ElggMenuItem::factory(array(
					'name' => 'blog',
					'text' => elgg_echo('profile:blog'),
					'href' => "/blog/group/$owner->guid/all",
					'priority' => 8,
				));
			}

			if ($params['entity']->file_enable != "no") {
				$items['files'] = ElggMenuItem::factory(array(
					'name' => 'files',
					//'text' => elgg_view_icon('clip') . elgg_echo('profile:files'),
					'text' => elgg_echo('profile:files'),
					'href' => "/file/group/$owner->guid/all",
					'priority' => 8,
				));
			}

			if ($params['entity']->bookmarks_enable != "no") {
				$items['bookmarks'] = ElggMenuItem::factory(array(
					'name' => 'bookmarks',
					//'text' => elgg_view_icon('clip') . elgg_echo('profile:files'),
					'text' => elgg_echo('profile:bookmarks'),
					'href' => "/bookmarks/group/$owner->guid/all",
					'priority' => 9,
				));
			}

			if ($params['entity']->polls_enable != "no") {
				$items['polls'] = ElggMenuItem::factory(array(
					'name' => 'polls',
					//'text' => elgg_view_icon('clip') . elgg_echo('profile:files'),
					'text' => elgg_echo('polls:group_polls'),
					'href' => "/polls/group/$owner->guid/all",
					'priority' => 10,
				));
			}

			if ($owner->canEdit()) {
				$items['membershiprequests'] = ElggMenuItem::factory(array(
					'name' => 'membership_requests',
					'text' => elgg_echo('groups:membershiprequests'),
					'href' => "groups/requests/$owner->guid",
					'priority' => 11,
				));
			}

			if ($owner->canEdit() && (elgg_get_plugin_setting("mail", "group_tools") == "yes")) {
				$items['mail'] = ElggMenuItem::factory(array(
					'name' => 'mail',
					'text' => elgg_echo('group_tools:menu:mail'),
					'href' => "groups/mail/$owner->guid",
					'priority' => 12,
				));
			}

		} else {
			register_error(elgg_echo('membershiprequired'));
		}
	}
	
	if ($owner instanceof ElggUser) {
		$items['info'] = ElggMenuItem::factory(array(
			'name' => 'info', 
			'text' => elgg_echo('profile:info'), 
			'href' => "/profile/$owner->username/info",
			'priority' => 2,
		));
/*		
		$items['profile'] = ElggMenuItem::factory(array(
			'name' => 'profile',
			'text' => elgg_echo('profile:wall'),
			'href' => "/profile/$owner->username",
			'priority' => 1,
		));
*/		
		$items['friends'] = ElggMenuItem::factory(array(
			'name' => 'friends',	
			'text' => elgg_echo('friends'),
			'href' => "/friends/$owner->username"
		));
	}
/*	
	$top_level_pages = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'page_top',
		'container_guid' => $owner->guid,
		'limit' => 0,
	));
	
	foreach ($top_level_pages as $page) {
		$items["pages-$page->guid"] = ElggMenuItem::factory(array(
			'name' => "pages-$page->guid",
			'href' => $page->getURL(),
			'text' => elgg_view_icon('page') . elgg_view('output/text', array('value' => $page->title)),
		));
	}
*/
	return $items;
}

function gc_theme_profile_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];
	$user = elgg_get_logged_in_user_entity();
		
	//trigger any javascript loads that we might need
	//elgg_view('compound/composer');
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_about',
		'href' => "/ajax/view/profile/user_about?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_about"),
		'priority' => 100,
		'selected' => true,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_workinfo',
		'href' => "/ajax/view/profile/user_workinfo?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_workinfo"),
		'priority' => 200,
		'selected' => true,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_orgchart',
		'href' => "/ajax/view/profile/user_orgchart?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_orgchart"),
		'priority' => 300,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_colleagues',
		'href' => "/ajax/view/profile/user_colleagues?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_colleagues"),
		'priority' => 400,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_activity',
		'href' => "/ajax/view/profile/user_activity?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_activity"),
		'priority' => 500,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));

	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_blog',
		'href' => "/ajax/view/profile/user_blog?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_blog"),
		'priority' => 550,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	$items[] = ElggMenuItem::factory(array(
		'name' => 'user_groups',
		'href' => "/ajax/view/profile/user_groups?container_guid=$entity->guid",
		'text' => elgg_echo("profile:user_groups"),
		'priority' => 600,
		'item_class' => 'gc-profile-tab-item',
		'link_class' => 'gc-profile-tab-link',
	));
	return $items;
}

function gc_theme_annotation_permissions_handler($hook, $type, $result, $params) {
	$entity = $params['entity'];
	$user = $params['user'];
	$annotation_name = $params['annotation_name'];
	$GLOBALS['GC_THEME']->debug("gc_theme_annotation_permissions_handler ".var_export($params,true));
	
	//Users should not be able to post on their own message board
	if ($annotation_name == 'messageboard' && $user->guid == $entity->guid) {
		return false;
	}
	
	//No "commenting" on users, must use messageboard
	if ($annotation_name == 'generic_comment' && $entity instanceof ElggUser) {
		return false;
	}
	
	//No "commenting" on forum topics, must use special "reply" annotation
	if ($annotation_name == 'generic_comment' && elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return false;
	}
	
	//Definitely should be able to "like" a forum topic!
	if ($annotation_name == 'likes' && elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return true;
	}
	
	if ($annotation_name == 'group_topic_post' && !elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return false;
	}
/*
	if ($annotation_name == 'generic_comment') {
		if (get_entity($entity->container_guid) instanceof ElggGroup) {
			$group=get_entity($entity->container_guid);
			if (! $group->isMember($user)) {
				return false;
			}
		}
	}
*/
	
	if ($annotation_name == 'generic_comment' && elgg_instanceof($entity, 'group')) {
		return false;
	}
}

function gc_theme_container_permissions_handler($hook, $type, $result, $params) {
	$container = $params['container'];
	$subtype = $params['subtype'];
	
	if ($container instanceof ElggGroup) {
		if ($subtype == 'thewire') {
			return false;
		}
	}
}

function gc_theme_group_profile_fields($hook, $type, $fields, $params) {
	return array(
		'briefdescription' => 'text',
		'description' => 'longtext',
		'interests' => 'tags',
	);
}

function gc_pages_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$container = get_entity($entity->container_guid);
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'pages') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
		//deal with group tools
		if (elgg_instanceof($container, 'group') && elgg_is_active_plugin('group_tools')) {
			if ($container->owner_guid != elgg_get_logged_in_user_guid() && ! check_entity_relationship(elgg_get_logged_in_user_guid(), "group_admin", $container->guid)){
				foreach ($return as $index => $item) {
					if ($item->getName() == 'delete') {
						unset($return[$index]);
					}
				}
			}
		} else {
			foreach ($return as $index => $item) {
				if ($item->getName() == 'delete') {
					unset($return[$index]);
				}
			}
		}
	}


	$options = array(
		'name' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => "pages/history/$entity->guid",
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

function gc_file_tools_file_route_hook($hook, $type, $returnvalue, $params){
	$result = $returnvalue;
	
	if(!empty($returnvalue) && is_array($returnvalue)){
		$page = elgg_extract("segments", $returnvalue);
		
		switch($page[0]){
			case "view":
				if(!elgg_is_logged_in() && isset($page[1])){
					if(!get_entity($page[1])){
						gatekeeper();
					}
				}
				break;
			case "owner":
				if(file_tools_use_folder_structure()){
					$result = false;
						
					include(dirname(dirname(__FILE__)) . "/pages/list.php");
				}
				break;
			case "group":
				if(file_tools_use_folder_structure()){
					$result = false;
					
					include(dirname(dirname(__FILE__)) . "/pages/list.php");
				}
				break;
			case "add":
				$result = false;
				
				include(dirname(dirname(__FILE__)) . "/pages/file/new.php");
				break;
			case "zip":
				if(isset($page[1])){
					$result = false;
					
					elgg_set_page_owner_guid($page[1]);
					
					register_error(elgg_echo("changebookmark"));
					forward("file/add/" . $page[1] . "?upload_type=zip");
				}
				break;
			case "bulk_download":
				$result = false;
				
				include(dirname(dirname(__FILE__)) . "/pages/file/download.php");
				break;
		}
	}
	
	return $result;
}

function gc_group_tools_route_groups_handler($hook, $type, $return_value, $params){
	$result = $return_value;
	if(!empty($return_value) && is_array($return_value)){
		$page = $return_value['segments'];
		switch($page[0]){
			case "invitations":
				$result = false;
				if(isset($page[1])){
					set_input("username", $page[1]);
				}
				
				include(dirname(dirname(__FILE__)) . "/pages/groups/invitations.php");
				break;
		}
	}
	return $result;
}
function gc_file_icon_url_override($hook, $type, $returnvalue, $params) {
	$file = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'file')) {

		// thumbnails get first priority
		if ($file->thumbnail) {
			$ts = (int)$file->icontime;
			return "mod/file/thumbnail.php?file_guid=$file->guid&size=$size&icontime=$ts";
		}

		$mapping = array(
			'application/excel' => 'excel',
			'application/msword' => 'word',
			'application/ogg' => 'music',
			'application/pdf' => 'pdf',
			'application/powerpoint' => 'ppt',
			'application/vnd.ms-excel' => 'excel',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'openoffice',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
			'application/x-gzip' => 'archive',
			'application/x-rar-compressed' => 'archive',
			'application/x-stuffit' => 'archive',
			'application/zip' => 'archive',

			'text/directory' => 'vcard',
			'text/v-card' => 'vcard',

			'application' => 'application',
			'audio' => 'music',
			'text' => 'text',
			'video' => 'video',
		);

		$mime = $file->mimetype;
		if ($mime) {
			$base_type = substr($mime, 0, strpos($mime, '/'));
		} else {
			$mime = 'none';
			$base_type = 'none';
		}

		if (isset($mapping[$mime])) {
			$type = $mapping[$mime];
		} elseif (isset($mapping[$base_type])) {
			$type = $mapping[$base_type];
		} else {
			$type = 'general';
		}

		if ($size == 'large') {
			$ext = '_lrg';
		} else {
			$ext = '';
		}
		if (preg_match('/\.docx/i',$file->originalfilename)) {
			$type='word';
			$url = "mod/gc_theme/graphics/icons/{$type}{$ext}.gif";
		} elseif (preg_match('/\.xlsx/i',$file->originalfilename)) {
			$type='excel';
			$url = "mod/gc_theme/graphics/icons/{$type}{$ext}.gif";
		} elseif (preg_match('/\.pptx/i',$file->originalfilename)) {
			$type='ppt';
			$url = "mod/gc_theme/graphics/icons/{$type}{$ext}.gif";
		} elseif ($type == 'pdf') {
			$url = "mod/gc_theme/graphics/icons/{$type}{$ext}.gif";
		} else {
			$url = "mod/gc_theme/graphics/icons/{$type}{$ext}.gif";
		}
		return $url;
	}
}
function gc_theme_views_add_rss_link() {
        global $autofeed;
        if (isset($autofeed) && $autofeed == true) {
                $url = current_page_url();
                if (substr_count($url, '?')) {
                        $url .= "&view=rss";
                } else {
                        $url .= "?view=rss";
                }

                $url = elgg_format_url($url);
                elgg_register_menu_item('extras', array(
                        'name' => 'rss',
                        'text' => elgg_view_icon('rss').elgg_echo('rss:subscribe'),
                        'href' => $url,
                        'title' => elgg_echo('rss:subscribe'),
                ));
        }
}
function gc_object_notifications_intercept($hook, $entity_type, $returnvalue, $params) {
	if (isset($params)) {
		$GLOBALS['GC_THEME']->debug("GC INTERCEPT params ".var_export($params,true));
		if ($params['event'] == 'create' && $params['object'] instanceof ElggObject) {
			$GLOBALS['GC_THEME']->debug("GC INTERCEPT params ".var_export($params,true));
			$object = $params['object'];
			$GLOBALS['GC_THEME']->debug('BLOG/EVENT INTERCEPT object->subtype '.$object->getSubtype());
			if ($object->getSubtype() == 'blog' || $object->getSubtype() == 'event') {
				$GLOBALS['GC_THEME']->debug('GC INTERCEPT IT IS A BLOG OR AN EVENT');
				$GLOBALS['GC_THEME']->debug('GC INTERCEPT object->status '.$object->status);
				if ($object->status != 'published') {
					$GLOBALS['GC_THEME']->debug('BRUNO GC INTERCEPT AND IT IS NOT PUBLISHED');
					return true;
				}
			}
		}
	}
	return null;
}

function roles_im_admins_config($hook_name, $entity_type, $return_value, $params) {

	$roles = array(

		'im_admin' => array(
			'title' => 'roles_im_admin:role:title',
			'permissions' => array(
				'actions' => array(
				),
				'menus' => array(
				),
				'views' => array(
				),
				'hooks' => array(
				),

			),
		),
		ADMIN_ROLE => array(
			'title' => 'roles:role:ADMIN_ROLE',
			'extends' => array(),
			'permissions' => array(
				'actions' => array(
				),
				'menus' => array(
				),
				'views' => array(
					'forms/account/settings' => array(
						'rule' => 'extend',
						'view_extension' => array(
							'view' => 'roles/settings/account/role',
							'priority' => 150
						)
					),
				),
				'hooks' => array(
					'usersettings:save::user' => array(
						'rule' => 'extend',
						'hook' => array(
							'handler' => 'roles_user_settings_save',
							'priority' => 500,
						)
					),
					'register::menu:user_hover' => array(
						'rule' => 'extend',
						'hook' => array(
							'handler' => 'im_admin_user_menu_setup',
							'priority' => 500,
						)
					,)
				),
			),
		),		
	);

	if (!is_array($return_value)) {
		return $roles;
	} else {
		return array_merge($return_value, $roles);
	}
}

function im_admin_user_menu_setup($hook, $type, $return, $params) {

        // Make sure we have a logged-in user, who is not an admin
        $user = $params['entity'];
        if (!elgg_instanceof($user, 'user') || $user->isAdmin()) {
                return $return;
        }

        $role = roles_get_role($user);
        if ($role->name == 'im_admin') {
                $action = 'revoke_im_admin';
        } else {
                $action = 'make_im_admin';
        }

        $url = "action/roles_im_admin/$action?guid={$user->guid}";
        $url = elgg_add_action_tokens_to_url($url);
        $item = new ElggMenuItem($action, elgg_echo("roles_im_admin:action:$action"), $url);
        $item->setSection('admin');
        $item->setLinkClass('data-confirm');
        $return[] = $item;

        return $return;
}

function im_admin_can_edit_hook($hook, $type, $return_value, $params){
	$result = $return_value;
	if(!empty($params) && is_array($params) && !$result){
		if(array_key_exists("entity", $params) && array_key_exists("user", $params)){
			$entity = $params["entity"];
			$user = $params["user"];
			if(($entity instanceof ElggGroup) && ($user instanceof ElggUser)){
				if(roles_has_role($user, "im_admin")){
					$result = true;
				}
			}
		}
	}
	return $result;
}

function ad2elgg_user_update_forward_hook($hook_name, $entity_type, $return_value, $parameters){
	$GLOBALS['GC_THEME']->debug("FORWARD HOOK: ".var_export($parameters,true));
	$username = get_input("username");
	$GLOBALS['GC_THEME']->debug("Username: $username");
	if(!empty($username)){
		return elgg_get_site_url() . "/dfait_adsync/sync/" . $username;
	}
}

function gc_get_friendly_time($hook, $type, $value, $params) {
	$time = $params['time'];
        $diff = time() - (int)$time;

        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;

        if ($diff < $minute) {
                        return elgg_echo("friendlytime:justnow");
        } else if ($diff < $hour) {
                $diff = round($diff / $minute);
                if ($diff == 0) {
                        $diff = 1;
                }

                if ($diff > 1) {
                        return elgg_echo("friendlytime:minutes", array($diff));
                } else {
                        return elgg_echo("friendlytime:minutes:singular", array($diff));
                }
        } else if ($diff < $day) {
                $diff = round($diff / $hour);
                if ($diff == 0) {
                        $diff = 1;
                }

                if ($diff > 1) {
                        return elgg_echo("friendlytime:hours", array($diff));
                } else {
                        return elgg_echo("friendlytime:hours:singular", array($diff));
                }
        } else {
                return date('Y-m-d',$time);
	}
}

function event_entity_menu_setup($hook, $type, $return, $params) {
        if (elgg_in_context('widgets')) {
                return $return;
        }

        $entity = $params['entity'];
        $handler = elgg_extract('handler', $params, false);
        if ($handler != 'event') {
                return $return;
        }
	//Older events don't have a status
	if (! $entity->status) {
		$status = 'published';
	} else {
		$status = $entity->status;
	}
        if ($status != 'published') {
                // draft status replaces access
                foreach ($return as $index => $item) {
                        if ($item->getName() == 'access') {
                                unset($return[$index]);
                        }
                }

                $status_text = elgg_echo("blog:status:{$status}");
                $options = array(
                        'name' => 'published_status',
                        'text' => "<span>$status_text</span>",
                        'href' => false,
                        'priority' => 150,
                );
                $return[] = ElggMenuItem::factory($options);
        }

        return $return;
}

function gc_wire_prepare_notification($hook, $type, $notification, $params) {

        $entity = $params['event']->getObject();
        $owner = $params['event']->getActor();
        $recipient = $params['recipient'];
        $language = $params['language'];
        $method = $params['method'];
        $descr = elgg_get_excerpt($entity->description);

        $subject = elgg_echo('thewire:notify:subject', array($owner->name), $language);
        if ($entity->reply) {
                $parent = thewire_get_parent($entity->guid);
                if ($parent) {
                        $parent_owner = $parent->getOwnerEntity();
                        $body = elgg_echo('thewire:notify:reply', array($owner->name, $parent_owner->name), $language);
                }
        } else {
                $body = elgg_echo('thewire:notify:post', array($owner->name), $language);
        }
        $body .= "\n\n" . $descr . "\n\n";
        $body .= elgg_echo('thewire:notify:footer', array($entity->getURL()), $language);

        $notification->subject = $subject;
        $notification->body = $body;
        $notification->summary = elgg_echo('thewire:notify:summary', array($descr), $language);

        return $notification;
}

function gc_file_tools_folder_sidebar_tree_hook($hook, $type, $returnvalue, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $returnvalue;
	}
	
	$container = elgg_extract("container", $params);
	if (empty($container) || (!elgg_instanceof($container, "user") && !elgg_instanceof($container, "group"))) {
		return $returnvalue;
	}
	
	$main_menu_item = ElggMenuItem::factory(array(
		"name" => "root",
		"text" => elgg_echo("file_tools:list:folder:main"),
		"href" => "#",
		"id" => "0",
		"rel" => "root",
		"priority" => 0
	));
	
	if ($folders = file_tools_get_folders($container->getGUID())) {
		$main_menu_item->setChildren(gc_file_tools_make_menu_items($folders));
	}
	
	$returnvalue[] = $main_menu_item;
	
	return $returnvalue;
}
