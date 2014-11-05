<?php
/**
 * Elgg reported content: delete action
 * 
 * @package ElggReportedContent
 */


if (elgg_is_admin_logged_in()) {
	
	$cntRow = 0;
	$cntImported = 0;
	$cntSkipped = 0;
	$cntErrored = 0;
	$statusmsg = "";
	
	$mem_limit_org = ini_set('memory_limit', '1024M');
	$GLOBALS['IMPUSR_LOG']->debug("Increasing memory limit from [$mem_limit_org] to [1024M].");
	$max_exec_time_org = ini_set('max_execution_time', '0');
	$GLOBALS['IMPUSR_LOG']->debug("Setting maximun execution time from [$max_exec_time_org] to [0].");
		
	if($csvFileName = get_uploaded_filename("importUserFile")){
		$csvData = new CSVFile($csvFileName);
		
		foreach ($csvData as $line)
		{
			$errorFound = false;
			$cntRow += 1;
			$dn = trim($line["DN"]);
			$displayName = trim($line["displayName"]);
			$sAMAccountName = trim($line["sAMAccountName"]);
			$mail = trim($line["mail"]);
			
			if(!is_not_null($dn)) {
				$errorFound = true;
				$GLOBALS['IMPUSR_LOG']->error("Row [$cntRow], 'DN' is empty or null. ");
			} else {
				$GLOBALS['IMPUSR_LOG']->info("Processing: Row [$cntRow], DN[".$dn."]" . __METHOD__);
			}
			if (!is_not_null($displayName)) {
				$errorFound = true;
				$GLOBALS['IMPUSR_LOG']->error("Row [$cntRow], 'displayName' is empty or null. ");
			} 
			if (!is_not_null($sAMAccountName)) {
				$errorFound = true;
				$GLOBALS['IMPUSR_LOG']->error("Row [$cntRow], 'sAMAccountName' is empty or null. ");
			} 
			if (!is_not_null($mail)) {
				$errorFound = true;
				$GLOBALS['IMPUSR_LOG']->error("Row [$cntRow], 'mail' is empty or null. ");
			}
			
			if (!$errorFound) {
				try {
					$user = impusr_create_elgg_user($sAMAccountName, $displayName, $mail);
					if (!$user) {
						$cntErrored += 1;
						$GLOBALS['IMPUSR_LOG']->error("USER CREATION ERROR on row [$cntRow], sAMAccountName [$sAMAccountName].");
					} else {
						$cntImported += 1;
						$GLOBALS['IMPUSR_LOG']->info("Created username [$user->username] with guid [$user->guid].");
					}
				} catch (Exception $e) {
					$cntErrored += 1;
					$GLOBALS['IMPUSR_LOG']->error("USER CREATION ERROR on row [$cntRow], sAMAccountName [$sAMAccountName].");
					$GLOBALS['IMPUSR_LOG']->error("ERROR: $e->getMessage(). TRACE: $e->getTraceAsString()");
				}
			} else {
				// Last line is often empty so we will not count it.
				if (!is_not_null($dn) && 
					!is_not_null($displayName) && 
					!is_not_null($sAMAccountName) && 
					!is_not_null($mail)) {
					// no-op
				} else {
					$cntSkipped += 1;
				}
			}
		}
		$GLOBALS['IMPUSR_LOG']->info("Number of users imported : $cntImported");
		$GLOBALS['IMPUSR_LOG']->info("Number of users skipped  : $cntSkipped");
		$GLOBALS['IMPUSR_LOG']->info("Number of users error'ed : $cntErrored");
		
		$statusmsg .= " Number of users imported : " . $cntImported;
		$statusmsg .= "<br/>";
		$statusmsg .= " Number of users skipped  : " . $cntSkipped;
		$statusmsg .= "<br/>";
		$statusmsg .= " Number of users error'ed : " . $cntErrored;
	}
	
	ini_set('max_execution_time', $max_exec_time_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting maximun execution time to [$mem_limit_org].");
	ini_set('memory_limit', $mem_limit_org);
	$GLOBALS['ADSYNC_LOG']->debug("Resetting memory limit to [$mem_limit_org].");
	
	if ($errorFound) {
		register_error($statusmsg);
	} else {
		system_message($statusmsg);
	}
	
 	forward(REFERER);
}

/**
 *
 * Create an Elgg users
 *
 * @param string $username The user's username
 * @param string $name The user's display name
 * @param string $email The user's email address
 *
 * @return ElggUser|false Depending on success
 */
function impusr_create_elgg_user($username = null, $name = null, $email = null) {
	$result = false;

	$GLOBALS['ADSYNC_LOG']->debug("=== STARTING: " . __METHOD__);

	$password = generateRandomString(10);

	try {
		if($users = get_user_by_email($email)){
			// found a user with this email on the site, so invite (or add)
			$user = $users[0];
			$GLOBALS['IMPUSR_LOG']->info("User with same email address [$email] already exist with username [$user->username]." . __METHOD__);
		} elseif (get_user_by_username($username)) {
			$GLOBALS['IMPUSR_LOG']->info("User with same username [$username] already exist." . __METHOD__);
		} else {
			$guid = register_user($username, $password, $name, $email, FALSE);
			
			if ($guid) {
				$elgg_user = get_user_by_username($username);
			
				if ( (isset($elgg_user)) && ($elgg_user instanceof ElggUser) ) {
					$result = $elgg_user;
				}
			}
		}
	} catch (Exception $e) {
		$GLOBALS['IMPUSR_LOG']->error("ERROR: " . $e->getMessage() .  ". TRACE: " . $e->getTraceAsString());
	}
	
	return $result;
}


