<?php
/**
 * Return default results for searches on tags.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function gc_register($hook, $type, $value,$params) {
	$db_prefix = elgg_get_config('dbprefix');
	elgg_log("BRUNO gc_register:params ".var_export($params,true),'NOTICE');
	elgg_log("BRUNO gc_register:params guid ".$params['user']->guid,'NOTICE');
	// Use raw method to update user to GCuser because user entity not validated so not yet available
	$user=$params['user'];
	$metaname = 'collections_notifications_preferences_email';
	$user->$metaname = '-1';
	return true;
}
