<?php
/**
 * DFAIT ADSync
 *
 *
 * @author Sebastien Routier
 * @license http://opensource.org/licenses/GPL-3.0 (GPL-3.0)
 */


$GLOBALS['ADSYNC_PID'] = getmypid();
// $GLOBALS['ADSYNC_LOG'] = new FlexLog(FlexLogLevel::DEBUG);
$GLOBALS['ADSYNC_LOG'] = new FlexLog(FlexLogLevel::ERROR);
$GLOBALS['ADSYNC_LOG']->debug("ADSYNC: FlexLog initialized." . " PID: " . $GLOBALS['ADSYNC_PID']);

elgg_register_event_handler('init', 'system', 'adsync_init');

/**
 * Plugin initialization
 */
function adsync_init() {
	global $CONFIG;
	
	elgg_register_page_handler('dfait_adsync', 'adsync_handler');
	elgg_register_plugin_hook_handler('route', 'profile', 'adsync_profile_handler', 1);

	// Override save method
	elgg_register_action('dfait_adsync/settings/save',$CONFIG->pluginspath . 'dfait_adsync/actions/plugins/settings/save.php');
	
}

/**
 * Routes requests to an actions script
 *
 * @param array $page An array of URL segments
 * @param string $identifier The identifier for this plugin
 * @return bool
 */
function adsync_handler($page, $identifier) {
	$GLOBALS['ADSYNC_LOG']->debug("== STARTING: " . __METHOD__);
	
	// file path to the page scripts
	$lib_path = elgg_get_plugins_path() . 'dfait_adsync/lib';
	
	// get plugin command
	if (!isset($page[0]) || empty($page[0])) {
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_command"));
		system_messages(elgg_echo("adsync:invalid_invocation_missing_command"), "error");
	} else {
		$cmd = $page[0];
	}
	// get import target command
	if (!isset($page[1]) || empty($page[1])) {
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_target"));
		system_messages(elgg_echo("adsync:invalid_invocation_missing_target"), "error");
	} else {
		$target = $page[1];
	}
	
	if ((!empty($cmd)) && !empty($target)) {
		$start_date = new DateTime();
	
		// select page based on first URL segment after /adsync/
		switch ($cmd) {
			case 'sync':
				require("$lib_path/functions.php");
				$result = adsync_sync_handler($target);
				if (true == $result) {
					system_messages(elgg_echo("adsync:pull_completed"), "success");
				} else {
					$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:pull_failed"));
					system_messages(elgg_echo("adsync:pull_failed"), "error");
				}
				break;
			case 'getavatar':
				require("$lib_path/functions.php");
				$result = adsync_getavatar_handler($target);
				if (true == $result) {
					system_messages(elgg_echo("adsync:getavatar_completed"), "success");
				} else {
					$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:getavatar_failed"));
					system_messages(elgg_echo("adsync:getavatar_failed"), "error");
				}
				break;
			default:
				$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_command", array($cmd)));
				system_messages(elgg_echo("adsync:invalid_command", array($cmd)), "error");
				break;
		}
		
		$end_date = new DateTime();
		$time_ellapsed = $start_date->diff($end_date);
		$days = $time_ellapsed->d;
		$hours = $time_ellapsed->h;
		$minutes = $time_ellapsed->i;
		$seconds = $time_ellapsed->s;
		
 		$status_msg = "Task completed in: {$days} days, {$hours} hours, {$minutes} minutes, {$seconds} seconds, .";
		$GLOBALS['ADSYNC_LOG']->debug($status_msg);
	}

	forward(REFERER);
		
	// return true to let Elgg know that a page was sent to browser
	return true;
}



/**
 * Routes requests to an actions script
 *
 * @param array $page An array of URL segments
 * @param string $identifier The identifier for this plugin
 * @return bool
 */
function adsync_profile_handler($hook, $type, $value, $params) {
	$GLOBALS['ADSYNC_LOG']->debug("== STARTING: " . __METHOD__);
	$user = elgg_get_logged_in_user_entity();
	$username = $user->username;
	$target_username = $value[segments][0];
	if ($username == $target_username || elgg_is_admin_logged_in()) {
		// file path to the page scripts
		$lib_path = elgg_get_plugins_path() . 'dfait_adsync/lib';
		require("$lib_path/functions.php");
		$result = adsync_refresh_profile($target_username);
	} else {
		$GLOBALS['ADSYNC_LOG']->debug("Logged in user not allowed to update profile, skipping...");
	}
	// return true to let Elgg know that a page was sent to browser
	return $value;
}
