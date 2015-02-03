<?php
$event_guid = get_input('event_guid');
$container_guid = get_input('container_guid');
elgg_log("EVENT_MANAGER event_guid=$event_guid container_guid=$container_guid",'NOTICE');
if (! $event_guid || ! $container_guid) {
	register_error(elgg_echo('event_manager:notify:groups:missing_event_or_container'));
	forward(REFERER);
}
$event = get_entity($event_guid);
$title = htmlspecialchars($event->title, ENT_QUOTES, 'UTF-8');
$description = $event->description;
$address = elgg_get_site_url().'events/event/view/'.$event_guid;
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://$address";
}
if (!$title || !$address) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}
// see https://bugs.php.net/bug.php?id=51192
$php_5_2_13_and_below = version_compare(PHP_VERSION, '5.2.14', '<');
$php_5_3_0_to_5_3_2 = version_compare(PHP_VERSION, '5.3.0', '>=') &&
		version_compare(PHP_VERSION, '5.3.3', '<');

$validated = false;
if ($php_5_2_13_and_below || $php_5_3_0_to_5_3_2) {
	$tmp_address = str_replace("-", "", $address);
	$validated = filter_var($tmp_address, FILTER_VALIDATE_URL);
} else {
	$validated = filter_var($address, FILTER_VALIDATE_URL);
}
if (!$validated) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}
$user_id = elgg_get_logged_in_user_guid();
//Check for multiple container
if (preg_match('/,/',$container_guid)) {
	$mv_container_guid = true;
	$container_guids = preg_split('/,/',$container_guid);
}
if ($mv_container_guid) {
	foreach($container_guids as $container_guid) {
		$bookmark = new ElggObject;
		$bookmark->subtype = "bookmarks";
		$bookmark->title = $title;
		$bookmark->address = $address;
		$bookmark->description = $description;
		$bookmark->container_guid = $container_guid;
		$gguid=get_entity($container_guid);
		elgg_log("EVENT_MANAGER notify groups =".$gguid->membership,'NOTICE');
		if ($gguid instanceof ElggGroup) {
			$gac=get_data_row("SELECT id FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid='$container_guid'");
			elgg_log("EVENT_MANAGER notiy groups group_access_collections ".$gac->id,'NOTICE');
			$bookmark->access_id = $gac->id;
		} else {
			$bookmark->access_id = ACCESS_LOGGED_IN;
		}
		$res = $bookmark->save();
		if (! $res) {
			$failed=true;
		} else {
			add_to_river('river/object/bookmarks/create','create', elgg_get_logged_in_user_guid(), $bookmark->getGUID());
			//add_to_river('river/object/bookmarks/event','create', elgg_get_logged_in_user_guid(), $bookmark->getGUID());
		}
		unset($bookmark);
	}
} else {
	$bookmark = new ElggObject;
	$bookmark->subtype = "bookmarks";
	$bookmark->title = $title;
	$bookmark->address = $address;
	$bookmark->description = $description;
	$bookmark->container_guid = $container_guid;
	$gguid=get_entity($container_guid);
	elgg_log("EVENT_MANAGER notify groups =".$gguid->membership,'NOTICE');
	if ($gguid instanceof ElggGroup && $gguid->membership == ACCESS_PRIVATE) {
		$gac=get_data_row("SELECT id FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid='$container_guid'");
		elgg_log("EVENT_MANAGER notiy groups group_access_collections ".$gac->id,'NOTICE');
		$bookmark->access_id = $gac->id;
	} else {
		$bookmark->access_id = ACCESS_LOGGED_IN;
	}
	$res = $bookmark->save();
	if (! $res) {
		$failed=true;
	} else {
		add_to_river('river/object/bookmarks/create','create', elgg_get_logged_in_user_guid(), $bookmark->getGUID());
		//add_to_river('river/object/bookmarks/event','create', elgg_get_logged_in_user_guid(), $bookmark->getGUID());
	}
	unset($bookmark);
}
if ($failed) {
	system_message(elgg_echo('bookmarks:save:failed'));
} else {
	system_message(elgg_echo('bookmarks:save:success'));
}
forward($address);
