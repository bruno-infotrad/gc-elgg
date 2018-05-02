<?php
$GLOBALS['GC_THEME'] = new FlexLog(FlexLogLevel::DEBUG);

function gc_theme_init() {
	$plugins_path =  elgg_get_plugins_path();
	require_once 'lib/functions.php';
	require_once 'lib/event_handler.php';
	require_once 'lib/page_handler.php';
	require_once 'lib/hook_handler.php';
	require_once 'lib/thebetter_wire.php';
	// needed to use some function for view
	elgg_register_library('elgg:pages', $plugins_path . 'pages/lib/pages.php');
	elgg_register_library('elgg:file_tools', $plugins_path . 'file_tools/lib/functions.php');
	//For batch notification to work:
	// Intercept blog notification when created when status not published
	elgg_register_plugin_hook_handler('object:notifications', 'object', 'gc_object_notifications_intercept');
	//elgg_register_js('jquery.scrollabletab', '/mod/gc_theme//vendors/jquery.scrollabletab.js','head');
	elgg_register_js('jquery.scrollto', '/mod/gc_theme/vendors/jquery.scrollTo-1.4.3.1-min.js','head');
	elgg_register_js('jquery.scrollabletab', '/mod/gc_theme/vendors/simplescrolltab.js','head');
	//elgg_register_js('jquery.jeditable', '/mod/gc_theme/vendors/jquery.jeditable.mini.js','head');
	elgg_register_js('jquery.multidatespicker', '/mod/gc_theme/vendors/jquery-ui.multidatespicker/jquery-ui.multidatespicker.js','head');
	elgg_register_css('multidatespicker', '/mod/gc_theme/vendors/jquery-ui.multidatespicker/css/mdp.css','head');
        elgg_load_css('multidatespicker');
	//elgg_register_js('jquery.tinyscrollbar', '/mod/gc_theme/vendors/jquery.tinyscrollbar.js','head');
	//elgg_register_js('jquery.scrollabletab', '/mod/gc_theme/vendors/jquery.scrollabletab.js','head');
	elgg_load_js('jquery.scrollto');
	elgg_load_js('jquery.scrollabletab');
	elgg_load_js('jquery.jeditable');
	//elgg_load_js('jquery.multidatespicker');
	elgg_load_js('elgg.contributed_by');
	//elgg_load_js('jquery.tinyscrollbar');
	elgg_load_js('lightbox');
        elgg_load_css('lightbox');
        elgg_unregister_js('elgg.embed');
	$gc_embed_js = elgg_get_simplecache_url('js', 'embed/gc_embed');
        elgg_register_simplecache_view('js/embed/gc_embed');
        elgg_register_js('elgg.embed', $gc_embed_js, 'footer');

	$toggle_long_posts_js = elgg_get_simplecache_url('js', 'toggle_long_posts');
        elgg_register_simplecache_view('js/toggle_long_posts');
        elgg_register_js('elgg.toggle_long_posts', $toggle_long_posts_js, 'footer');
	elgg_load_js('elgg.toggle_long_posts');
	$contribute_to_js = elgg_get_simplecache_url('js', 'contribute_to');
        elgg_register_simplecache_view('js/contribute_to');
        elgg_register_js('elgg.contribute_to', $contribute_to_js, 'footer');
	$gc_comments_js = elgg_get_simplecache_url('js', 'gc_comments');
        elgg_register_simplecache_view('js/gc_comments');
        elgg_register_js('elgg.gc_comments', $gc_comments_js, 'footer');
	$gc_gft_js = elgg_get_simplecache_url('js', 'gc_gft');
        elgg_register_simplecache_view('js/gc_gft');
        elgg_register_js('elgg.gc_gft', $gc_gft_js, 'footer');
	$gc_discussion_js = elgg_get_simplecache_url('js', 'gc_discussion');
        elgg_register_simplecache_view('js/gc_discussion');
        elgg_register_js('elgg.gc_discussion', $gc_discussion_js, 'footer');
	elgg_unregister_js('elgg.thewire');
	$gc_wire_js = elgg_get_simplecache_url('js', 'gc_wire');
        elgg_register_simplecache_view('js/gc_wire');
        elgg_register_js('elgg.gc_wire', $gc_wire_js, 'footer');
	// this way a simplecache JS file can be loaded from a .php file
	elgg_register_simplecache_view("js/elgg/ckeditor.js");
	//$dropzone = elgg_get_simplecache_url('js', 'dropzone');
        //elgg_register_simplecache_view('js/dropzone');
        //elgg_register_js('elgg.dropzone', $dropzone, 'footer');

	// Add wire
	add_group_tool_option('thewire',elgg_echo('gc_theme:enable_group_wire'),TRUE);
	//Register role config hook for im admins
	elgg_register_plugin_hook_handler('roles:config', 'role', 'roles_im_admins_config', 600);
	//Register permissions check hook
	elgg_register_plugin_hook_handler("permissions_check", "group", "im_admin_can_edit_hook");
	//Replace with my search tag hook to add brief description and description in search
	elgg_unregister_plugin_hook_handler('search', 'object', 'search_objects_hook');
	elgg_register_plugin_hook_handler('search', 'object', 'gc_search_objects_hook');
	elgg_unregister_plugin_hook_handler('search', 'comments', 'search_comments_hook');
	elgg_register_plugin_hook_handler('search', 'comments', 'gc_search_comments_hook');
	elgg_unregister_plugin_hook_handler('search', 'group', 'search_groups_hook');
	elgg_register_plugin_hook_handler('search', 'group', 'gc_search_groups_hook');
	elgg_unregister_plugin_hook_handler('search', 'user', 'search_users_hook');
	elgg_register_plugin_hook_handler('search', 'user', 'gc_search_users_hook');
	elgg_unregister_plugin_hook_handler('search', 'tags', 'search_tags_hook');
	elgg_register_plugin_hook_handler('search', 'tags', 'gc_search_tags_hook');
	//elgg_register_plugin_hook_handler('register', 'user', 'gc_register');
	elgg_unregister_plugin_hook_handler('access:collections:write', 'all', 'groups_write_acl_plugin_hook');
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'gc_groups_write_acl_plugin_hook');
	elgg_register_plugin_hook_handler("route", "groups", "gc_group_tools_route_groups_handler",400);
	remove_group_tool_option('activity');
	elgg_register_plugin_hook_handler('file:icon:url','override','gc_file_icon_url_override');
	elgg_unregister_plugin_hook_handler('output:before', 'layout','elgg_views_add_rss_link');
	elgg_register_plugin_hook_handler('output:before', 'layout', 'gc_theme_views_add_rss_link');
	elgg_unregister_plugin_hook_handler('register', 'menu:page', 'bookmarks_page_menu');
	// Trigger update of ad2elgg_user table when username is changed
	elgg_register_event_handler("update", "user", "ad2elgg_user_update");
	// For two column layout
	elgg_unregister_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');
	elgg_unregister_event_handler('pagesetup', 'system', 'usersettings_pagesetup');
	elgg_unregister_page_handler('settings', 'usersettings_page_handler');
	elgg_register_page_handler('settings', 'gc_usersettings_page_handler');
	elgg_unregister_page_handler('notifications', 'notifications_page_handler');
	elgg_register_page_handler('notifications', 'gc_notifications_page_handler');
	elgg_unregister_plugin_hook_handler('prepare', 'notification:create:object:thewire', 'thewire_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:thewire', 'gc_wire_prepare_notification');
	elgg_unregister_page_handler('members', 'members_page_handler');
	elgg_register_page_handler('members', 'gc_members_page_handler');
	elgg_unregister_page_handler("file_tools", "file_tools_page_handler");
	elgg_register_page_handler("file_tools", "gc_file_tools_page_handler");
	elgg_unregister_plugin_hook_handler("route", "file", "file_tools_file_route_hook");
	elgg_register_plugin_hook_handler("route", "file", "gc_file_tools_file_route_hook");
	elgg_unregister_plugin_hook_handler("register", "menu:file_tools_folder_sidebar_tree", "file_tools_folder_sidebar_tree_hook");
	elgg_register_plugin_hook_handler("register", "menu:file_tools_folder_sidebar_tree", "gc_file_tools_folder_sidebar_tree_hook");
	elgg_unregister_plugin_hook_handler("entity:url", "object", "file_tools_folder_url_handler");
	elgg_register_plugin_hook_handler("entity:url", "object", "gc_file_tools_folder_url_handler");
	/**
	 * Customize pages
	 */
	elgg_register_plugin_hook_handler('index', 'system', 'gc_theme_index_handler');
	elgg_unregister_plugin_hook_handler('register', 'menu:user_hover', 'reportedcontent_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'gc_reportedcontent_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'gc_user_entities');
	elgg_register_page_handler('profile', 'gc_theme_profile_page_handler');
	elgg_register_page_handler('dashboard', 'gc_theme_dashboard_handler');
	elgg_unregister_page_handler('search', 'search_page_handler');
	elgg_register_page_handler('search', 'gc_theme_search_page_handler');
	elgg_register_page_handler('tags', 'gc_theme_tags_page_handler');
	elgg_register_page_handler('pages', 'gc_pages_page_handler');
	elgg_register_page_handler('bookmarks', 'gc_bookmarks_page_handler');
	elgg_register_page_handler('polls', 'gc_polls_page_handler');
	elgg_register_page_handler('blog', 'gc_blogs_page_handler');
	elgg_register_page_handler('events', 'gc_event_manager_page_handler');
	elgg_register_page_handler('messages', 'gc_messages_page_handler');
	elgg_unregister_page_handler('site_notifications', 'site_notifications_page_handler');
	elgg_register_page_handler('site_notifications', 'gc_site_notifications_page_handler');
	elgg_register_page_handler('multi_invite_autocomplete', 'multi_invite_autocomplete');
	elgg_register_page_handler('groups_autocomplete', 'groups_autocomplete');
	elgg_register_page_handler('tags_autocomplete', 'tags_autocomplete');
	elgg_register_page_handler('user_autocomplete', 'user_autocomplete');
	elgg_register_page_handler('contribute_to', 'contribute_to_page_handler');
	elgg_register_page_handler('notify_groups', 'notify_groups_page_handler');
	elgg_register_page_handler('pns', 'pns_page_handler');
	elgg_register_page_handler('intro_add_colleagues', 'intro_add_colleagues_page_handler');
	elgg_register_page_handler('intro_join_groups', 'intro_join_groups_page_handler');
	elgg_register_page_handler('intro_upload_avatar', 'intro_upload_avatar_page_handler');
	//Pages for getting extra comments and replies via ajax elgg.get
	elgg_register_page_handler('event_list', 'event_list','header');
	elgg_register_page_handler('extra_feed_comments', 'extra_feed_comments','header');
	elgg_register_page_handler('extra_feed_replies', 'extra_feed_replies','header');
	elgg_register_page_handler('all_my_groups', 'all_my_groups','header');
	//What a hack!  Overriding groups page handler without blowing away other plugins doing the same
	//global $CONFIG, $gc_theme_original_groups_page_handler;
	//$gc_theme_original_groups_page_handler = $CONFIG->pagehandler['groups'];
	elgg_register_page_handler('groups', 'gc_theme_groups_page_handler');
	//elgg_register_ajax_view('blog/composer');
	elgg_register_ajax_view('gc_theme/ajax/add_gc_comment');
	elgg_register_ajax_view('gc_theme/ajax/view/comment');
	elgg_register_ajax_view('gc_theme/ajax/edit_gc_wire');
	elgg_register_ajax_view('gc_theme/ajax/edit_gc_gft');
	elgg_register_ajax_view('gc_theme/ajax/add_discussion_reply');
	elgg_register_ajax_view('gc_theme/ajax/view/discussion_reply');
	elgg_register_ajax_view('compound/composer');
	elgg_register_ajax_view('file/composer');
	elgg_register_ajax_view('poll/composer');
	elgg_register_ajax_view('profile/user_about');
	elgg_register_ajax_view('profile/user_workinfo');
	elgg_register_ajax_view('profile/user_colleagues');
	elgg_register_ajax_view('profile/user_orgchart');
	elgg_register_ajax_view('profile/user_activity');
	elgg_register_ajax_view('profile/user_blog');
	elgg_register_ajax_view('profile/user_groups');
	//Ajax pages for scroll
	elgg_register_ajax_view('gc_theme/ajax/allgroups');
	elgg_register_ajax_view('gc_theme/ajax/blogs');
	elgg_register_ajax_view('gc_theme/ajax/bookmarks_all');
	elgg_register_ajax_view('gc_theme/ajax/bookmarks_friends');
	elgg_register_ajax_view('gc_theme/ajax/bookmarks_group');
	elgg_register_ajax_view('gc_theme/ajax/bookmarks_owner');
	elgg_register_ajax_view('gc_theme/ajax/comments');
	elgg_register_ajax_view('gc_theme/ajax/dashboard');
	elgg_register_ajax_view('gc_theme/ajax/discussion');
	elgg_register_ajax_view('gc_theme/ajax/embed');
	elgg_register_ajax_view('gc_theme/ajax/friends');
	elgg_register_ajax_view('gc_theme/ajax/friendsof');
	elgg_register_ajax_view('gc_theme/ajax/file_friends');
	elgg_register_ajax_view('gc_theme/ajax/file_owner');
	elgg_register_ajax_view('gc_theme/ajax/file_world');
	elgg_register_ajax_view('gc_theme/ajax/group_members');
	elgg_register_ajax_view('gc_theme/ajax/group_wall');
	elgg_register_ajax_view('gc_theme/ajax/members');
	elgg_register_ajax_view('gc_theme/ajax/members/search/name');
	elgg_register_ajax_view('gc_theme/ajax/messages_inbox');
	elgg_register_ajax_view('gc_theme/ajax/replies');
	elgg_register_ajax_view('gc_theme/ajax/site_notifications');
	elgg_register_ajax_view('gc_theme/ajax/pages_friends');
	elgg_register_ajax_view('gc_theme/ajax/pages_owner');
	elgg_register_ajax_view('gc_theme/ajax/pages_world');
	elgg_register_ajax_view('gc_theme/ajax/tagslist');
	elgg_register_ajax_view('gc_theme/ajax/polls');
	elgg_register_ajax_view('gc_theme/ajax/search');
	elgg_register_ajax_view('gc_theme/ajax/thewire');
	elgg_register_ajax_view('gc_theme/ajax/user_activity');
	elgg_register_ajax_view('gc_theme/ajax/user_colleagues');
	elgg_register_ajax_view('gc_theme/ajax/user_groups');
	//organize info screen
	elgg_unextend_view('groups/tool_latest', 'blogs/group_module');
	elgg_extend_view('groups/tool_latest', 'blogs/group_module',499);
	elgg_unextend_view('groups/tool_latest', 'discussion/group_module');
	elgg_extend_view('groups/tool_latest', 'discussion/group_module',500);
	elgg_unextend_view('groups/tool_latest', 'pages/group_module');
	elgg_extend_view('groups/tool_latest', 'pages/group_module',501);
        elgg_unextend_view('groups/tool_latest', 'groups/profile/activity_module');
        elgg_extend_view('groups/tool_latest', 'groups/profile/activity_module',600);
	elgg_unextend_view('groups/tool_latest', 'file/group_module');
	elgg_extend_view('groups/tool_latest', 'file/group_module',601);
	elgg_unextend_view('groups/tool_latest', 'bookmarks/group_module');
	elgg_extend_view('groups/tool_latest', 'bookmarks/group_module',602);

	//Replace event_manager js
	elgg_unextend_view("js/elgg", "js/event_manager/site");
	elgg_extend_view("js/elgg", "js/event_manager/site");
	// Unregister Google map
	//elgg_register_simplecache_view("js/event_manager/googlemaps");
	elgg_unregister_js("event_manager.maps.helper");
	elgg_unregister_js("event_manager.maps.base");
	//Remove sidebar cleanup section
	elgg_unextend_view("groups/edit", "group_tools/forms/cleanup");
	//Replace to add additional tab in group edit to toggle admin notifications
	elgg_extend_view("groups/edit", "group_tools/forms/group_admin_notifications", 376);
	//Same presentation for profile
	elgg_extend_view('profile/tool_latest', 'friends/profile_module');
	elgg_extend_view('profile/tool_latest', 'groups/profile_module');
	elgg_extend_view('profile/tool_latest', 'blogs/profile_module');
	elgg_extend_view('profile/tool_latest', 'thewire/profile_module');
  
	elgg_register_js('elgg.all_my_groups', '/mod/gc_theme/js/lib/all_my_groups.js');
	elgg_register_js('elgg.extra_feed_comments', '/mod/gc_theme/js/lib/extra_feed_comments.js');
	elgg_register_js('elgg.extra_feed_discussion_replies', '/mod/gc_theme/js/lib/extra_feed_discussion_replies.js');
	elgg_register_js('elgg.activity_stream', '/mod/gc_theme/js/lib/ui.activity_stream.js');
	elgg_register_js('elgg.new_feeds', '/mod/gc_theme/js/lib/new_feeds.js');
	elgg_register_js('elgg.new_messages', '/mod/gc_theme/js/lib/new_messages.js');
	elgg_register_js('elgg.gcblog', '/mod/gc_theme/views/default/js/gcblog.js');
	elgg_register_js('elgg.user_handle', '/mod/gc_theme/js/lib/user_handle.js');
	elgg_register_js('elgg.contributed_by', '/mod/gc_theme/js/lib/contributed_by.js');
	elgg_register_js('elgg.toggle_cla', '/mod/gc_theme/js/lib/toggle_cla.js');
	elgg_register_js('elgg.dropzone', '/mod/gc_theme/js/lib/dropzone.js');
	//elgg_register_js('elgg.scroll', '/mod/gc_theme/js/lib/scroll.js');
	//elgg_extend_view('page/components/list', 'js/toggle_long_posts');
	// Tab preferences
	elgg_extend_view('forms/account/settings', 'core/settings/account/gc_preferences');
	elgg_unextend_view('core/settings/statistics', 'profile_manager/account/login_history');

        elgg_register_plugin_hook_handler('usersettings:save', 'user', 'gc_theme_user_settings_save');


	$action_path = $plugins_path . 'gc_theme/actions';
	//Action for sending event notification email to colleagues and post bookmarks to books
	elgg_register_action("event_manager/notify_colleagues", "$action_path/event_manager/notify_colleagues.php");
	elgg_register_action("gc_theme/notify_groups", "$action_path/gc_theme/notify_groups.php");
	elgg_unregister_action("event_manager/event/edit");
	elgg_register_action("event_manager/event/edit","$action_path/event/edit.php");
	//Action for roles
	elgg_register_action("roles_im_admin/make_im_admin", "$action_path/roles_im_admin/make_im_admin.php");
	elgg_register_action("roles_im_admin/revoke_im_admin", "$action_path/roles_im_admin/revoke_im_admin.php");
	elgg_register_action('gc_theme/settings/save',$plugins_path . 'gc_theme/actions/plugins/settings/save.php');
	//elgg_register_action('',$CONFIG->pluginspath . 'gc_theme/settings/usersettings/save.php');
	elgg_register_action('gc_theme/settings/save',$plugins_path . 'gc_theme/actions/plugins/settings/save.php');
	elgg_register_action("compound/add", "$action_path/compound/add.php");
	elgg_register_action("compound/remove_exec_content", "$action_path/compound/remove_exec_content.php");
	elgg_register_action("file/upload", "$action_path/file/upload.php");
	elgg_register_action("file/upload2", "$action_path/file/upload2.php");
	elgg_register_action("dashboard/activity_stream", "$action_path/dashboard/activity_stream.php");
	elgg_register_action("dashboard/new_feeds", "$action_path/dashboard/new_feeds.php");
	elgg_register_action("dashboard/new_messages", "$action_path/dashboard/new_messages.php");
	//elgg_unregister_action('likes/delete');
	//elgg_register_action('likes/delete', "$action_path/likes/delete.php");
	elgg_register_action('friends/multi_invite', "$action_path/friends/multi_invite.php");
	elgg_register_action('groups/intro_join_groups', "$action_path/groups/intro_join_groups.php");
	elgg_unregister_action('blog/save');
	elgg_register_action('blog/save',"$action_path/blog/save.php");
	elgg_unregister_action("comment/save");
	elgg_register_action("comment/save", "$action_path/comment/save.php");
	elgg_unregister_action('discussion/reply/save');
	elgg_register_action('discussion/reply/save', "$action_path/discussion/reply/save.php");
	elgg_unregister_action("group_tools/mail");
	elgg_register_action("group_tools/mail", $action_path."/group_tools/mail.php");
	elgg_register_action("groups/edit", $action_path."/groups/edit.php");
	elgg_register_action("gc_theme/pns", $action_path."/gc_theme/pns.php");
	elgg_register_action("notificationsettings/single_groupsave", $action_path."/notificationsettings/single_groupsave.php");
	// Remove admin for toggling notification for group
	elgg_unregister_action("group_tools/notifications");
	elgg_register_action("group_tools/notifications", $action_path . "/group_tools/notifications.php");
	// Register rename of collections
	elgg_register_action("friends/collections/rename", $action_path . "/friends/collections/rename.php");
	// Poll with title
	elgg_unregister_action("polls/edit");
	elgg_register_action("polls/edit","$action_path/polls/edit.php");
	// Override reported content action to send email
	$action_path = $plugins_path . "gc_theme/actions/reportedcontent";
	elgg_unregister_action('reportedcontent/add');
	elgg_register_action('reportedcontent/add', "$action_path/add.php");
	// Override join action for closed group (bug 59)
	$action_path = $plugins_path . "gc_theme/actions/groups/membership";
	elgg_unregister_action("groups/join");
	elgg_register_action("groups/join", "$action_path/join.php");
	//Customize existing page handler (for left menu)
	elgg_register_page_handler('thewire_group', 'thewire_group_page_handler');
	elgg_register_page_handler('thewire', 'thewire_gc_page_handler');
	elgg_register_page_handler('file', 'file_leftmenu_page_handler');
	elgg_unregister_page_handler('friends', 'friends_page_handler');
	elgg_unregister_page_handler('friendsof', 'friends_page_handler');
	elgg_unregister_page_handler('collections', 'collections_page_handler');
	elgg_register_page_handler('friends', 'gc_friends_page_handler');
	elgg_register_page_handler('friendsof', 'gc_friends_page_handler');
	elgg_register_page_handler('collections', 'gc_collections_page_handler');
	// Special page handler for AD amalgamation
	elgg_register_page_handler('delete_unused_accounts', 'delete_unused_accounts_page_handler');
	elgg_register_page_handler('reassign_objects', 'reassign_objects_page_handler');
	elgg_register_page_handler('find_xCIDA_users_with_profile_info', 'fxuwpi_page_handler');
	

	/**
	 * Customize menus
	 */
	//elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'groups_activity_owner_block_menu');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'discussion_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'discussion_add_to_river_menu');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'gc_theme_river_menu_handler');
	elgg_unregister_plugin_hook_handler('register','menu:entity','thewire_setup_entity_menu_items');
	elgg_register_plugin_hook_handler('register','menu:entity','gc_thewire_discussion_reply_setup_entity_menu_items');
	// Unregister file and blog menu so as not to have two entries
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'file_owner_block_menu');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'thewire_owner_block_menu');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'pages_owner_block_menu');
	//Work in progress
	//elgg_trigger_plugin_hook('prepare', "menu:$menu_name", $vars, $vars['menu']);
	//elgg_register_plugin_hook_handler('register', 'menu:entity', 'gc_dashboard_delete_item');

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'gc_theme_owner_block_menu_handler', 600);
	elgg_register_plugin_hook_handler('register', 'menu:profile', 'gc_theme_profile_menu_handler');
        elgg_register_plugin_hook_handler('register', 'menu:entity', 'event_entity_menu_setup');

	
	elgg_register_event_handler('pagesetup', 'system', 'gc_theme_pagesetup_handler', 1000);
	
	/**
	 * Customize permissions
	 */
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'gc_theme_annotation_permissions_handler');
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'gc_theme_container_permissions_handler');
	
	/**
	 * Miscellaneous customizations
	 */
	//Small "correction" to groups profile -- brief description makes more sense to come first!
	elgg_register_plugin_hook_handler('profile:fields', 'group', 'gc_theme_group_profile_fields', 1);
	// menu for pages
	elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'pages_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'gc_pages_entity_menu_setup');
	elgg_register_plugin_hook_handler('format', 'friendly:time', 'gc_get_friendly_time');
		
	//@todo report some of the extra patterns to be included in Elgg core
	elgg_extend_view('css/elgg', 'gc_theme/css');
	elgg_extend_view('js/elgg', 'js/topbar');
	//elgg_unextend_view('js/elgg', 'likes/js');
	//elgg_extend_view('js/elgg', 'likes/js');
	//File tool
	elgg_unextend_view("js/elgg", "file_tools/js/site");
	elgg_extend_view("js/elgg", "file_tools/js/site");
	//Scroll
	elgg_extend_view("js/elgg", "js/scroll");
	//elgg_extend_view('js/elgg', '/mod/gc_theme/js/lib/extra_feed_comments.js');
	//elgg_extend_view('js/elgg', '/mod/gc_theme/js/lib/extra_feed_replies.js');
	
	//Likes summary bar -- "You, John, and 3 others like this"
	//if (elgg_is_active_plugin('likes')) {
		//elgg_extend_view('river/elements/responses', 'likes/river_footer', 1);
	//}
	
	//elgg_extend_view('river/elements/responses', 'discussion/discussion_replies');
	
	//Elgg only includes the search bar in the header by default,
	//but we usually don't show the header when the user is logged in
	elgg_extend_view('page/elements/header','page/elements/topbar');
	if (elgg_is_active_plugin('search')) {
		//elgg_extend_view('page/elements/topbar', 'search/search_box');
		elgg_unextend_view('page/elements/header', 'search/search_box');
		
		//if(elgg_is_logged_in()==false)
		{
			elgg_unextend_view('page/elements/header', 'search/header');
		}
	}
}

function tag_management_pagesetup() {
	if (elgg_in_context("admin") && elgg_is_admin_logged_in()) {
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		elgg_register_admin_menu_item('configure', 'settings', 'gc_theme');
		elgg_register_admin_menu_item('configure', 'manage_tags', 'gc_theme');
	}
}

function gc_classifieds_cleanup() {
	$max_lifetime = elgg_get_plugin_setting('classified_expiry', 'gc_theme');
	if ($max_lifetime && $max_lifetime > 0) {
		$expired = time()-$max_lifetime*24*3600;
		$ads = elgg_get_entities(array( 'type' => 'object', 'subtype' => 'page', 'container_guid' => 34082, 'modified_time_upper'=>$expired, 'limit'=>0,));
	}
	foreach($ads as $ad) {
		if ($ad->delete()) {
			echo "Deleted Ad ".$ad->title.' '.elgg_view_friendly_time($ad->time_updated)."\r\n";
		} else {
			echo "Could not delete Ad ".$ad->title.' '.elgg_view_friendly_time($ad->time_updated)."\r\n";
		}
	}
}
function batch_classifieds_cleanup() {
        $period = 'daily';
        elgg_register_plugin_hook_handler('cron', $period, 'gc_classifieds_cleanup');
}
// Register a startup event
elgg_register_event_handler('init','system','batch_classifieds_cleanup');

// Initialization functions
elgg_register_event_handler('init', 'system', 'gc_theme_init');
elgg_register_event_handler('pagesetup', 'system', 'tag_management_pagesetup');
