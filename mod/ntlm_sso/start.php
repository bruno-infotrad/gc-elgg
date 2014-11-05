<?php
/**
 * NTLM Authentication
 * Also need LDAP to be able to automatically create accounts for authenticated
 * users who do not exist in Elgg
 * These parameters are required for the event API, but we won't use them:
	 * 
 * @param unknown_type $event
 * @param unknown_type $object_type
 * @param unknown_type $object 
*/
function ntlm_auth_init()
{
	global $CONFIG;
	$credentials['username'] = 'dummy';
	$credentials['password'] = 'user';
	// Override save method
	elgg_register_action('ntlm_sso/settings/save',$CONFIG->pluginspath . 'ntlm_sso/actions/plugins/settings/save.php');
	// Unregister username/password handler and register the Kerberos authentication handler
        unregister_pam_handler('pam_auth_userpass');
	register_pam_handler('ntlm_auth_authenticate');
	register_translations($CONFIG->pluginspath . "ntlm_sso/languages/");
	if (!elgg_is_logged_in() && $_SERVER['PHP_AUTH_USER'])
	{
		$username = $_SERVER['PHP_AUTH_USER'];
		$username = preg_replace('/@.+/','',$username);
	        $result=elgg_authenticate($username,'dummy');
		if ($result !== true) {
			register_error($result);
		}
		else
		{
			//forward(REFERER);
		}
	}
}
/**
* NTLM authentication
* 
* @param mixed $credentials PAM handler specific credentials
* @return boolean
*/
function ntlm_auth_authenticate($credentials = null)
{
	// Nothing to do if LDAP module not installed
	$user_create = elgg_get_plugin_setting('user_create', 'ntlm_sso');
	// Get configuration settings
	$config = elgg_get_plugin_from_id('ntlm_sso');
	        
	// Nothing to do if not configured
	if (!$config)
	{
		return false;
	}
	$username      = null;
	// Check SERVER variables to check if user was authenticated (nothing to do otherwise)
	//if ($_SERVER['PHP_AUTH_USER'] && ($_SERVER['AUTH_TYPE'] == 'NTLM'))
	if ($_SERVER['PHP_AUTH_USER'])
	{
		$username = $_SERVER['PHP_AUTH_USER'];
		$username = preg_replace('/@.+/','',$username);
	} else {

		return false;
	}
	
	// Perform the authentication
	return ldap_auth_check($config, $username,$user_create);
}
    
/**
* Perform an LDAP authentication check
*
* @param ElggPlugin $config
* @param string $username
* @param string $password
* @return boolean
*/
function ldap_auth_check($config, $username,$user_create)
{
	if ($user = get_user_by_username($username))
	{
		$sql_query0 = "SELECT dfaitedsid FROM `ad2elgg_users` where extensionattribute9='".$username."' and elgg_guid is not null";
        	$signet_elgg_username = get_data($sql_query0);
		$sql_query1 = "SELECT extensionattribute9 FROM `ad2elgg_users` where dfaitedsid=lower('".$username."') and elgg_guid is not null";
        	$cida_elgg_username = get_data($sql_query1);
		// Now check if 1) User never logged in or 2) account is not associated with anything.
		// If one of these conditions is met check if a corresponding account exists. If it does, log user in as this account
                $nomoobu = gc_theme_get_user_objects($user);
		if ($user->last_login == 0 || $nomoobu == 0) {
			elgg_log("BRUNO NTLM_AUTH last_login=".$user->last_login." num of objects=".$nomoobu." username=$username signet_elgg_username ".var_export($signet_elgg_username,true)." cida_elgg_username ".var_export($cida_elgg_username,true),'NOTICE');
			if (count($signet_elgg_username) == 1) {
				$linked_username = $signet_elgg_username[0]->dfaitedsid;
				elgg_log("BRUNO NTLM_AUTH signet linked_username=$linked_username ",'NOTICE');
				if ($linked_user = get_user_by_username($linked_username))
				{
					return login($linked_user);
				} else {
					return login($user);
				}
			} elseif (count($cida_elgg_username) == 1) {
				$linked_username = $cida_elgg_username[0]->extensionattribute9;
				elgg_log("BRUNO NTLM_AUTH cida linked_username=$linked_username ",'NOTICE');
				if ($linked_user = get_user_by_username($linked_username))
				{
					return login($linked_user);
				} else {
					return login($user);
				}
			} else {
				return login($user);
			}
		} else {
			elgg_log("BRUNO NTLM_AUTH last_login=".$user->last_login." num of objects=".$nomoobu." username=$username signet_elgg_username ".var_export($signet_elgg_username,true)." cida_elgg_username ".var_export($cida_elgg_username,true),'NOTICE');
			if (count($signet_elgg_username) == 1) {
				$linked_username = $signet_elgg_username[0]->dfaitedsid;
				elgg_log("BRUNO NTLM_AUTH signet linked_username=$linked_username ",'NOTICE');
				if ($linked_user = get_user_by_username($linked_username))
				{
					if(gc_theme_get_user_objects($linked_user)) {
						register_error(elgg_echo('gc_theme:duplicate_account'));
					} 
				} 
			} elseif (count($cida_elgg_username) == 1) {
				$linked_username = $cida_elgg_username[0]->extensionattribute9;
				elgg_log("BRUNO NTLM_AUTH cida linked_username=$linked_username ",'NOTICE');
				if ($linked_user = get_user_by_username($linked_username))
				{
					if(gc_theme_get_user_objects($linked_user)) {
						register_error(elgg_echo('gc_theme:duplicate_account'));
					} 
				} 
			}
			elgg_log("BRUNO NTLM_AUTH username=$username",'NOTICE');
			return login($user);
		}
	}
	else
	{
		// user loging in with username, account does not exist in Agora but association exists in add2elgg_users
		// no duplicates can exist
		$sql_query0 = "SELECT dfaitedsid FROM `ad2elgg_users` where extensionattribute9='".$username."' and elgg_guid is not null";
        	$signet_elgg_username = get_data($sql_query0);
		$sql_query1 = "SELECT extensionattribute9 FROM `ad2elgg_users` where dfaitedsid=lower('".$username."') and elgg_guid is not null";
        	$cida_elgg_username = get_data($sql_query1);
		elgg_log("BRUNO NTLM_AUTH username=$username signet_elgg_username ".var_export($signet_elgg_username,true)." cida_elgg_username ".var_export($cida_elgg_username,true),'NOTICE');
		if (count($signet_elgg_username) == 1) {
			$linked_username = $signet_elgg_username[0]->dfaitedsid;
			elgg_log("BRUNO NTLM_AUTH signet linked_username=$linked_username ",'NOTICE');
			if ($linked_user = get_user_by_username($linked_username))
			{
				return login($linked_user);
			} else {
				return false;
			}
		} elseif (count($cida_elgg_username) == 1) {
			$linked_username = $cida_elgg_username[0]->extensionattribute9;
			elgg_log("BRUNO NTLM_AUTH cida linked_username=$linked_username ",'NOTICE');
			if ($linked_user = get_user_by_username($linked_username))
			{
				return login($linked_user);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
function gc_theme_get_user_objects($user) {
	$ia=elgg_set_ignore_access();
	$number_of_entities_owned_by_user = elgg_get_entities(array(
						'types' => 'object',
						'subtypes' => array('blog','bookmarks','folder','groupforumtopic','page','page_top','poll','poll_choice','thewire'),
						'owner_guids' => $user->getGUID(),
						'count' => TRUE,
						));
	$number_of_annotations_owned_by_user = elgg_get_annotations(array(
						'annotation_owner_guid' => $user->getGUID(),
						'count' => TRUE,
						));
	elgg_set_ignore_access($ia);
	$nomoobu = $number_of_entities_owned_by_user + $number_of_annotations_owned_by_user;
	return $nomoobu;
}
elgg_register_event_handler('init','system','ntlm_auth_init');
?>
