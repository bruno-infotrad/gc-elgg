<?php
/**
 * Open external links in a new window.
 */

/**
 * Inits the plugin
 * 
 * @return void
 */
function target_blank_init() {
	// extend js
	elgg_extend_view("js/elgg", "js/target_blank");
	
	// plugin hooks
	elgg_register_plugin_hook_handler("action", "plugins/settings/save", "target_blank_plugins_settings_save_action_hook");

	// action override
	elgg_register_action("target_blank/settings/save", dirname(__FILE__) . "/actions/settings/save.php", "admin");
}

/**
 * Listen to the saving of plugin settings, if the plugin is this plugin invalidate simplecache
 *
 * @param string $hook 'action'
 * @param string $type 'plugins/settings/save'
 * @param bool $returnvalue false to stop the action
 * @param null $params null
 *
 * @return void
 */
function target_blank_plugins_settings_save_action_hook($hook, $type, $returnvalue, $params) {

	$plugin_id = get_input("plugin_id");
	if ($plugin_id === "target_blank") {
		elgg_invalidate_simplecache();
	}
}

elgg_register_event_handler('init', 'system', 'target_blank_init');