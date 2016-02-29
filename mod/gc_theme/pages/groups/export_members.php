<?php
$group_guid = get_input('group_guid',0);
if ($group_guid) {
	header("Content-Type: text/csv");
	header("Content-Disposition: Attachment; filename=export.csv");
	header('Pragma: public');
	$members = gc_get_group_members($group_guid, $limit = 0, $offset = 0, $site_guid = 0, $count = false);
	foreach ($members as $member) {
		echo $member->username.';'.$member->name.';'.$member->email."\r\n";
	}
}
