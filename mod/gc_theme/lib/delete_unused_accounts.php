<?php
/*
 * Utility to delete unused account (last_Login = 0)
*/
$sql_query_count = "SELECT count(*) as count FROM `elgg_users_entity` where last_login = 0 and username regexp '[a-zA-Z]{3}[[:digit:]]{3}' and username regexp '^......$' order by username";
$db_count=get_data($sql_query_count);
$count = $db_count[0]->count;
$GLOBALS['DUA_LOG']->fatal('Processing '.$count.' unused xCIDA accounts');
$sql_query = "SELECT username FROM `elgg_users_entity` where last_login = 0 and username regexp '[a-zA-Z]{3}[[:digit:]]{3}' and username regexp '^......$' order by username";
$list_of_users = get_data($sql_query);
foreach ($list_of_users as $sql_user) {
	$username = $sql_user->username;
	$user=get_user_by_username($username);
/* Remove to ensure personal data is not destroyed
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
	$nomoobu = $number_of_entities_owned_by_user + $number_of_annotations_owned_by_user;
	if ($user->last_login == 0 || $nomoobu == 0) {
*/
	if ($user->last_login == 0) {
		$GLOBALS['DUA_LOG']->DEBUG('Deleting user '.$username);
		if($user->delete()) {
			$GLOBALS['DUA_LOG']->fatal('User '.$username.' deleted');
		} else {
			$GLOBALS['DUA_LOG']->fatal('User '.$username.' COULD NOT BE DELETED');
		}
	}
}
