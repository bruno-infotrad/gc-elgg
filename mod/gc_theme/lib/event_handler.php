<?php
function gc_theme_pagesetup_handler() {
	if (elgg_get_context() != 'admin') {
		$owner = elgg_get_page_owner_entity();
	
		if (elgg_is_logged_in()) {
			$db_prefix = elgg_get_config('dbprefix');
			$user = elgg_get_logged_in_user_entity();

			elgg_register_menu_item('page', array(
				'name' => 'news',
				'text' => elgg_view_agora_icon('home').elgg_echo('newsfeed').' <span class="elgg-new-feeds"></span>',
				'href' => '/dashboard',
				//Stupid hack because sections cannot be ordered and do not take priority into account
				// https://github.com/Elgg/Elgg/issues/3625 https://github.com/Elgg/Elgg/issues/5488 https://gist.github.com/mrclay/2242916
				'section' => '0discussions',
				'priority' => 10,
	//			'contexts' => array('dashboard'),
			));
			if (elgg_is_active_plugin('thewire')) {
				elgg_register_menu_item('page', array(
					'name' => 'wire',
					'parent_name' => 'news',
					'text' => elgg_echo('thewire'),
					'href' => "/thewire/all",
					'section' => '0discussions',
					'priority' => 15,
				));
			}

			elgg_unregister_menu_item('extras', 'bookmark');
			elgg_unregister_menu_item('page', '1_account');
			elgg_unregister_menu_item('page', '1_plugins');
			elgg_unregister_menu_item('page', '1_statistics');
			$active_plugins = elgg_get_plugins();
			foreach ($active_plugins as $plugin) {
				$plugin_id = $plugin->getID();
				elgg_unregister_menu_item('page', $plugin_id);
			}
	
			if (elgg_is_active_plugin('search')) {
				elgg_unregister_menu_item('page', 'all');
				elgg_unregister_menu_item('page', 'item:group');
				elgg_unregister_menu_item('page', 'item:user');
				elgg_unregister_menu_item('page', 'item:object:blog');
				elgg_unregister_menu_item('page', 'item:object:file');
				elgg_unregister_menu_item('page', 'item:object:groupforumtopic');
				elgg_unregister_menu_item('page', 'item:object:page');
				elgg_unregister_menu_item('page', 'item:object:page_top');
				elgg_unregister_menu_item('page', 'item:object:thewire');
				elgg_unregister_menu_item('page', 'search_types:tags');
				elgg_unregister_menu_item('page', 'search_types:comments');
			}

			if (elgg_is_active_plugin('messages')) {
				elgg_unregister_menu_item('page', 'messages:inbox');
				elgg_register_menu_item('page', array(
					'name' => 'messages:inbox',
					'text' => elgg_view_agora_icon('messages').elgg_echo('messages').'<span class="gc-messages-new"></span>',
					'href' => "/messages/inbox/$user->username",
					'priority' => 20,
					'section' => '0discussions',
				));
				elgg_unregister_menu_item('page', 'messages:sentmessages');
				elgg_register_menu_item('page', array(
					'name' => 'messages:sentmessages',
					'parent_name' => 'messages:inbox',
					'text' => elgg_echo('messages:sentmessages'),
					'title' => elgg_echo('messages:sentmessages'),
					'href' => "/messages/sent/" . $user->username,
					'section' => '0discussions',
					//'priority' => 21,
					//'context' => 'messages',
				));
			}
	
			if (elgg_is_active_plugin('groups')) {
				elgg_unregister_menu_item('page', 'membership_requests');
				elgg_unregister_menu_item('page', 'groups:owned');
				elgg_unregister_menu_item('page', 'groups:member');
				elgg_unregister_menu_item('page', 'groups:all');
				elgg_unregister_menu_item('page', 'mail');
				elgg_unregister_menu_item('page', 'membership_requests');
				$num_tba=0;
				$join_requests=0;
				$num_invitations = count(groups_get_invited_groups(elgg_get_logged_in_user_guid()));
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
				if ($num_tba) {
					$text = "<span id=\"gc-group-invitations\"> ".$num_tba."</span>";
					$groups_item_class = 'invitations-exist';
				}
				$own_group_list = elgg_get_entities(array(
					'type' => 'group',
					'owner_guid' => elgg_get_logged_in_user_guid(),
				));
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
				if ($join_requests) {
					$join_text = "<span id=\"gc-group-invitations\"> ".$join_requests."</span>";
					$groups_item_class = 'invitations-exist';
				}

				elgg_register_menu_item('page', array(
					'section' => '1communities',
					'name' => 'groups:all',
					'text' => elgg_view_agora_icon('groups').elgg_echo('see:allgroups'),
					'href' => "/groups/all?filter=my_groups",
					'priority' => 30,
					'item_class' => $groups_item_class,
				));
				$options = array( 'type' => 'group',
						'relationship' => 'member',
						'relationship_guid' => $user->getGUID(),
						'joins' => array("JOIN ".$db_prefix."groups_entity ge ON e.guid=ge.guid"),
						'order_by' => 'name asc',
						'limit' => 10,
						'offset' => 0,
				);
                		$groups = elgg_get_entities_from_relationship($options);
				//Hack: Use big number to get all groups
				$totalgroups = $user->getGroups(array('subtype'=>''), 10000);

				if ($num_tba) {
					elgg_register_menu_item('page', array(
						'name' => "groups-invitations",
						'text' => elgg_echo('groups:invitations:short').$text,
						'href' => "/groups/invitations/$user->username",
						'priority' => 30,
						'parent_name' => 'groups:all',
						'section' => '1communities',
					));
				}
				if ($join_requests) {
					elgg_register_menu_item('page', array(
						'name' => "groups-join-request",
						'text' => elgg_echo('groups:membershiprequests:short').$join_text,
						'href' => "/groups/all?filter=groups_i_own",
						'priority' => 30,
						'parent_name' => 'groups:all',
						'section' => '1communities',
					));
				}

				foreach ($groups as $group) {
					if (strlen($group->name) > 23) {
						$group_name = substr($group->name,0,23).'...';
					} else {
						$group_name = $group->name;
					}
					elgg_register_menu_item('page', array(
						'name' => "group-$group->guid",
						'text' => $group_name,
						'title' => $group->name,
						'href' => $group->getURL(),
						'priority' => 33,
						'parent_name' => 'groups:all',
						'section' => '1communities',
					));
				}
				elgg_register_menu_item('page', array(
					'name' => 'groups:yours',
					'text' => elgg_echo('groups:yours'),
					'title' => elgg_echo('groups:yours'),
					'href' => "/groups/all?filter=my_groups",
                        		'priority' => 30,
					'parent_name' => 'groups:all',
					'section' => '1communities',
                		));
				elgg_register_menu_item('page', array(
					'name' => 'groups:groups_i_own',
					'text' => elgg_echo('groups:owned'),
					'title' => elgg_echo('groups:owned'),
					'href' => "/groups/all?filter=groups_i_own",
                        		'priority' => 30,
					'parent_name' => 'groups:all',
					'section' => '1communities',
                		));
				elgg_register_menu_item('page', array(
					'name' => 'groups:groups_list',
					'text' => elgg_echo('groups:list'),
					'title' => elgg_echo('groups:list'),
					'href' => "/groups/list?filter=a",
                        		'priority' => 31,
					'parent_name' => 'groups:all',
					'section' => '1communities',
                		));
				if (count($totalgroups) > 10) {
					elgg_load_js('elgg.all_my_groups');
					elgg_register_menu_item('page', array(
						'name' => 'groups:yours:more',
						'text' => '+ '.elgg_echo('groups:yours:more'),
						'title' => elgg_echo('groups:yours:more'),
						'href' => "#",
                        			'priority' => 34,
						'parent_name' => 'groups:all',
						'section' => '1communities',
						'id' => 'all-my-groups',
                			));
				}
				
				elgg_register_menu_item('page', array(
					'name' => 'groups-add',
					'text' => elgg_echo('groups:add'),
					'href' => "/groups/add",
					'priority' => 32,
					'parent_name' => 'groups:all',
					'section' => '1communities',
				));
				elgg_unregister_menu_item('page', 'groups:invitations');
				elgg_unregister_menu_item('page','groups:user:invites');
/*
				elgg_register_menu_item('title', array(
					'name' => 'groups:user:invites',
					'text' => elgg_echo('groups:invitations').$text,
					'href' => "groups/invitations/$user->username",
                        		'priority' => 34,
                		));
*/
			}
	
			elgg_unregister_menu_item('page', 'friends');
			elgg_unregister_menu_item('page', 'friends:of');
			elgg_unregister_menu_item('page', 'collections');
			elgg_unregister_menu_item('page', 'friends:view:collections');

			/*
			elgg_register_menu_item('page', array(
				'name' => 'friends',
				'text' => elgg_view_agora_icon('colleagues').elgg_echo('friends'),
				'href' => "/friends/$user->username",
				'section' => '1communities',
				'priority' => 30,
			));
*/
			if ($owner instanceof ElggUser && $owner->guid != $user->guid) {

                                if (check_entity_relationship($user->guid, 'friend', $owner->guid)) {
                                        elgg_register_menu_item('extras', array(
                                                'name' => 'removefriend',
                                                'text' => elgg_echo('friend:remove'),
                                                'href' => "/action/friends/remove?friend=$owner->guid",
                                                'is_action' => TRUE,
                                                'contexts' => array('profile'),
                                        ));
                                } else {
                                        elgg_register_menu_item('title', array(
                                                'name' => 'addfriend',
                                                'text' => elgg_echo('friend:shortadd'),
                                                'title' => elgg_echo('friend:add'),
                                                'href' => "/action/friends/add?friend=$owner->guid",
                                                'is_action' => TRUE,
                                                'link_class' => 'gc-addfriend elgg-button elgg-button-special',
                                                'contexts' => array('profile'),
                                                'priority' => 1,
                                        ));
                                }
/*
                                if (elgg_is_active_plugin('messages')) {
                                        elgg_register_menu_item('title', array(
                                                'name' => 'message',
                                                //'text' => elgg_view_icon('speech-bubble-alt') . elgg_echo('messages:message'),
                                                'text' => elgg_echo('messages:message'),
                                                'href' => "/messages/compose?send_to=$owner->guid",
                                                'link_class' => 'elgg-button elgg-button-action',
                                                'contexts' => array('profile'),
                                        ));
                                }
*/
                        }

			if (elgg_is_active_plugin('members')) {
				elgg_register_menu_item('page', array(
					'name' => 'members',
					'text' => elgg_view_agora_icon('users').elgg_echo('gc_theme:people'),
					'href' => "/members",
					'section' => '1communities',
					'priority' => 40,
				));
			}
		
				elgg_register_menu_item('title', array(
					'name' => 'editprofile',
					'href' => "/collections/add/$user->guid",
					'text' => elgg_echo('profile:edit'),
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('collections'),
					'priority' => 50,
				));
				elgg_register_menu_item('title', array(
					'name' => 'multi_invite',
					'href' => "/friends/multi_invite",
					'text' => elgg_echo('friends:multi_invite'),
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('friends'),
					'priority' => 40,
				));
			if ($owner->guid == $user->guid) {
				elgg_register_menu_item('title', array(
					'name' => 'editprofile',
					'href' => "/profile/$user->username/edit",
					'text' => elgg_echo('gc_theme:profile:edit'),
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('profile'),
					'priority' => 50,
				));
				elgg_unregister_menu_item('page','edit_avatar');
				elgg_unregister_menu_item('page','edit_profile');
				elgg_unregister_menu_item('extras','avatar:edit');
				elgg_register_menu_item('title', array(
					'name' => 'editavatar',
					'href' => "/avatar/edit/$user->username",
					'text' => elgg_echo('gc_theme:avatar:edit'),
					'link_class' => 'elgg-button elgg-button-action',
					'contexts' => array('profile'),
					'priority' => 60,
				));
			}

			// SR [2012-08-29] Adding a button to pull data from AD
			if (elgg_is_active_plugin('dfait_adsync')) {
				// Make sure that the owner of this page is the current user OR that this current user is an admin.
				if (elgg_is_admin_logged_in()) {
					elgg_register_menu_item('dfait_adsync', array(
						'name' => 'syncuserinfo',
						'priority' => 5,
						'href' => "/dfait_adsync/sync/$owner->username",
						'text' => elgg_echo('adsync:syncdata'),
						'link_class' => 'elgg-button elgg-button-action',
						'contexts' => array('profile'),
						));
				}
				// SR [2012-09-07] add a button redirecting the user to TeamInfo to allow the 
				// editing of his profile.
				if (($owner->guid == $user->guid) || (elgg_is_admin_logged_in())) {
					$teaminfo_url = elgg_get_plugin_setting('adsync_teaminfo_url', 'dfait_adsync');
					$teaminfo_url .= "/DetailPerson.aspx?dfaitEDSId=" . $owner->username;
					elgg_register_menu_item('dfait_adsync', array(
						'name' => 'editteaminfo',
						'priority' => 100,
						'href' => $teaminfo_url,
						'text' => elgg_echo('adsync:edit_teaminfo'),
						'link_class' => 'elgg-button elgg-button-action',
						'contexts' => array('profile'),
						));
					elgg_register_menu_item('dfait_adsync', array(
						'name' => 'getteaminfoavatar',
						'priority' => 5,
						'href' => "/dfait_adsync/getavatar/$owner->username",
						'text' => elgg_echo('adsync:getavatar'),
						'link_class' => 'elgg-button elgg-button-action',
						'contexts' => array('profile'),
						));

				}
			}
			
			if (elgg_is_active_plugin('file')) {
				elgg_unregister_menu_item('page', 'file:all');
				elgg_unregister_menu_item('page', 'file:document');
				elgg_register_menu_item('page', array(
					'name' => 'files',
					'text' => elgg_view_agora_icon('files').elgg_echo('files'),
					'href' => "/file/owner/$user->username",
					'section' => '2contributions',
					'priority' => 70,
				));
			}	

			if (elgg_is_active_plugin('polls')) {
				elgg_register_menu_item('page', array(
					'name' => 'polls',
					'text' => elgg_view_agora_icon('polls').elgg_echo('item:object:poll'),
					'href' => "/polls/owner/$user->username",
					'section' => '2contributions',
					'priority' => 72,
				));
			}
			
			if (elgg_is_active_plugin('pages')) {
				elgg_register_menu_item('page', array(
					'name' => 'pages',
					'text' => elgg_view_agora_icon('pages').elgg_echo('pages'),
					'href' => "/pages/all",
					'section' => '2contributions',
					'priority' => 74,
				));
			}
	
			if (elgg_is_active_plugin('blog')) {
				elgg_register_menu_item('page', array(
					'name' => 'blog',
					'text' => elgg_view_agora_icon('blogs').elgg_echo('blog'),
					'href' => "/blog/friends/$user->username",
					'section' => '2contributions',
					'priority' => 76,
				));
			}

			if (elgg_is_active_plugin('event_manager')) {
				elgg_register_menu_item('page', array(
					'name' => 'event_manager',
					'text' => elgg_view_agora_icon('eventmanager').elgg_echo('event_manager:menu:title'),
					'href' => "/events/event/list",
					'section' => '2contributions',
					'priority' => 77,
				));
			}
			
			if (elgg_is_active_plugin('tagcloud')) {
				elgg_register_menu_item('page', array(
					'name' => 'tags',
					'text' => elgg_echo('tagcloud:allsitetags'),
					'href' => '/tags',
					'priority' => 100,
					'is_trusted' => true,
				));
			}
			
/*
			elgg_register_menu_item('page', array(
				'name' => 'activity',
				'text' => elgg_echo('activity'),
				'href' => '/activity',
				'priority' => 101,
	//			'contexts' => array('dashboard'),
			));
*/
	
	//			elgg_register_menu_item('page', array(
	//				'section' => 'default',
	//				'name' => 'profile',
	//				'text' => elgg_echo($_SESSION['user']->name),
	//				'href' => "/profile/$user->username",
	//				'contexts' => array('dashboard'),
	//				'priority' => 10,
	//			));
	
	
			$address = urlencode(current_page_url());
			
//			if (elgg_is_active_plugin('bookmarks')) {
//				elgg_register_menu_item('extras', array(
//					'name' => 'bookmark',
//					'text' => elgg_view_icon('push-pin-alt') . elgg_echo('bookmarks:this'),
//					'href' => "bookmarks/add/$user->guid?address=$address",
//					'title' => elgg_echo('bookmarks:this'),
//					'rel' => 'nofollow',
//				));
//			}
			
			if (elgg_is_active_plugin('reportedcontent')) {
				elgg_unregister_menu_item('footer', 'report_this');
			
				$href = "javascript:elgg.forward('reportedcontent/add'";
				$href .= "+'?address='+encodeURIComponent(location.href)";
				$href .= "+'&title='+encodeURIComponent(document.title));";
					
				elgg_register_menu_item('extras', array(
					'name' => 'report_this',
					'href' => $href,
					'text' => elgg_view_icon('report-this') . elgg_echo('reportedcontent:this'),
					'title' => elgg_echo('reportedcontent:this:tooltip'),
					'priority' => 500,
				));
			}
			
			/**
			 * TOPBAR customizations
			 */
			//Want our logo present, not Elgg's
			elgg_unregister_menu_item('topbar', 'elgg_logo');
			elgg_unregister_menu_item('topbar', 'friends');
			elgg_unregister_menu_item('topbar', 'messages');
/*
			$site = elgg_get_site_entity();
			elgg_register_menu_item('topbar', array(
				'name' => 'logo',
				'href' => '/',
				'text' => "<h1 id=\"gc-topbar-logo\">$site->name</h1>",
				'priority' => 1,
			));
	*/	
			elgg_register_menu_item('topbar', array(
				'name' => 'account',
				'section' => 'alt',
				//'text' => elgg_echo('account')." ▼",
				'text' => elgg_echo('account')." &#x25bc;",
				'href' => "#",
				'priority' => 1000,
			));
	
			elgg_register_menu_item('topbar', array(
				'name' => 'home',
				'href' => '/dashboard',
				'text' => elgg_echo('home'),
				'section' => 'alt',
				'priority' => 1,
			));
			
			if (elgg_is_active_plugin('profile')) {
				elgg_unregister_menu_item('topbar', 'profile');
				elgg_register_menu_item('topbar', array(
					'name' => 'profile',
					'section' => 'alt',
					'text' => elgg_echo('topbar:profile'),
					'href' => "/profile/$user->username",
					'priority' => 2,
				));
			}
			
			elgg_unregister_menu_item('topbar', 'usersettings');
			elgg_register_menu_item('topbar', array(
				'name' => 'usersettings',
				'parent_name' => 'account',
				'href' => "/settings/user/$user->username",
				'text' => elgg_echo('settings:user'),
				'section' => 'alt',
			));
			
			if (elgg_is_active_plugin('notifications')) {
				elgg_register_menu_item('topbar', array(
					'name' => 'notifications',
					'parent_name' => 'account',
					'href' => "/notifications/personal",
					'text' => elgg_echo('notifications:personal'),
					'section' => 'alt',
				));
			}
			
			elgg_unregister_menu_item('topbar', 'logout');
			if (! elgg_is_active_plugin('ntlm_sso')) {
				elgg_register_menu_item('topbar', array(
					'name' => 'logout',
					'parent_name' => 'account',
					'href' => '/action/logout',
					'is_action' => TRUE,
					'text' => elgg_echo('logout'),
					'section' => 'alt',
					'priority' => 1000, //want this to be at the bottom of the list no matter what
				));
			}
			elgg_unregister_menu_item('page', 'site_stats');
			if(elgg_in_context("groups") && ($owner instanceof ElggGroup)){
				$user=elgg_get_logged_in_user_entity();
				if(!empty($user)){
					// check for admin transfer
					$admin_transfer = elgg_get_plugin_setting("admin_transfer", "group_tools");
                        		if(($admin_transfer == "owner") && (roles_has_role($user,'im_admin'))){
                                		elgg_extend_view("groups/edit", "group_tools/forms/admin_transfer", 400);
                        		}
				}
			}
		} else {
	
				$login_box = elgg_view('core/account/login_box');
				$params = array(
	                			'content' => $content,
	                			'sidebar' => $login_box
			);
		}
	}
}

function gc_relationship_notification_hook($event, $type, $object) {

        $user_one = get_entity($object->guid_one);
        $user_two = get_entity($object->guid_two);

        return notify_user($object->guid_two,
                        $object->guid_one,
                        elgg_echo('friend:newfriend:subject', array($user_one->name)),
                        elgg_echo("friend:newfriend:body", array($user_one->name, $user_one->getURL(), elgg_get_site_url()))
        );
}

function ad2elgg_user_update($event, $type, $user) {
	if(!empty($user) && ($user instanceof ElggUser)){
		$elgg_guid = $user->getGUID();
		$sql_dfaitedsid = sanitise_string($user->username);
		$sql_query = "SELECT * FROM ad2elgg_users where dfaitedsid = '{$sql_dfaitedsid}'";
		$GLOBALS['DUA_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			if (count($row) != 1) {
				$GLOBALS['DUA_LOG']->error("SQL: multiple $dfaitedsid already associated with $elgg_guid");
			} else {
				$sql_id = $row->id;
				$sql_elgg_guid = $row->elgg_guid;
				$dfaitedsid = $row->dfaitedsid;
				$account_banned = $row->account_banned;
				$mail = $row->mail;
				$GLOBALS['DUA_LOG']->debug("ROW: id=$sql_id elgg_guid=$sql_elgg_guid account_banned=$account_banned mail=$mail");
				//if (!isset($ProcBy) || empty($ProcBy) || $ProcBy == $GLOBALS['ADSYNC_PID']) {
				if (isset($sql_elgg_guid)) {
					$GLOBALS['DUA_LOG']->error("SQL: $dfaitedsid already associated with $sql_elgg_guid");
				} elseif($account_banned == 'yes') {
					$GLOBALS['DUA_LOG']->error("SQL: account for $dfaitedsid is banned");
				} elseif(! isset($mail)) {
					$GLOBALS['DUA_LOG']->error("SQL: mail for $dfaitedsid is not set");
				} else {
					$sql_query = "UPDATE ad2elgg_users SET elgg_guid = {$elgg_guid}, ProcBy = '' WHERE id = {$sql_id};";
					$GLOBALS['DUA_LOG']->debug("SQL: $sql_query");
					if (update_data($sql_query)) {
						$GLOBALS['DUA_LOG']->debug("SQL: $dfaitedsid associated with $elgg_guid");
						// Replace profile_manager forward to automatically sync the modified user
						 elgg_register_plugin_hook_handler("forward", "system", "ad2elgg_user_update_forward_hook",400);
					} else {
						$GLOBALS['DUA_LOG']->error("SQL: could not associate $dfaitedsid with $elgg_guid");
					}
				}
			}
		}
	}
}
