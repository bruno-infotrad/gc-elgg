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
$GLOBALS['SSO'] = new FlexLog(FlexLogLevel::INFO);

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
		$GLOBALS['SSO']->info('User '.$username.' exists');
		$sql_query0 = "SELECT dfaitedsid FROM `ad2elgg_users` where extensionattribute9='".$username."' and elgg_guid is not null";
        	$signet_elgg_username = get_data($sql_query0);
		$sql_query1 = "SELECT extensionattribute9 FROM `ad2elgg_users` where dfaitedsid=lower('".$username."')";
        	$cida_elgg_username = get_data($sql_query1);
		// Now check if 1) User never logged in or 2) account is not associated with anything.
		// If one of these conditions is met check if a corresponding account exists. If it does, log user in as this account
                $nomoobu = gc_theme_get_user_objects($user);
		$GLOBALS['SSO']->info("last_login=".$user->last_login." num of objects=".$nomoobu." username=$username signet_elgg_username ".var_export($signet_elgg_username,true)." cida_elgg_username ".var_export($cida_elgg_username,true));
		if ($user->last_login == 0 || $nomoobu == 0) {
			if (count($signet_elgg_username) == 1 and $signet_elgg_username[0]->dfaitedsid) {
				$linked_username = $signet_elgg_username[0]->dfaitedsid;
				$GLOBALS['SSO']->info("SIGNET linked_username=$linked_username ");
				if ($linked_user = get_user_by_username($linked_username)) {
					if (! $linked_user->isBanned()) {
						$GLOBALS['SSO']->info("User $username exists,Logged in as SIGNET $linked_username ");
						return login($linked_user);
					} else {
						return login($user);
					}
				} else {
					return login($user);
				}
			} elseif (count($cida_elgg_username) == 1 and $cida_elgg_username[0]->extensionattribute9) {
				$linked_username = $cida_elgg_username[0]->extensionattribute9;
				$GLOBALS['SSO']->info("CIDA linked_username=$linked_username ");
				if ($linked_user = get_user_by_username($linked_username)) {
					if (! $linked_user->isBanned()) {
						$GLOBALS['SSO']->info("User $username exists,Logged in as CIDA $linked_username ");
						return login($linked_user);
					} else {
						return login($user);
					}
				} else {
					return login($user);
				}
			} else {
				return login($user);
			}
		} else {
			if (count($signet_elgg_username) == 1 and $signet_elgg_username[0]->dfaitedsid) {
				$linked_username = $signet_elgg_username[0]->dfaitedsid;
				$GLOBALS['SSO']->info("SIGNET linked_username=$linked_username ");
				if ($linked_user = get_user_by_username($linked_username))
				{
					if(gc_theme_get_user_objects($linked_user)) {
						$GLOBALS['SSO']->info("Duplicate Agora active accounts $username and $linked_username, Logged in as CIDA $username");
						//register_error(elgg_echo('gc_theme:duplicate_account'));
					} 
				} 
			} elseif (count($cida_elgg_username) == 1 and $cida_elgg_username[0]->extensionattribute9) {
				$linked_username = $cida_elgg_username[0]->extensionattribute9;
				$GLOBALS['SSO']->info("CIDA linked_username=$linked_username ");
				if ($linked_user = get_user_by_username($linked_username))
				{
					if(gc_theme_get_user_objects($linked_user)) {
						$GLOBALS['SSO']->info("Duplicate Agora active accounts $username and $linked_username, Logged in as SIGNET $username");
						//register_error(elgg_echo('gc_theme:duplicate_account'));
					} 
				} 
			}
			return login($user);
		}
	}
	else
	{
		// user loging in with username, account does not exist in Agora but association exists in add2elgg_users
		// no duplicates can exist
		$GLOBALS['SSO']->info('User '.$username.' does not exists');
		$sql_query0 = "SELECT dfaitedsid FROM `ad2elgg_users` where extensionattribute9='".$username."' and elgg_guid is not null";
        	$signet_elgg_username = get_data($sql_query0);
		$sql_query1 = "SELECT extensionattribute9 FROM `ad2elgg_users` where dfaitedsid=lower('".$username."')";
        	$cida_elgg_username = get_data($sql_query1);
		$GLOBALS['SSO']->info("username=$username signet_elgg_username ".var_export($signet_elgg_username,true)." cida_elgg_username ".var_export($cida_elgg_username,true));
		if (count($signet_elgg_username) == 1 and $signet_elgg_username[0]->dfaitedsid) {
			$linked_username = $signet_elgg_username[0]->dfaitedsid;
			$GLOBALS['SSO']->info("SIGNET linked_username=$linked_username ");
			if ($linked_user = get_user_by_username($linked_username)) {
				if (! $linked_user->isBanned()) {
					$GLOBALS['SSO']->info("User $username does not exist, Logged in as SIGNET $linked_username");
					return login($linked_user);
				} else {
					return login($user);
				}
			} else {
				return false;
			}
		} elseif (count($cida_elgg_username) == 1 and $cida_elgg_username[0]->extensionattribute9) {
			$linked_username = $cida_elgg_username[0]->extensionattribute9;
			$GLOBALS['SSO']->info("CIDA linked_username=$linked_username ");
			if ($linked_user = get_user_by_username($linked_username)) {
				if (! $linked_user->isBanned()) {
					$GLOBALS['SSO']->info("User $username does not exist, Logged in as CIDA $linked_username");
					return login($linked_user);
				} else {
					return login($user);
				}
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
						'subtypes' => array('blog','bookmarks','file','folder','groupforumtopic','page','page_top','poll','poll_choice','thewire'),
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
