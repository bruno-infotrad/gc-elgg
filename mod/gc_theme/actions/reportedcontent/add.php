<?php
/**
 * Elgg report action
 * 
 * @package ElggReportContent
 */
$title = get_input('title');
$description = get_input('description');
$address = get_input('address');
$access = ACCESS_PRIVATE; //this is private and only admins can see it
$rcemail = elgg_get_plugin_setting('rcemail', 'gc_theme');
if ($title && $address) {

	$report = new ElggObject;
	$report->subtype = "reported_content";
	$report->owner_guid = elgg_get_logged_in_user_guid();
	$body = 'Report by: ' . elgg_get_logged_in_user_entity()->username;
	$report->title = $title;
	$body = $body ."\nTitle: ". $title;
	$report->address = $address;
	$body = $body ."\nURL: ". $address;
	$report->description = $description;
	$body = $body ."\n\n". $description;
	$report->access_id = $access;

	if ($report->save()) {
		if (!elgg_trigger_plugin_hook('reportedcontent:add', 'system', array('report' => $report), true)) {
			$report->delete();
			register_error(elgg_echo('reportedcontent:failed'));
		} else {
			system_message(elgg_echo('reportedcontent:success'));
			$report->state = "active";
		}
		if ($rcemail) {	
			elgg_send_email(elgg_get_config('siteemail'),$rcemail,"Reported content from ".elgg_get_config('sitename'),$body);
		}
		forward($address);
	} else {
		register_error(elgg_echo('reportedcontent:failed'));
		forward($address);
	}
} else {

	register_error(elgg_echo('reportedcontent:failed'));
	forward($address);
}
