<?php
function manage_tags_page_handler() {
        if (elgg_is_admin_logged_in()) {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/views/default/admin/gc_theme';
		require_once "$base_dir/manage_tags.php";
		return true;
        } else {
                forward('/dashboard');
        }
}
function fxuwpi_page_handler() {
        if (elgg_is_admin_logged_in()) {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/lib';
		require_once "$base_dir/find_xCIDA_users_with_profile_info.php";
        } else {
                forward('/dashboard');
        }
}
function reassign_objects_page_handler() {
        if (elgg_is_admin_logged_in()) {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/lib';
		require_once "$base_dir/reassign_objects.php";
        } else {
                forward('/dashboard');
        }
}
function delete_unused_accounts_page_handler() {
        if (elgg_is_admin_logged_in()) {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/lib';
		require_once "$base_dir/delete_unused_accounts.php";
                forward('/dashboard');
        } else {
                forward('/dashboard');
        }
}
function pns_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/pns");
        	// exit because this is in a modal display.
		exit;
        }
}
function intro_upload_avatar_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/intro_upload_avatar");
		exit;
        }
}
function intro_join_groups_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/intro_join_groups");
		exit;
        }
}
function intro_add_colleagues_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/intro_add_colleagues");
		exit;
        }
}
function contribute_to_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/contribute_to");
        	// exit because this is in a modal display.
		exit;
        }
}
function notify_groups_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		echo elgg_view("gc_theme/notify_groups",array('event_guid' => $page[0]));
        	// exit because this is in a modal display.
		exit;
        }
}
function gc_event_manager_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'event' && isset($page[1])&& ($page[1] == 'list' || $page[1] == 'view')) {
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/event';
			if (isset($page[2])) {
				if ($page[1] == 'list'){
                			set_input('owner_guid', $page[2]);
				} elseif ($page[1] == 'view'){
                			set_input('guid', $page[2]);
				}
			}
			include "$base_dir/$page[1].php";
        	} elseif (isset($page[0]) && $page[0] == 'registrationform' && isset($page[1])&& $page[1] == 'edit' && isset($page[2])) {
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/registrationform';
                	set_input('guid', $page[2]);
			include "$base_dir/edit.php";
        	} else {
                	return event_manager_page_handler($page);
        	}
       		return true;
        }
}
function gc_messages_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'inbox' && isset($page[1])) {
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/messages';
                	set_input('username', $page[1]);
			include "$base_dir/inbox.php";
        	} elseif (isset($page[0]) && $page[0] == 'read' && isset($page[1])) {
			//need to load js and css for right navigation to work
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/messages';
                	set_input('guid', $page[1]);
			include "$base_dir/read.php";
        	} else {
                	return messages_page_handler($page);
        	}
       		return true;
        }
}

function gc_site_notifications_page_handler($segments) {
        $base = elgg_get_plugins_path() . 'gc_theme/pages/site_notifications';

        elgg_gatekeeper();

        if (!isset($segments[1])) {
                $segments[1] = elgg_get_logged_in_user_entity()->username;
        }

        $user = get_user_by_username($segments[1]);
        if (!$user) {
                return false;
        }

        elgg_set_page_owner_guid($user->guid);

        require "$base/view.php";

        return true;
}

function gc_pages_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'view' && isset($page[1])) {
			//need to load js and css for right navigation to work
			elgg_load_js('jquery-treeview');
        		elgg_load_css('jquery-treeview');
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/pages';
                	set_input('guid', $page[1]);
			include "$base_dir/view.php";
        	} else {
                	return pages_page_handler($page);
        	}
       		return true;
        }
}

function gc_bookmarks_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'view' && isset($page[1])) {
			//need to load js and css for right navigation to work
			elgg_load_js('jquery-treeview');
        		elgg_load_css('jquery-treeview');
        		$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/bookmarks';
                	set_input('guid', $page[1]);
			include "$base_dir/view.php";
        	} else {
                	return bookmarks_page_handler($page);
        	}
       		return true;
        }
}

function gc_blogs_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'view' && isset($page[1])) {
			//need to load js and css for right navigation to work
			$params = gc_blog_get_page_content_read($page[1]);
			if (isset($params['sidebar'])) {
				$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
			} else {
				$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
			}

			$body = elgg_view_layout('content', $params);

			echo elgg_view_page($params['title'], $body);
			return true;
        	} else {
                	return blog_page_handler($page);
        	}
       		return true;
        }
}

function gc_blog_get_page_content_read($guid = NULL) {

	$return = array();

	elgg_entity_gatekeeper($guid, 'object', 'blog');

	$blog = get_entity($guid);

	// no header or tabs for viewing an individual blog
	$return['filter'] = '';

	elgg_set_page_owner_guid($blog->container_guid);

	elgg_group_gatekeeper();

	$return['title'] = $blog->title;

	$container = $blog->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "blog/group/$container->guid/all");
		if ($container->readonly == 'yes') {
			$show_add_form = FALSE;
		} else {
			$show_add_form = TRUE;
		}
	} else {
		elgg_push_breadcrumb($crumbs_title, "blog/owner/$container->username");
	}

	elgg_push_breadcrumb($blog->title);
	$return['content'] = elgg_view_entity($blog, array('full_view' => true));
	// check to see if we should allow comments
	if ($blog->comments_on != 'Off' && $blog->status == 'published') {
		$return['content'] .= elgg_view_comments($blog,$show_add_form);
	}

	return $return;
}

function gc_polls_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
        	if (isset($page[0]) && $page[0] == 'view' && isset($page[1])) {
			//need to load js and css for right navigation to work
			echo gc_polls_get_page_view($page[1]);
        	} else {
                	return polls_page_handler($page);
        	}
       		return true;
        }
}

function gc_polls_get_page_view($guid) {
	elgg_load_js('elgg.polls');
	$poll = get_entity($guid);
	if (elgg_instanceof($poll,'object','poll')) {		
		// Set the page owner
		$page_owner = $poll->getContainerEntity();
		if ($page_owner->readonly == 'yes') {
			$show_add_form = FALSE;
		} else {
			$show_add_form = TRUE;
		}

		elgg_set_page_owner_guid($page_owner->guid);
		$title =  $poll->title;
		$content = elgg_view_entity($poll, array('full_view' => TRUE));
		//check to see if comments are on
		if ($poll->comments_on != 'Off') {
			$content .= elgg_view_comments($poll,$show_add_form);
		}
		
		elgg_push_breadcrumb(elgg_echo('item:object:poll'), "polls/all");
		if (elgg_instanceof($page_owner,'user')) {
			elgg_push_breadcrumb($page_owner->name, "polls/owner/{$page_owner->username}");
		} else {
			elgg_push_breadcrumb($page_owner->name, "polls/group/{$page_owner->guid}");
		}
		elgg_push_breadcrumb($poll->title);
	} else {			
		// Display the 'post not found' page instead
		$title = elgg_echo("polls:notfound");	
		$content = elgg_view("polls/notfound");	
		elgg_push_breadcrumb(elgg_echo('item:object:poll'), "polls/all");
		elgg_push_breadcrumb($title);
	}
	
	$params = array('title' =>$title,'content' => $content,'filter'=>'');
	$body = elgg_view_layout('content', $params);
		
	// Display page
	return elgg_view_page($title,$body);
}
function gc_notifications_page_handler($segments, $handle) {
	elgg_gatekeeper();
	$current_user = elgg_get_logged_in_user_entity();
        // default to personal notifications
        if (!isset($segments[0])) {
                $segments[0] = 'personal';
        }
        if (!isset($segments[1])) {
                forward("notifications/{$segments[0]}/{$current_user->username}");
        }
        $user = get_user_by_username($segments[1]);
        if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
                forward();
        }

	elgg_register_menu_item('notifications_nav', array(
		'name' => 'user_settings',
		'text' => elgg_echo('usersettings:user:opt:linktext'),
		'href' => "/settings/user/$user->username",
	));
		elgg_register_menu_item('notifications_nav', array(
		'name' => 'user_stats',
		'text' => elgg_echo('usersettings:statistics:opt:linktext'),
		'href' => "/settings/statistics/$user->username",
	));
		elgg_register_menu_item('notifications_nav', array(
		'name' => 'notification',
		'text' => elgg_echo('notifications:personal'),
		'href' => "/notifications/personal",
	));
		elgg_register_menu_item('notifications_nav', array(
		'name' => 'notification_groups',
		'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
		'href' => "/notifications/group",
	));

	$pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';

        switch ($segments[0]) {
                case 'group':
                        require "$pages_dir/notifications/groups.php";
                        break;
                case 'personal':
                        require "$pages_dir/notifications/index.php";
                        break;
                default:
                        return false;
        }


        return true;
}
function gc_usersettings_page_handler($segments, $handle) {
        if (!isset($segments[0])) {
                $segments[0] = 'user';
        }

        if (isset($segments[1])) {
                $user = get_user_by_username($segments[1]);
                elgg_set_page_owner_guid($user->guid);
        } else {
                $user = elgg_get_logged_in_user_guid();
                elgg_set_page_owner_guid($user->guid);
        }

        elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");

	$pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';
        switch ($segments[0]) {
                case 'statistics':
                        elgg_push_breadcrumb(elgg_echo('usersettings:statistics:opt:linktext'));
                        $path = "$pages_dir/settings/statistics.php";
                        break;
                case 'user':
                        $path = "$pages_dir/settings/account.php";
                        break;
        }

        if (isset($path)) {

		elgg_register_menu_item('settings_nav', array(
			'name' => 'user_settings',
			'text' => elgg_echo('usersettings:user:opt:linktext'),
			'href' => "/settings/user/$user->username",
		));
		elgg_register_menu_item('settings_nav', array(
			'name' => 'user_stats',
			'text' => elgg_echo('usersettings:statistics:opt:linktext'),
			'href' => "/settings/statistics/$user->username",
		));
		elgg_register_menu_item('settings_nav', array(
			'name' => 'notification',
			'text' => elgg_echo('notifications:personal'),
			'href' => "/notifications/personal",
		));
		elgg_register_menu_item('settings_nav', array(
			'name' => 'notification_groups',
			'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
			'href' => "/notifications/group",
		));

                require $path;
                return true;
        }
}

function gc_theme_tags_page_handler($segments, $handle) {
	$pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';
	set_input('selected_tab', $segments[0]);
	require_once "$pages_dir/tags/tagslist.php";
	return true;
}

function gc_theme_groups_page_handler($segments, $handle) {
	$pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';

	switch ($segments[0]) {
		case 'all':
			require_once "$pages_dir/groups/allgroups.php";
			return true;

		case 'list':
			require_once "$pages_dir/groups/groupslist.php";
			return true;

		case 'invitations':
			set_input('username', $segments[1]);
			require_once "$pages_dir/groups/invitations.php";
			return true;

		case 'profile':
			elgg_set_context($segments[0]);
			elgg_set_page_owner_guid($segments[1]);
			$group = get_entity($segments[1]);
        		gc_groups_register_profile_buttons($group);
			require_once "$pages_dir/groups/wall.php";
			return true;

		case 'members':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/members.php";
			return true;

		case 'export_members':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/export_members.php";
			return true;

		case 'info':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/info.php";
			return true;

		case 'blog':
			elgg_set_page_owner_guid($segments[1]);
			//require_once "$pages_dir/groups/blog.php";
			return true;

		case 'bookmarks':
			elgg_set_page_owner_guid($segments[1]);
			//require_once "$pages_dir/groups/bookmarks.php";
			return true;

		case 'files':
			elgg_set_page_owner_guid($segments[1]);
			//require_once "$pages_dir/groups/files.php";
			return true;

		case 'discussion':
			elgg_set_page_owner_guid($segments[1]);
			require_once "$pages_dir/groups/discussion.php";
			return true;

		case 'pages':
			elgg_set_page_owner_guid($segments[1]);
			//require_once "$pages_dir/groups/pages.php";
			return true;

		case 'albums':
			elgg_set_page_owner_guid($segments[1]);
			//require_once "$pages_dir/groups/albums.php";
			return true;
		default:
                	return groups_page_handler($segments);
			//global $gc_theme_original_groups_page_handler;
			//return call_user_func($gc_theme_original_groups_page_handler, $segments, $handle);
	}
}

function gc_theme_profile_page_handler($page) {
	if (isset($page[0])) {
		$username = $page[0];
		$user = get_user_by_username($username);
		elgg_set_page_owner_guid($user->guid);
	}

	// short circuit if invalid or banned username
	if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	}

	$action = NULL;
	if (isset($page[1])) {
		$action = $page[1];
	}

	$pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';
	switch ($action) {
		case 'edit':
			// use for the core profile edit page
			require get_config('path') . 'pages/profile/edit.php';
			return true;
			break;
		
		case 'info':
			require $pages_dir.'/profile/info.php';
			return true;
		default:
			require $pages_dir.'/profile/info.php';
/*
			if (elgg_is_logged_in()) {
				require dirname(__FILE__) . '/pages/profile/info.php';
			} else {
				require dirname(__FILE__) . '/pages/profile/info.php';
			}
*/
			
			return true;
	}
}

function thewire_group_page_handler($page) {
        $base_dir = elgg_get_plugins_path() . 'gc_theme/pages/thewire';

        if (!isset($page[0])) {
                $page = array('all');
        }

        switch ($page[0]) {
                case "group":
			group_gatekeeper();
                        include "$base_dir/owner.php";
                        break;
                default:
                        return false;
        }
        return true;
}

function thewire_gc_page_handler($page) {

	if (! elgg_is_logged_in()) {
		forward('/dashboard');
	} else {
	$GLOBALS['GC_THEME']->debug("page ".var_export($page,true));
        if (isset($page[0]) && (
	    $page[0] == 'all' || 
	    $page[0] == 'edit' || 
	    $page[0] == 'friends' || 
	    $page[0] == 'owner' ||
	    $page[0] == 'tag' || 
	    $page[0] == 'thread' || 
	    $page[0] == 'view'
	)) {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/thewire';
		if ( isset($page[1])) {
			set_input('guid', $page[1]);
			set_input('tag', $page[1]);
		}
		include $base_dir.'/'.$page[0].'.php';
        } else {
                return thewire_page_handler($page);
        }
        return true;
	}
}

function file_leftmenu_page_handler($page) {

	if (! elgg_is_logged_in()) {
		forward('/dashboard');
	} else {
        	$base_dir = elgg_get_plugins_path() . 'gc_theme/pages/file';
        	if (isset($page[0]) && ($page[0] == 'all' || $page[0] == 'search')) {
			include "$base_dir/world.php";
        	} elseif (isset($page[0]) && $page[0] == 'friends') {
			include "$base_dir/friends.php";
        	} elseif (isset($page[0]) && $page[0] == 'group') {
			include "$base_dir/owner.php";
        	} elseif (isset($page[0]) && isset($page[1]) && $page[0] == 'view') {
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
        	} else {
        	        return file_page_handler($page);
        	}
        	return true;
	}
}

function gc_members_page_handler($page) {
	if (! elgg_is_logged_in()) {
		forward('/dashboard');
	} else {
        	$base = elgg_get_plugins_path() . 'gc_theme/pages/members';
        	if (!isset($page[0])) {
                	$page[0] = 'online';
        	}

        	$vars = array();
        	$vars['page'] = $page[0];
	
        	if ($page[0] == 'search') {
                	$vars['search_type'] = $page[1];
                	require_once "$base/search.php";
        	} else {
                	require_once "$base/index.php";
        	}
        	return true;
	}
}

function gc_friends_page_handler($page_elements, $handler) {
        elgg_set_context('friends');
       	$base = elgg_get_plugins_path() . 'gc_theme/pages/friends';
	$GLOBALS['GC_THEME']->debug("gc_friends_page_handler:page_elements ".var_export($page_elements,true));
        if (isset($page_elements[0]) && $user = get_user_by_username($page_elements[0])) {
                elgg_set_page_owner_guid($user->getGUID());
        }
	/*
        if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		$user = elgg_get_logged_in_user_entity();
		//elgg_register_menu_item('page', array( 'name' => 'friends:view:collections', 'text' => elgg_echo('friends:collections'), 'href' => "collections/$user->username",));
        }
	*/

        switch ($handler) {
                case 'friends':
        		if (isset($page_elements[0]) && $page_elements[0] == 'multi_invite') {
                        	require_once "$base/multi_invite.php";
			} elseif (isset($page_elements[0]) && $page_elements[0] == 'search') {
                		$vars['search_type'] = $page_elements[1];
                        	require_once "$base/search.php";
			} else {
				elgg_register_menu_item('title', array(
					'name' => 'multi_invite',
					'href' => "/friends/multi_invite",
					'text' => elgg_echo('friends:multi_invite'),
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('friends'),
					'priority' => 40,
				));
                        	require_once "$base/index.php";
			}
                        break;
                case 'friendsof':
                        require_once "$base/of.php";
                        break;
                default:
                        return false;
        }
        return true;
}
function gc_collections_page_handler($page_elements) {
        elgg_set_context('friends');
	elgg_unregister_menu_item('title', 'multi_invite');
       	$base = elgg_get_plugins_path() . 'gc_theme/pages/friends';
        if (isset($page_elements[0])) {
                if ($page_elements[0] == "add") {
                        elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
			$user = elgg_get_logged_in_user_entity();
			//elgg_register_menu_item('page', array( 'name' => 'friends:view:collections', 'text' => elgg_echo('friends:collections'), 'href' => "collections/$user->username",));
                        require_once "{$base}/collections/add.php";
                        return true;
                } elseif ($page_elements[0] == "owner") {
                        $user = get_user_by_username($page_elements[1]);
                        if ($user) {
                                elgg_set_page_owner_guid($user->getGUID());
                                if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
					$user = elgg_get_logged_in_user_entity();
					//elgg_register_menu_item('page', array( 'name' => 'friends:view:collections', 'text' => elgg_echo('friends:collections'), 'href' => "collections/$user->username",));
                                }
                                require_once "{$base}/collections/view.php";
                                return true;
                        }
                }
        }
        return false;
}


function all_my_groups() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/all_my_groups.php';
        return true;
}
function event_list() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/event_list.php';
        return true;
}
function extra_feed_comments() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/extra_feed_comments.php';
        return true;
}

function extra_feed_replies() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/extra_feed_replies.php';
        return true;
}
function groups_autocomplete() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/groups_autocomplete.php';
        return true;
}
function tags_autocomplete() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/tags_autocomplete.php';
        return true;
}
function multi_invite_autocomplete() {
        require_once elgg_get_plugins_path() . 'gc_theme/lib/multi_invite_autocomplete.php';
        return true;
}

function gc_theme_dashboard_handler() {
	$GLOBALS['GC_THEME']->debug("in start:gc_theme_dahboard_handler");
        require_once elgg_get_plugins_path() . 'gc_theme/pages/dashboard.php';
        return true;
}

function gc_theme_activity_handler() {
        require_once elgg_get_plugins_path() . 'gc_theme/pages/activity.php';
        return true;
}

function gc_theme_custom_river_handler() {
	$GLOBALS['GC_THEME']->debug("in start:gc_theme_custom_river_handler");
        require_once elgg_get_plugins_path() . 'gc_theme/pages/custom_river.php';
        return true;
}

function gc_theme_search_page_handler($segments, $handle) {
        $pages_dir = elgg_get_plugins_path() . 'gc_theme/pages';

        elgg_set_page_owner_guid($segments[1]);
        require_once "$pages_dir/search/index.php";
        return true;
}
function gc_groups_register_profile_buttons($group) {
			if ($group->isMember(elgg_get_logged_in_user_entity())) {
				if ($group->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
					// leave
					$url = elgg_get_site_url() . "action/groups/leave?group_guid={$group->getGUID()}";
					$url = elgg_add_action_tokens_to_url($url);
					$text = 'groups:leave';
					elgg_unregister_menu_item('title', $text);
					elgg_register_menu_item('title', array(
						'name' => $text,
						'href' => $url,
						'text' => elgg_echo($text),
						'link_class' => 'elgg-button elgg-button-action',
						'confirm' => TRUE
					));
				}
			}
//if (elgg_in_context('group_profile')) {
                if (elgg_is_logged_in() && $group->canEdit()) {
			if (!$group->isPublicMembership()) {
                        	$url = elgg_get_site_url() . "groups/requests/{$group->getGUID()}";

                        	$count = elgg_get_entities_from_relationship(array(
                        	        'type' => 'user',
                        	        'relationship' => 'membership_request',
                        	        'relationship_guid' => $group->getGUID(),
                        	        'inverse_relationship' => true,
                        	        'count' => true,
                        	));

                        	if ($count) {
                        	        $text = elgg_echo('groups:membershiprequests:pending', array($count));
                        	} else {
                        	        $text = elgg_echo('groups:membershiprequests');
                        	}

				elgg_register_menu_item('title', array(
					'name' => 'membership_requests',
					'text' => $text,
					'href' => $url,
					'link_class' => 'elgg-button elgg-button-action',
				));
                	}
                        elgg_register_menu_item('title', array(
				'name' => 'mail',
				'text' => elgg_echo('group_tools:menu:mail'),
				'href' => "groups/mail/".$group->getGUID(),
				'link_class' => 'elgg-button elgg-button-action',
			));

                }





//        }
/*
        $actions = array();
        // group owners
        if ($group->canEdit()) {
                // edit and invite
                $url = elgg_get_site_url() . "groups/requests/{$group->getGUID()}";
                $actions[$url] = 'groups:request';
        }
        if ($actions) {
                foreach ($actions as $url => $text) {
                        elgg_register_menu_item('title', array(
                                'name' => $text,
                                'href' => $url,
                                'text' => elgg_echo($text),
                                'link_class' => 'elgg-button elgg-button-action',
                        ));
                }
        }
*/
}

function gc_file_tools_page_handler($page) {
	$include_file = false;
	
	switch($page[0]) {
		case "list":
			if(elgg_is_xhr() && !empty($page[1])) {
				elgg_set_page_owner_guid($page[1]);
					
				if(get_input("folder_guid", false) !== false) {
					set_input("draw_page", false);
				}
					
				if(isset($page[2])) {
					set_input("folder_guid", $page[2]);
				}
				$include_file = dirname(dirname(__FILE__)) . "/pages/list.php";
			}
			break;
		case "folder":
			if($page[1] == 'new') {
				if(!empty($page[2])) {
					elgg_set_page_owner_guid($page[2]);
				}
				$include_file = dirname(dirname(__FILE__)) . "/pages/folder/new.php";
			} elseif($page[1] == 'edit') {
				if(!empty($page[2])) {
					set_input("folder_guid", $page[2]);

					$include_file = dirname(dirname(__FILE__)) . "/pages/folder/edit.php";
				}
			}
			break;
		case "file":
			if($page[1] == 'new') {
				if(!empty($page[2])) {
					elgg_set_page_owner_guid($page[2]);
				}
				$include_file = dirname(dirname(__FILE__)) . "/pages/file/new.php";
			} elseif($page[1] == 'download') {
				$include_file = dirname(dirname(__FILE__)) . "/pages/file/download.php";
			}
			break;
		case "proc":
			if(file_exists(dirname(dirname(__FILE__)) . "/procedures/" . $page[1] . "/" . $page[2] . ".php")) {
				$include_file = dirname(dirname(__FILE__)) . "/procedures/" . $page[1] . "/" . $page[2] . ".php";
			} else {
				echo json_encode(array('valid' => 0));
				exit;
			}
			break;
	}

	if(!empty($include_file)){
		include($include_file);
		return true;
	} else {
		forward("file/all");
	}		
}
function user_autocomplete() {
	$q = sanitize_string(get_input("q"));
	$limit = (int) get_input("limit", 10);
	
	$result = array();
	
	if(($user = elgg_get_logged_in_user_entity()) && !empty($q)){
		// show hidden (unvalidated) users
		$hidden = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		if($relationship = "notfriends"){
			$dbprefix = elgg_get_config("dbprefix");
			
			// find existing users
			$query_options = array(
				"type" => "user",
				"limit" => $limit,
				"joins" => array("JOIN {$dbprefix}users_entity u ON e.guid = u.guid"),
				"wheres" => array("(u.name LIKE '%{$q}%' OR u.username LIKE '%{$q}%')", "u.banned = 'no'"),
				"order_by" => "u.name asc"
			);
			
			if($entities = elgg_get_entities($query_options)){
				foreach($entities as $entity){
					$result[] = array("type" => "user", "value" => $entity->username,"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name, "name" => $entity->name);
				}
			}
		}
		
		// restore hidden users
		access_show_hidden_entities($hidden);
	}
	
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	
	exit();
}
