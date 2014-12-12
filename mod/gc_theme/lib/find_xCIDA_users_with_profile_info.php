<?php
global $CONFIG;
if (elgg_is_admin_logged_in()) {
	echo "<html><body>";
	$tot=0;
	$users = get_data("SELECT username FROM {$CONFIG->dbprefix}users_entity eu JOIN {$CONFIG->dbprefix}entities e ON eu.guid=e.guid");
	//$offset=0;
	//while($users = elgg_get_entities(array( 'type' => 'user','subtype'=> null,'limit'=>100,'offset'=>$offset))) {
	foreach ($users as $userbare) {
		$user=get_user_by_username($userbare->username);
		//echo "User $user->username<br>";
		if(! $user->isBanned()) {
			if (preg_match('/[a-zA-Z]{3}[0-9]{3}/',$user->username)) {
					$GLOBALS['DUA_LOG']->FATAL("User $user->username");
					$user_metadata = profile_manager_get_user_profile_data($user);
					$count=count($user_metadata);
					if (($count > 1)&&($user->icontime)) {
						$tot+=1;
						$avtot+=1;
						echo "$user->username has an avatar and $count profile attributes $avtot $tot<br>";
						//echo var_export($user_metadata,TRUE)."<br>";
					} elseif ($count > 1) {
						$tot+=1;
						echo "$user->username has $count profile attributes $avtot $tot<br>";
						//echo var_export($user_metadata,TRUE)."<br>";
					} elseif ($user->icontime) {
						$avtot+=1;
						echo "$user->username has an avatar $avtot $tot<br>";
						//echo var_export($user_metadata,TRUE)."<br>";
					}
			}
		}
	}
	echo "Number of users with profile metadata: $tot<br>";
	echo "</body></html>";
	exit;
}
