<?php
/**
 * ADSync handler to sync data from Active Directory and TeamInfo
 * 
 * @param string $userid	The user name to sync or 
 * 								'all' to sync all users or
 * 								'changes' to sync only the records that have changed.
 * 
 * @return boolean A response as indicating if the sync was successful.
 */
function adsync_sync_handler($userid = null) {
	$result = false;
	
	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if (!isset($userid) || empty($userid)) {
		// Wrong invocation $userid must be a userid or 'all'.
		// Function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_target"));
	} else {
		if ('all' == $userid) {
			if (elgg_is_admin_logged_in()) {
				$result = adsync_sync_all_users(); 
			} else {
				// Only admin users can users can call this method to
				// sync data from Active Directory and TeamInfo.
				// Function will return false.
				$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_admin_only_function"));
				system_messages(elgg_echo("adsync:invalid_invocation_admin_only_function"), "error");
			}
		} elseif ('changes' == $userid) {
			if (elgg_is_admin_logged_in()) {
				$result = adsync_sync_changed_users(); 
			} else {
				// Only admin users can users can call this method to
				// sync data from Active Directory and TeamInfo.
				// Function will return false.
				$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_admin_only_function"));
				system_messages(elgg_echo("adsync:invalid_invocation_admin_only_function"), "error");
			}
		} else {
			$user = elgg_get_logged_in_user_entity();
			
			// Make sure that the owner of this page is the current user OR that this current user is an admin.
			if ( ($user->username == $userid) || (elgg_is_admin_logged_in())) {
				$result = adsync_sync_one_user($userid);
			} else {
				// Only admin users can users and the profile owner can call this method to
				// sync data from Directory and TeamInfo.
				// Function will return false.
				$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_permission_denied"));
				system_messages(elgg_echo("adsync:invalid_invocation_permission_denied"), "error");
			}
		}
	}
	
	return $result;
}


/**
 * ADSync handler to retrieve the TeamInfo avatar
 * 
 * @param string $userid	The user name to get the avatar for.
 * 
 * @return boolean A response as indicating if the pull was successful.
 */
function adsync_getavatar_handler($userid = null) {
	$result = false;
	
	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if (!isset($userid) || empty($userid)) {
		// Wrong invocation $userid must be a userid or 'all'.
		// Function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_target"));
	} else {
		$user = elgg_get_logged_in_user_entity();
		
		// Make sure that the owner of this page is the current user OR that this current user is an admin.
		if ( ($user->username == $userid) || (elgg_is_admin_logged_in())) {
			$result = adsync_getavatar_one_user($userid);
		} else {
			// Only admin users can users and the profile owner can call this method to
			// sync data from TeamInfo.
			// Function will return false.
			$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_permission_denied"));
			system_messages(elgg_echo("adsync:invalid_invocation_permission_denied"), "error");
		}
	}
	
	return $result;
}


/**
 * Function to sync the data from Active Directory and TeamInfo for *all* user.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_sync_all_users() {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	$mem_limit_org = ini_set('memory_limit', '1024M');
	$GLOBALS['ADSYNC_LOG']->debug("Increasing memory limit from [$mem_limit_org] to [1024M].");
	$max_exec_time_org = ini_set('max_execution_time', '0');
	$GLOBALS['ADSYNC_LOG']->debug("Setting maximun execution time from [$max_exec_time_org] to [0].");

	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Importing data for all users."));
	$users = adsync_get_active_user_names();

	$user_nb = count($users);
	$cnt = 1;
	foreach ($users as $user) {
		$GLOBALS['ADSYNC_LOG']->warn("Processing $cnt of $user_nb active users.");
		$result = adsync_sync_one_user($user->dfaitedsid);
		$cnt = $cnt +1;
	}
	
	// For good measure update all banned flags in Elgg to the value in AD.
	adsync_sync_banned();
   // Disable users without an email address.
   adsync_disable_users_without_email();
   
	ini_set('max_execution_time', $max_exec_time_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting maximun execution time to [$mem_limit_org].");
	ini_set('memory_limit', $mem_limit_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting memory limit to [$mem_limit_org].");

	$result = true;

	return $result;
}


/**
 * Function to sync the data from Active Directory and TeamInfo for *changed* user.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_sync_changed_users() {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	$mem_limit_org = ini_set('memory_limit', '1024M');
	$GLOBALS['ADSYNC_LOG']->debug("Increasing memory limit from [$mem_limit_org] to [1024M].");
	$max_exec_time_org = ini_set('max_execution_time', '0');
	$GLOBALS['ADSYNC_LOG']->debug("Setting maximun execution time from [$max_exec_time_org] to [0].");

	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Processing all banned or disabled accounts."));
	adsync_sync_banned();
		
	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Importing data for *changed* and *active* users."));
	$users = adsync_get_changed_active_user_names();

	$user_nb = count($users);
	$cnt = 1;
	$GLOBALS['ADSYNC_LOG']->warn("Number of active users to update: $user_nb.");
	foreach ($users as $user) {
		$GLOBALS['ADSYNC_LOG']->warn("Processing user [$user->dfaitedsid], $cnt of $user_nb active users.");
		$result = adsync_sync_one_user($user->dfaitedsid);
		$cnt = $cnt +1;
	}

	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Disable users without an email address."));
   adsync_disable_users_without_email();
	
	ini_set('max_execution_time', $max_exec_time_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting maximun execution time to [$mem_limit_org].");
	ini_set('memory_limit', $mem_limit_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting memory limit to [$mem_limit_org].");

	$result = true;

	return $result;
}


/**
 * 
 * @param unknown_type $ad_data
 * @return Ambigous <boolean, ElggUser, false, void, mixed, multitype:, multitype:unknown , object, unknown>
 */
function adsync_create_elgg_user($ad_data = null) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$username = $ad_data->samaccountname;
	$password = "useless";
	$name = $ad_data->displayname;
	$email = $ad_data->mail;
	
	$guid = register_user($username, $password, $name, $email, FALSE);

	if ($guid) {
		$elgg_user = get_user_by_username($username);
		
		if ( (isset($elgg_user)) && ($elgg_user instanceof ElggUser) ) {
			$result = $elgg_user;
		}
	}
	
	return $result;
}


/**
 * Queries the database for the list of all active AD/LDAP users.
 * 
 * @return array An array of database result objects. If the query
 *               returned nothing, an empty array.
 */
function adsync_get_active_user_names() {
	global $CONFIG;
	$usernames = array();

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$sql_query = "SELECT dfaitedsid FROM ad2elgg_users WHERE account_banned =  'no' AND mail <>  '' AND displayname <> '' ORDER BY samaccountname";
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$usernames = get_data($sql_query);
		
	return $usernames;
}


/**
 * Queries the database for the list of all changed and active AD/LDAP users.
 * 
 * @return array An array of database result objects. If the query
 *               returned nothing, an empty array.
 */
function adsync_get_changed_active_user_names() {
	global $CONFIG;
	$usernames = array();

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$sql_query = "SELECT dfaitedsid FROM ad2elgg_users WHERE account_banned =  'no' AND mail <>  '' AND displayname <>  '' AND ( elgg_guid IS NULL OR synced_at IS NULL OR updated_at > synced_at) AND hash_previous <> hash_current ORDER BY dfaitedsid"; 
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$usernames = get_data($sql_query);
		
	return $usernames;
}


/**
 * Queries the database for the list of teammates for the given userid.
 *
 * @return array An array of database result objects. If the query
 *               returned nothing, an empty array.
 */
function adsync_get_teammates($manager_dn) {
	global $CONFIG;
	$teammates = array();

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if (!isset($manager_dn) || empty($manager_dn)) {
		// Wrong invocation $manager_dn must be the dn of a user.
		// Function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:manager_missing"));
	} else {
		$sql_query = "SELECT id, elgg_guid, dfaitedsid, displayname FROM `ad2elgg_users` where manager = '$manager_dn' and account_banned = 'no' order by displayname asc;";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$teammates = get_data($sql_query);
		$GLOBALS['ADSYNC_LOG']->debug("Teammates count: " . count($teammates));
	}
	

	return $teammates;
}


/**
 * Queries the database for the list of organization members for the given
 * organization DN.
 *
 * @return array An array of database result objects. If the query
 *               returned nothing, an empty array.
 */
function adsync_get_org_members($org_dn) {
	global $CONFIG;
	$org_members = array();

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if (!isset($org_dn) || empty($org_dn)) {
		// Wrong invocation $manager_dn must be the dn of a user.
		// Function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:org_missing"));
	} else {
		$sql_query = "SELECT dfaitedsid, displayname FROM `ad2elgg_users` where dfaituserorganization = '$org_dn' and account_banned = 'no' order by displayname asc;";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$org_members = get_data($sql_query);
		$GLOBALS['ADSYNC_LOG']->debug("Org members count: " . count($org_members));
	}

	return $org_members;
}


/**
 * Queries the database for the list of userid in the hierarchy.
 *
 * @return array An array of database result objects. If the query
 *               returned nothing or if we reached the end of the 
 *               recursive lookup, returns an empty array.
 */
function adsync_get_hierarchy($user_dn, $nbToGet) {
	global $CONFIG;
	$hierarchy = null;

	$GLOBALS['ADSYNC_LOG']->error("=== STARTING: " . __METHOD__);
	
	if (!isset($user_dn) || empty($user_dn)) {
		// Wrong invocation $manager_dn must be the dn of a user.
		// Function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:manager_missing"));
		$hierarchy = Array();
	} else if (isset($nbToGet) && $nbToGet > 0) {
		$sql_query = "SELECT dfaitedsid, displayname, manager FROM `ad2elgg_users` WHERE dn = '$user_dn'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		
		if ($row) {
			$GLOBALS['ADSYNC_LOG']->debug("User found: " . $row->dfaitedsid);
			$hierarchy = adsync_get_hierarchy($row->manager, $nbToGet - 1);
			if (is_array($hierarchy)) {
				$hierarchy[$nbToGet] = $row;
			}
		} else {
			$hierarchy = Array();
		}
		
	} else if (isset($nbToGet) && 0 == $nbToGet) {
		$hierarchy = Array();
	}

	return $hierarchy;
}


/**
 * Function to sync the data from Active Directory and TeamInfo for *one* user.
 *
 * @param string $userid	The user ID to sync.
 *
 * @return boolean A response as indicating if the sync was successful.
 */
function adsync_sync_one_user($userid = null) {
	$result = false;

	try {
		$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
			
		if (adsync_lockrow($userid)) {
			$GLOBALS['ADSYNC_LOG']->warn("Sync'ing data for one user: $userid ");
			$ad_data = adsync_load_user_data($userid);
		
			if ($ad_data) {
				$elgg_data = adsync_transform_data_for_elgg($ad_data, $elgg_data);
			}
			if ($elgg_data) {
				$elgg_user = adsync_get_elgg_user($ad_data);
			}
			if (!$elgg_user) {
				// Do not create the account if corresponding xCIDA account exists
				$GLOBALS['ADSYNC_LOG']->error("User $ad_data->samaccountname does not exist, extensionattribute9=$ad_data->extensionattribute9");
				$xCIDA_elgg_user = get_user_by_username($ad_data->extensionattribute9);
				if (!$xCIDA_elgg_user) {
					$elgg_user = adsync_create_elgg_user($ad_data);
				} else {
					$GLOBALS['ADSYNC_LOG']->error("User $ad_data->samaccountname already has xCIDA account $ad_data->extensionattribute9");
				}
			}
			if (($elgg_user) && ($elgg_data)) {
				$elgg_user->name = $ad_data->displayname;
				$result = adsync_save_elgg_data($elgg_user, $elgg_data);
			}
			if ($result) {
				$result = adsync_save_misc_prop($elgg_user, $ad_data);
			}
			if ($result) {
				// If the Elgg user does not already have an avatar.
				$icontime = $elgg_user->icontime;
				if (!$icontime) {
					$result = adsync_get_avatar_from_teaminfo($elgg_user);
				} else {
					$GLOBALS['ADSYNC_LOG']->debug("Agora already has an avatar.");
				}
			}
			if ($result) {
				$result = adsync_update_synced_at($ad_data);
				adsync_unlockrow($userid);		 
			}
			if ($result) {
				// Notify of profile update
				elgg_trigger_event('profileupdate', $elgg_user->type, $elgg_user);
			}
		} else {
			$GLOBALS['ADSYNC_LOG']->warn("Skipping sync for user already being processed: $userid ");
		}
	}
	catch (Exception $e)
	{
		$GLOBALS['ADSYNC_LOG']->error('ERROR: '.$e->getMessage().' TRACE: '.$e->getTraceAsString());
	}
	
	return $result;
}

/**
 * Function to retrieve the TeamInfo avatar for *one* user.
 *
 * @param string $userid	The user ID to get avatar from.
 *
 * @return boolean A response as indicating if the pull was successful.
 */
function adsync_getavatar_one_user($userid = null) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
		
	$GLOBALS['ADSYNC_LOG']->warn("Retrieving avatar for one user: $userid ");

	$elgg_user = get_user_by_username($userid);
	
	if ($elgg_user) {
		// Checks to see if user already has an avatar, in which case delete it.
		$icontime = $elgg_user->icontime;
		if ($icontime) {
			unset($user->icontime);
		} 
			
		$result = adsync_get_avatar_from_teaminfo($elgg_user);
	}

	return $result;
}

/**
 * 
 * 
 * 
 * @param unknown_type $ad_data
 * @return Ambigous <boolean, mixed, multitype:, multitype:unknown , object, unknown>
 */
function adsync_get_elgg_user($ad_data = null) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if (!isset($ad_data)) {
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_wrong_data_type"));
	} else {
		$elgg_guid = $ad_data->elgg_guid;
		
		if ($elgg_guid) {
			$elgg_user = get_user_entity_as_row($elgg_guid);
		} 
		if (isset($elgg_user)) {
			$username = $elgg_user->username;
		} else {
			$username = $ad_data->samaccountname;
		}
		
		$elgg_user = get_user_by_username($username);
		
		if ( (isset($elgg_user)) && ($elgg_user instanceof ElggUser) ) {
			$result = $elgg_user;
		} 
	}	
	
	return $result;
}


/**
 * Queries the cached Active Directory database and loads all the coloumn values in an array.
 *
 * @param string	$userid_or_dn	The user name or DN to get from the database.
 *
 * @return array 	An array that will receive all the user data, passed by reference.
 */
function adsync_load_user_data($userid_or_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__, 'DEBUG');
	
	if (!isset($userid_or_dn) || empty($userid_or_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_userid"));
	} else {
		$sql_dn_or_id = sanitise_string($userid_or_dn);
		$sql_query = "SELECT * FROM ad2elgg_users where dfaitedsid = '{$sql_dn_or_id}' or dn = '{$sql_dn_or_id}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$rc = $row;
		}

	}
	
	return $rc;
}


/**
 * 
 * @param unknown_type $userid_or_dn
 * @return Ambigous <boolean, mixed, multitype:, multitype:unknown , object, unknown>
 */
function adsync_load_user_synced_date($userid_or_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if (!isset($userid_or_dn) || empty($userid_or_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_userid"));
	} else {
		$sql_dn_or_id = sanitise_string($userid_or_dn);
		$sql_query = "SELECT id, elgg_guid, dfaitedsid, created_at, updated_at, synced_at FROM ad2elgg_users where samaccountname = '{$sql_dn_or_id}' or dn = '{$sql_dn_or_id}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$rc = $row;
		}

	}
	
	return $rc;
}


/**
 * Queries the cached Active Directory database and loads all the coloumn values in an array.
 *
 * @param string	$building_dn	The user name or DN to get from the database.
 *
 * @return array 	An array that will receive all the user data, passed by reference.
 */
function adsync_load_building_data($building_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if (!isset($building_dn) || empty($building_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_building_dn"));
	} else {
		$sql_building_dn = sanitise_string($building_dn);
		$sql_query = "SELECT * FROM ad2elgg_buildings where dn = '{$sql_building_dn}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$rc = $row;
		}
	
	}
	
	return $rc;
}


/**
 * Queries the cached Active Directory database and loads all the coloumn values in an array.
 *
 * @param string	$org_dn		The user name or DN to get from the database.
 *
 * @return array 	An array that will receive all the user data, passed by reference.
 */
function adsync_load_organization_data($org_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if (!isset($org_dn) || empty($org_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_org_dn"));
	} else {
		$sql_org_dn = sanitise_string($org_dn);
		$sql_query = "SELECT * FROM ad2elgg_organizations where dn = '{$sql_org_dn}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$rc = $row;
		}
	
	}
	
	return $rc;
}


/**
 * Copies the values from the TeamInfo data array to the Elgg data array, using the proper
 * field names that Elgg will expect.
 *
 * @param array $teaminfo_data	Array of TeamInfo data.
 * @param array $elgg_data		Array of Elgg data, passed as by reference.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_transform_data_for_elgg($ad_data) {
	$elgg_data = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if (!isset($ad_data)) {
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_wrong_data_type"));
	} else {

		$profile_type_id        = elgg_get_plugin_setting('adsync_profile_type_id', 'dfait_adsync');
		$profile_default_value  = elgg_get_plugin_setting('adsync_profile_default_value', 'dfait_adsync');

		$elgg_data['custom_profile_type'] = $profile_type_id;

		// Work information
		$elgg_data['contactemail'] = adsync_get_array_value($ad_data, 'mail', $profile_default_value);
		$org_dn = adsync_get_array_value($ad_data, 'dfaituserorganization', $profile_default_value);
		if (!empty($org_dn)) {
			$elgg_data['org_dn'] = $org_dn;
			$org_data = adsync_load_organization_data($org_dn);
			$elgg_data['org_label_en'] = adsync_get_org_label_en($org_data, $profile_default_value); //string_to_tag_array(adsync_get_array_value($ad_data, 'OrganizationName', $profile_default_value));
			$elgg_data['org_label_fr'] = adsync_get_org_label_fr($org_data, $profile_default_value); //string_to_tag_array(adsync_get_array_value($ad_data, 'OrganizationName', $profile_default_value));
		}
		// Manager info
		$manager_dn = adsync_get_array_value($ad_data, 'manager', $profile_default_value);
		if (!empty($manager_dn)) {
			$manager_data = adsync_load_user_data($manager_dn);
			$elgg_data['manager_dn'] = $manager_dn;
			$elgg_data['manager_dfaitedsid'] = adsync_get_array_value($manager_data, 'dfaitedsid', $profile_default_value);
			$elgg_data['manager_name'] = adsync_get_array_value($manager_data, 'displayname', $profile_default_value);
		} 
		$elgg_data['employee_type'] = adsync_get_array_value($ad_data, 'employeetype', $profile_default_value);
		$GLOBALS['ADSYNC_LOG']->debug("AD employeetype: " . adsync_get_array_value($ad_data, 'employeetype', $profile_default_value));
		$GLOBALS['ADSYNC_LOG']->debug("Elgg employee_type: " . $elgg_data['employee_type']);
		// Phone book title
		$elgg_data['title_english'] = adsync_get_array_value($ad_data, 'gctitleenglish', $profile_default_value);
		$elgg_data['title_french'] = adsync_get_array_value($ad_data, 'gctitlefrench', $profile_default_value);
		// Phone numbers
		$elgg_data['phone_mitnet_business'] = adsync_get_array_value($ad_data, 'telephonenumber', $profile_default_value);
		$elgg_data['phone_external'] = adsync_get_array_value($ad_data, 'othertelephone', $profile_default_value);
		$elgg_data['phone_alternate'] = adsync_get_array_value($ad_data, 'gcalternatetelephonenumber', $profile_default_value);
		$elgg_data['phone_secure'] = adsync_get_array_value($ad_data, 'gcsecuretelephonenumber', $profile_default_value);
		$elgg_data['phone_mobile'] = adsync_get_array_value($ad_data, 'mobile', $profile_default_value);
		$elgg_data['phone_assistant'] = adsync_get_array_value($ad_data, 'telephoneassistant', $profile_default_value);
		$elgg_data['name_assistant'] = adsync_get_array_value($ad_data, 'msexchassistantname', $profile_default_value);
		$elgg_data['phone_pager'] = adsync_get_array_value($ad_data, 'pager', $profile_default_value);
		$elgg_data['phone_mitnet_fax'] = adsync_get_array_value($ad_data, 'facsimiletelephonenumber', $profile_default_value);
		$elgg_data['phone_secure_fax'] = adsync_get_array_value($ad_data, 'gcsecurefaxnumber', $profile_default_value);
		$elgg_data['phone_home_1'] = adsync_get_array_value($ad_data, 'homephone', $profile_default_value);
		$elgg_data['phone_home_2'] = adsync_get_array_value($ad_data, 'otherhomephone', $profile_default_value);
		$elgg_data['phone_private_mobile'] = adsync_get_array_value($ad_data, 'othermobile', $profile_default_value);
		// Contact preferences
		$elgg_data['salutation'] = adsync_get_salutation($ad_data, $profile_default_value);
		$elgg_data['pref_lang'] = adsync_get_pref_lang($ad_data, $profile_default_value);
		$elgg_data['note'] = adsync_get_note($ad_data, $profile_default_value);
		// Building information
		$building_dn = adsync_get_array_value($ad_data, 'dfaitassociatedbuilding', $profile_default_value);
		if (!empty($building_dn)) {
			$building_data = adsync_load_building_data($building_dn);
			$elgg_data['building_name'] = adsync_get_array_value($building_data, 'gcbuildingnameenglish', $profile_default_value);
			$elgg_data['building_address'] = adsync_get_building_adress($building_data, "");
		} 
		$elgg_data['room'] = adsync_get_array_value($ad_data, 'physicaldeliveryofficename', $profile_default_value);
		// Other information
		$elgg_data['languages'] = string_to_tag_array(adsync_get_array_value($ad_data, 'Languages', $profile_default_value));
		$elgg_data['current_areas'] = adsync_get_array_value($ad_data, 'AreaOfRespCurr', $profile_default_value);
		$elgg_data['former_areas'] = adsync_get_array_value($ad_data, 'AreaOfRespFormer', $profile_default_value);
		$elgg_data['interests'] = string_to_tag_array(adsync_get_array_value($ad_data, 'SpecialProjects', $profile_default_value));
		$elgg_data['website'] = adsync_get_array_value($ad_data, 'WebSite', $profile_default_value);
		// Admin only fields.
		$elgg_data['teaminfo_pic_hash'] = adsync_get_array_value($ad_data, 'HashCode', $profile_default_value, false);
		$elgg_data['user_dn'] = adsync_get_array_value($ad_data, 'dn', $profile_default_value, false);
		
	}

	return $elgg_data;
}


/**
 * Locates the specified field in the array and returns it's value, converting it to UTF8 unless told otherwise, if the value is
 * not found it will return either the default value specified or an empty string.
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$name			Name of the field to return.
 * @param string 	$default		Default value to return if value not found.
 * @param boolean 	$utf8_convert	Boolean indicating if the value should be converted to UTF8 before returning.
 *
 * @return string The value, optionally converted to UTF8, picked from the array, or if not found, returns the default.
 */
function adsync_get_array_value($array, $name, $default = "", $utf8_convert=true) {
	$result = $default;

// 	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__');
	
	$value = $array->$name;
	if ((isset($value)) && (!empty($value))) {
// 		if ($utf8_convert) {
// 			$value = utf8_encode($value);
// 		}
// 		$result = sanitise_string_special($value);
		$result = $value;
	}

//  	$GLOBALS['ADSYNC_LOG']->debug("Got value => $result"');
	
	return $result;
}


/**
 * 
 *  
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string The combined salutation fields, picked from the array, or if not found, returns the default.
 */
function adsync_get_org_label_en($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$org_name = adsync_get_array_value($array, 'dfaitOrganizationNameEnglish', $default);
	$org_code = adsync_get_array_value($array, 'name', $default);

	$result = "$org_name - $org_code";

	return $result;
}


/**
 *
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string The combined salutation fields, picked from the array, or if not found, returns the default.
 */
function adsync_get_org_label_fr($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$org_name = adsync_get_array_value($array, 'dfaitOrganizationNameFrench', $default);
	$org_code = adsync_get_array_value($array, 'name', $default);

	$result = "$org_name - $org_code";

	return $result;
}


/**
 * Locates the salutation fields (English and French) in the array concatenates them and returns the result. if the value is
 * not found it will return either the default value specified or an empty string.
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string The combined salutation fields, picked from the array, or if not found, returns the default.
 */
function adsync_get_salutation($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$sal_en = adsync_get_array_value($array, 'gcprefixenglish', $default, true);
	$sal_fre = adsync_get_array_value($array, 'gcprefixfrench', $default, true);


	$salutations = array($sal_en, $sal_fre);
	
	$result = implode_if_not_empty(" / ", $salutations);

	return $result;
}


/**
 * Locates the preferred language field in the array and returns the nice localized label matching the coded value, if the value is
 * not found it will return either the default value specified or an empty string.
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string The preferred language, picked from the array, or if not found, returns the default.
 */
function adsync_get_pref_lang($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$pref_lang_code = adsync_get_array_value($array, 'dfaitpreferredlanguage', $default);

	switch ($pref_lang_code) {
		case 'B':
			$result = elgg_echo("adsync:bilingual_en");
			break;

		case 'R':
			$result = elgg_echo("adsync:bilingual_fr");
			break;

		case 'E':
			$result = elgg_echo("adsync:english_only");
			break;

		case 'F':
			$result = elgg_echo("adsync:french_only");
			break;

		default:
			break;
	}

	return $result;
}


/**
 * For reasons beyond my understanding, the notes field may contain as a sort of header the External phone number.
 * Additionally when saving your profile in TeamInfo all line-feed and cariage-returns are stripped...
 * This function will find the last occurence of a line-feed and cariage-return and strip anything before that point.
 * The result is the note field. If the note field is not found it returns the provided default or a empty string.
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string The sanitized note fields, picked from the array, or if not found, returns the default.
 */
function adsync_get_note($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$value = adsync_get_array_value($array, 'info', $default, true);

	if ((isset($value)) && (!empty($value))) {
		$ndx = adsync_get_last_carriage_return_pos($value);
		if (false === $ndx) {
			$result = $value;
		} else {
			$result = substr($value, $ndx+2);
		}
	}

	return $result;
}


/**
 * 
 * @param unknown_type $value
 * @return number
 */
function adsync_get_last_carriage_return_pos ($value) {
	$result = NIL;
	
	$result = strrpos($value, "\n\r"); // Last index of '\n\r'
	if (!$result) {
		$result = strrpos($value, "\r\n"); // Last index of '\r\n'
	}
	if (!$result) {
		$result = strrpos($value, "\n"); // Last index of '\n'
	}
	if (!$result) {
		$result = strrpos($value, "\r"); // Last index of '\r'
	}
	
	return $result;
}


/**
 *
 * @param Array 	$array			Array to get the field value from.
 * @param string 	$default		Default value to return if value not found.
 *
 * @return string 	The building address computed from the array, or if not found, returns the default.
 */
function adsync_get_building_adress($array, $default = "") {
	$result = $default;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$street    = adsync_get_array_value($array, 'gcstreetaddressenglish', $default, true);
	$city       = adsync_get_array_value($array, 'gccityenglish', $default, true);
	$province   = adsync_get_array_value($array, 'gcprovincenameenglish', $default, true);
	$postalcode = adsync_get_array_value($array, 'postalcode', $default, true);
	$country    = adsync_get_array_value($array, 'gccountrynameenglish', $default, true);
	
	$address = array($street, $city, $province, $postalcode, $country);
	
	$result = implode_if_not_empty(", ", $address);
	
	return $result;
}


/**
 * Saves the values in the array into the provided Elgg User object as profile fields.
 *
 * @param ElggUser	$elgg_user	ElggUser object to save the new data into.
 * @param array		$elgg_data	Array of field to save into the Elgg user object.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_save_elgg_data ($elgg_user, $elgg_data) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if ( (!isset($elgg_user)) || !($elgg_user instanceof ElggUser) ) {
		// Wrong invocation $elgg_user must be an elgg user object.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_elgg_user"));
	} else if ((!isset($elgg_data)) || ("array" != gettype($elgg_data))) {
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_wrong_data_type"));
	} else if ( 0 == sizeof($elgg_data)){
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_array_is_empty"));
	} else {
		// SR [2012-08-29] foreach block copied from elgg code found in .../actions/profile/edit.php
		foreach ($elgg_data as $shortname => $value) {
			$options = array(
					'guid' => $elgg_user->guid,
					'metadata_name' => $shortname
			);
			elgg_delete_metadata($options);
			// This block gets the access level from the HTTP POST for the form, set by the user for each
			// field. In our case this function is not called from an HTTP POST to save a profile, so there
			// will not be a $accesslevel array to match the $input array (here called $elgg_data), also
			// each of the fields pulled from TeamInfo should be visible, we therefore hardcode the
			// access_id to the public value.
			// 		if (isset($accesslevel[$shortname])) {
			// 			$access_id = (int) $accesslevel[$shortname];
			// 		} else {
			// 			// this should never be executed since the access level should always be set
			// 			$access_id = ACCESS_DEFAULT;
			// 		}
			$access_id = ACCESS_PUBLIC;
			if (is_array($value)) {
				$i = 0;
				foreach ($value as $interval) {
					$i++;
					$multiple = ($i > 1) ? TRUE : FALSE;
					create_metadata($elgg_user->guid, $shortname, $interval, 'text', $elgg_user->guid, $access_id, $multiple);
				}
			} else {
				create_metadata($elgg_user->getGUID(), $shortname, $value, 'text', $elgg_user->getGUID(), $access_id);
			}
		}

		$elgg_user->save();

		$result = true;
	}

	return $result;
}


/**
 * Saves some values directly into the user entity table.
 *
 * @param ElggUser	$elgg_user	ElggUser object to save the new data into.
 * @param array		$ad_data	Array of field to save into the Elgg user object.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_save_misc_prop ($elgg_user, $ad_data) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	if ( (!isset($elgg_user)) || !($elgg_user instanceof ElggUser) ) {
		// Wrong invocation $elgg_user must be an elgg user object.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_elgg_user"), 'ERROR');
	} else if (!isset($ad_data)) {
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_wrong_data_type"), 'ERROR');
	} else if ( 0 == sizeof($ad_data)){
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_array_is_empty"), 'ERROR');
	} else {
		
		$db_prefix = get_config('dbprefix');
		$sql_id         = sanitise_string($ad_data->id);
		$sql_name       = sanitise_string($ad_data->displayname);
		$sql_dfaitedsid = sanitise_string($ad_data->dfaitedsid);
		$sql_email      = sanitise_string($ad_data->mail);
		$sql_banned     = sanitise_string($ad_data->account_banned);
		$sql_guid       = sanitise_string($elgg_user->guid); 
		$sql_query = "UPDATE {$db_prefix}users_entity SET  name  = '{$sql_name}', email = '{$sql_email}', banned = '{$sql_banned}' WHERE guid = {$sql_guid};";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		
		$result = update_data($sql_query);
		
		if ($result) {
			$sql_query = "UPDATE ad2elgg_users SET  elgg_guid = {$sql_guid} WHERE id = {$sql_id} AND dfaitedsid = '{$sql_dfaitedsid}';";
			$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
			
			$result = update_data($sql_query);
		}
	}

	return $result;
}


/**
 * Disable users in ELGG based on the value in AD,
 * also mangles the email address of disabled users to help prevent error on duplication.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_sync_banned () {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$db_prefix = get_config('dbprefix');
	
	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Updating banned or disabled accounts."));
	$sql_query = "UPDATE LOW_PRIORITY ad2elgg_users AS ad, {$db_prefix}users_entity AS eu SET eu.banned = ad.account_banned WHERE ad.dfaitedsid = eu.username;";
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$result = update_data($sql_query);

	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Mangle email address of banned or disabled accounts in ad2elgg_users."));
	$sql_query = "UPDATE LOW_PRIORITY ad2elgg_users SET mail = CONCAT('ELGG_DISABLED+', mail) WHERE account_banned = 'yes' AND mail <> '' AND mail IS NOT NULL AND mail NOT LIKE 'ELGG_DISABLED+%';";
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$result = update_data($sql_query);
	
	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Mangle email address of banned or disabled accounts in elgg_users_entity."));
	$sql_query = "UPDATE LOW_PRIORITY {$db_prefix}users_entity SET email = CONCAT('ELGG_DISABLED+', email) WHERE banned = 'yes' AND email <> '' AND email IS NOT NULL AND email NOT LIKE 'ELGG_DISABLED+%';";
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$result = update_data($sql_query);
	
	return $result;
}


/**
 * Disable all users that do not have an email address.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_disable_users_without_email () {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);
	
	$db_prefix = get_config('dbprefix');
	
	$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Banning ELGG users that do not have an email address."));
	$sql_query = "UPDATE LOW_PRIORITY {$db_prefix}users_entity SET banned = 'yes' WHERE email = '' OR email IS NULL;";
	$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
	$result = update_data($sql_query);
	
	return $result;
}


/**
 * Downloads the Avatar from TeamInfo and saves it in Elgg for the given user.
 *
 * @param ElggUser $elgg_user	ElggUser object to get the avatar from TeamInfo for.
 *
 * @return boolean A response as indicating if the import was successful.
 */
function adsync_get_avatar_from_teaminfo($elgg_user) {
	global $CONFIG;
	$result = false;
	
	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if ( (!isset($elgg_user)) || !($elgg_user instanceof ElggUser) ) {
		// Wrong invocation $elgg_user must be an elgg user object.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_elgg_user"));
	} else {
		$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Getting avatar for user => " . $elgg_user->username));

		$tmpfname = tempnam("/tmp", "ti2dc-");

		$teaminfo_url        = elgg_get_plugin_setting('adsync_teaminfo_url', 'dfait_adsync');
		$teaminfo_domain     = elgg_get_plugin_setting('adsync_teaminfo_domain', 'dfait_adsync');
		$teaminfo_username   = elgg_get_plugin_setting('adsync_teaminfo_username', 'dfait_adsync');

		$now = new DateTime();
		$avatar_url = $teaminfo_url . "/Handler/ReadFile.ashx?dfaitEdsId=" . $elgg_user->username . "&d=" . $now->getTimeStamp();
		$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Avatar URL => " . $avatar_url));
		
		try {
			$key = $CONFIG->dbpass;
			$passwd = elgg_get_plugin_setting('adsync_teaminfo_password', 'dfait_adsync');
			$teaminfo_password = mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($passwd), MCRYPT_MODE_ECB);
			$block = mcrypt_get_block_size('tripledes', 'ecb');
			$pad = ord($teaminfo_password[($len = strlen($teaminfo_password)) - 1]);
			$teaminfo_password = substr($teaminfo_password, 0, strlen($teaminfo_password) - $pad);
	
			// Download file from TeamInfo using curl
			$ch = curl_init($avatar_url);
			$fp = fopen($tmpfname, 'wb');
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
			curl_setopt($ch, CURLOPT_USERPWD, $teaminfo_domain . "/" . $teaminfo_username . ":" . $teaminfo_password);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Avatar saved to => " . $tmpfname));
			
			// Get mime-type
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $tmpfname);
			finfo_close($finfo);
			$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Mime type => " . $mime_type));
			// Get file size
			$filesize = filesize($tmpfname);
	 		$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("File size => " . $filesize));
	
			if ("text/html" <> $mime_type)
			{
		 		// Build fake $_FILE entry
				$avatar_files = array();
				$avatar_files['name'] = $elgg_user->username . ".img";
				$avatar_files['type'] = $mime_type;
				$avatar_files['tmp_name'] = $tmpfname;
				$avatar_files['error'] = 0;
				$avatar_files['size'] = $filesize;
				$_FILES['avatar'] = $avatar_files;
		
				// SR [2012-08-31] block inspired from .../action/avatar/upload.php
				////////
				$guid = $elgg_user->guid;
				$owner = $elgg_user;
		
				$icon_sizes = elgg_get_config('icon_sizes');
		
				// get the images and save their file handlers into an array
				// so we can do clean up if one fails.
				$files = array();
				foreach ($icon_sizes as $name => $size_info) {
					$resized = get_resized_image_from_uploaded_file('avatar', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);
		
					if ($resized) {
						//@todo Make these actual entities.  See exts #348.
						$file = new ElggFile();
						$file->owner_guid = $guid;
						$file->setFilename("profile/{$guid}{$name}.jpg");
						$file->open('write');
						$file->write($resized);
						$file->close();
						$files[] = $file;
					} else {
						// cleanup on fail
						foreach ($files as $file) {
							$file->delete();
						}
						register_error(elgg_echo('avatar:resize:fail'));
						return false;
					}
				}
		
				// reset crop coordinates
				$owner->x1 = 0;
				$owner->x2 = 0;
				$owner->y1 = 0;
				$owner->y2 = 0;
		
				$owner->icontime = time();
				if (elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
		// 			system_message(elgg_echo("avatar:upload:success"));
		
					$view = 'river/user/default/profileiconupdate';
					elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
		// 			add_to_river($view, 'update', $owner->guid, $owner->guid);
				}
				////////
			}
				
			unlink($tmpfname);
		}
		catch (Exception $ex) {
			$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Caught Exception => " . $ex->getMessage()));
			$GLOBALS['ADSYNC_LOG']->debug(elgg_echo("Deleting file => " . $tmpfname));
			unlink($tmpfname);
		}
		// Made it this far, return true.
		$result = true;
	}


	return $result;
}


/**
 *
 */
function implode_if_not_empty($glue = "", $pieces) {
	$result = FALSE;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	$new_pieces = array();
	
	foreach ($pieces as $elem) {
		if (!empty($elem)) {
			array_push($new_pieces, $elem);
		}
	}

	$result = implode($glue, $new_pieces);

	return $result;
}


/**
 * 
 * @param unknown_type $elgg_user
 * @return boolean
 */
function adsync_refresh_profile($username) {
	$result = FALSE;

	$GLOBALS['ADSYNC_LOG']->debug("STARTING: " . __METHOD__);
	
	if (isset($username) && !empty($username) ) {
		$user_data = adsync_load_user_synced_date($username);
		
		if ($user_data) {
			$updated_at = $user_data->updated_at;
			$synced_at  = $user_data->synced_at;
			$elgg_guid  = $user_data->elgg_guid; 
			
			if (empty($synced_at) || empty($elgg_guid)) {
				$GLOBALS['ADSYNC_LOG']->debug("New user");
				adsync_sync_one_user($username);
			} else {
				$GLOBALS['ADSYNC_LOG']->debug("{$username} updated at: {$updated_at}, and synced at: {$synced_at}");
				$updated_at_date = new DateTime($updated_at, new DateTimeZone("UTC"));
				$synced_at_date  = new DateTime($synced_at, new DateTimeZone("UTC"));
				
				if ($updated_at_date > $synced_at_date) {
					$GLOBALS['ADSYNC_LOG']->debug("AD info has been updated since the last time, refreshing Elgg user");
					adsync_sync_one_user($username);
				} else {
					$now = new DateTime("now", new DateTimeZone("UTC"));
					$interval = $now->diff($synced_at_date);
					$GLOBALS['ADSYNC_LOG']->debug("Diff hours since last update: {$interval->h}");
					if ($interval->h >= 1) {
						$GLOBALS['ADSYNC_LOG']->debug("Resfreshing user profile");
						adsync_sync_one_user($username);
					} else {
						$GLOBALS['ADSYNC_LOG']->debug("Skipping resfresh for user profile");
					}
				}		
			}
					
		}
			
		$result = TRUE;
	}

	return $result;
}


/**
 * 
 * @param unknown_type $ad_data
 * @return boolean
 */
function adsync_update_synced_at ($ad_data) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	if (!isset($ad_data)) {
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_wrong_data_type"), 'ERROR');
	} else if ( 0 == sizeof($ad_data)){
		// Wrong invocation $teaminfo_data must be an array, containing the user's record.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_array_is_empty"), 'ERROR');
	} else {

// 		$db_prefix = get_config('dbprefix');
		$sql_id         = sanitise_string($ad_data->id);
		$sql_dfaitedsid = sanitise_string($ad_data->dfaitedsid);
		$synced_at = gmdate("Y-m-d H:i:s");

		$sql_query = "UPDATE ad2elgg_users SET  synced_at = '{$synced_at}', hash_previous = hash_current WHERE id = {$sql_id} AND dfaitedsid = '{$sql_dfaitedsid}';";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
			
		$result = update_data($sql_query);
	}

	return $result;
}


/**
 * 
 *
 * @param string	$userid_or_dn	The user name or DN to get from the database.
 *
 * @return array 	An array that will receive all the user data, passed by reference.
 */
function adsync_lockrow($userid_or_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__, 'DEBUG');

	if (!isset($userid_or_dn) || empty($userid_or_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_userid"));
	} else {
		$sql_dn_or_id = sanitise_string($userid_or_dn);
		$sql_query = "SELECT id, ProcBy FROM ad2elgg_users where dfaitedsid = '{$sql_dn_or_id}' or dn = '{$sql_dn_or_id}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$sql_id = $row->id;
			$ProcBy = $row->ProcBy;
			$GLOBALS['ADSYNC_LOG']->debug("ID: [$sql_id], ProcBy: [$ProcBy].");
			if (!isset($ProcBy) || empty($ProcBy) || $ProcBy == $GLOBALS['ADSYNC_PID']) {
				$sql_query = "UPDATE ad2elgg_users SET  ProcBy = {$GLOBALS['ADSYNC_PID']} WHERE id = {$sql_id};";
				$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
				$result = update_data($sql_query);
				$rc = true;
			}
		}

	}

	return $rc;
}



/**
 *
 *
 * @param string	$userid_or_dn	The user name or DN to get from the database.
 *
 * @return array 	An array that will receive all the user data, passed by reference.
 */
function adsync_unlockrow($userid_or_dn = null) {
	global $CONFIG;
	$rc = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__, 'DEBUG');

	if (!isset($userid_or_dn) || empty($userid_or_dn)) {
		// Wrong invocation $import_target must be a userid or 'all'.
		// function will return false.
		$GLOBALS['ADSYNC_LOG']->error(elgg_echo("adsync:invalid_invocation_missing_userid"));
	} else {
		$sql_dn_or_id = sanitise_string($userid_or_dn);
		$sql_query = "SELECT id, ProcBy FROM ad2elgg_users where dfaitedsid = '{$sql_dn_or_id}' or dn = '{$sql_dn_or_id}'";
		$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
		$row = get_data_row($sql_query);
		if ($row) {
			$sql_id = $row->id;
			$ProcBy = $row->ProcBy;
			$GLOBALS['ADSYNC_LOG']->debug("ID: [$sql_id], ProcBy: [$ProcBy].");
			if ($ProcBy == $GLOBALS['ADSYNC_PID']) {
				$sql_query = "UPDATE ad2elgg_users SET  ProcBy = NULL WHERE id = {$sql_id};";
				$GLOBALS['ADSYNC_LOG']->debug("SQL: $sql_query");
				$result = update_data($sql_query);
				$rc = true;
			}
		}

	}

	return $rc;
}
