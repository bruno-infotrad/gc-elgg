<?php
/**
 * Site Stats
 *
 *
 * @author Sebastien Routier
 * @license http://opensource.org/licenses/GPL-3.0 (GPL-3.0)
 */

elgg_register_event_handler('init', 'system', 'site_stats_init');

// elgg_register_event_handler('all', 'all', 'site_stats_log_events', 1);
// elgg_register_plugin_hook_handler('all', 'all', 'site_stats_log_plugin_hooks', 1);

/**
 * Plugin initialization
 */
function site_stats_init() {
	global $CONFIG;
	
	// Set up menu
// 	$item = new ElggMenuItem('stats', elgg_echo('site_stats:stats'), 'stats');
// 	elgg_register_menu_item('site', $item);
	
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('page', array(
								'name' => 'site_stats',
								'text' => elgg_echo('site_stats:stats'),
								'href' => '/site_stats',
								'priority' => 110,
// 								'contexts' => array('dashboard'),
		));
	
	
		elgg_register_page_handler('site_stats', 'site_stats_handler');
	}
	
}

/**
 * Routes requests to an actions script
 *
 * @param array $page An array of URL segments
 * @param string $identifier The identifier for this plugin
 * @return bool
 */
function site_stats_handler($page, $identifier) {

	srlog("== STARTING: " . __METHOD__, 'DEBUG');
	
	// file path to the page scripts
// 	$lib_path = elgg_get_plugins_path() . 'site_stats/lib';
	$pages_path = elgg_get_plugins_path() . 'site_stats/pages/site_stats';
	
	require "$pages_path/stats.php";

		
	// return true to let Elgg know that a page was sent to browser
	return true;
}

/**
 * Log the events
 */
function site_stats_log_events($event, $object_type, $object) {

// 	// filter out some very common events
// 	if ($event == 'view' || $event == 'display' || $event == 'log' || $event == 'debug') {
// 		return;
// 	}
// 	if ($event == 'session:get' || $event == 'validate') {
// 		return;
// 	}

	// filter out some very common events
	if ($event == 'log' || $event == 'debug') {
		return;
	}
	
	$stack = debug_backtrace();
	if ($stack[2]['function'] == 'elgg_trigger_event') {
		$event_type = 'Event';
	} else {
		$event_type = 'Plugin hook';
	}
	$function = $stack[3]['function'] . '()';
	if ($function == 'require_once' || $function == 'include_once') {
		$function = $stack[3]['file'];
	}

	$msg = elgg_echo('site_stats:event_log_msg', array(
			$event_type,
			$event,
			$object_type,
			$function,
	));
	srlog($msg, false, 'WARNING');

	unset($stack);
}

/**
 * Log the plugin hooks
 */
function site_stats_log_plugin_hooks($hook, $type, $returnvalue, $params) {

	// 	// filter out some very common events
	// 	if ($hook == 'view' || $hook == 'display' || $hook == 'log' || $hook == 'debug') {
	// 		return;
	// 	}
	// 	if ($hook == 'session:get' || $hook == 'validate') {
	// 		return;
	// 	}

	// filter out some very common events
	if ($hook == 'log' || $hook == 'debug') {
		return;
	}
	
	$stack = debug_backtrace();
	if ($stack[2]['function'] == 'elgg_trigger_event') {
		$event_type = 'Event';
	} else {
		$event_type = 'Plugin hook';
	}
	$function = $stack[3]['function'] . '()';
	if ($function == 'require_once' || $function == 'include_once') {
		$function = $stack[3]['file'];
	}

	$msg = elgg_echo('site_stats:hook_log_msg', array(
			$event_type,
			$hook,
			$type,
			$function,
	));
	srlog($msg, false, 'WARNING');

	unset($stack);
}

if (!function_exists("srlog")) {
  function srlog($msg, $level) {
    $rc = elgg_dump("-=[SR]=- ".$msg, false, $level);
    return $rc;
  }
}
