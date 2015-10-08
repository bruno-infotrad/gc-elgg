<?php
$user = $vars['user'];
$group = $vars['group'];
if (! $group->isMember($user)) {
	return;
}
global $NOTIFICATION_HANDLERS;
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	$subsbig[$method] = elgg_get_entities_from_relationship(array(
		'relationship' => 'notify' . $method,
		'relationship_guid' => $user->guid,
		'type' => 'group',
		'limit' => false,
	));
	$tmparray = array();
	if ($subsbig[$method]) {
		foreach($subsbig[$method] as $tmpent) {
			$tmparray[] = $tmpent->guid;
		}
	}
	$subsbig[$method] = $tmparray;
}

?>

<div class="elgg-module elgg-module-info single-group-save">
	<div class="elgg-body">
	<?php
		echo elgg_view('notifications/subscriptions/jsfuncs',$vars);
	?>
<?php

if ($group) {
	$i = 0; 
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		$i++;
	}
		
		$fields = '<h4>'.elgg_echo('agora:single_group_notification').'</h4>';
		//$fields = '';
		$i = 0;
		
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			if (in_array($group->guid,$subsbig[$method])) {
				$checked[$method] = 'checked="checked"';
			} else {
				$checked[$method] = '';
			}
			$notification_label =  elgg_echo('agora:notification:method:'.$method);
			$notification_helper =  "<div class='togglefieldhelper'>".elgg_echo('agora:notification:methodhelper:'.$method).'</div>';
			$fields .= <<< END
				<div class="{$method}togglefield">
				<a border="0" title="$notification_label" id="{$method}{$group->guid}" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}{$group->guid}');">
				<input type="checkbox" name="{$method}subscriptions[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}{$group->guid}');" value="{$group->guid}" {$checked[$method]} /></a>$notification_helper</div>
END;
			$i++;
		}
		echo $fields;
}
?>
	</div>
</div>
<script>
var site_url = elgg.get_site_url();
var user_guid = elgg.get_logged_in_user_guid();
var group_guid = "<?php echo $group->guid ;?>";
var preference_saved = "<?php echo elgg_echo('agora:usersettings:save:ok'); ?>";
$('body').on('click','.emailtogglefield', function() {
                if ($('.emailtogglefield input[type=checkbox]').is(':checked')){
                        $.post(elgg.security.addToken(site_url+'action/notificationsettings/single_groupsave?&user_guid=' + user_guid + '&group_guid=' + group_guid + '&method=email&status=on'));
			elgg.system_message(preference_saved);
                } else {
                        $.post(elgg.security.addToken(site_url+'action/notificationsettings/single_groupsave?&user_guid=' + user_guid + '&group_guid=' + group_guid + '&method=email&status=off'));
			elgg.system_message(preference_saved);
                };
});
$('body').on('click','.sitetogglefield', function() {
                if ($('.sitetogglefield input[type=checkbox]').is(':checked')){
                        $.post(elgg.security.addToken(site_url+'action/notificationsettings/single_groupsave?&user_guid=' + user_guid + '&group_guid=' + group_guid + '&method=site&status=on'));
			elgg.system_message(preference_saved);
                } else {
                        $.post(elgg.security.addToken(site_url+'action/notificationsettings/single_groupsave?&user_guid=' + user_guid + '&group_guid=' + group_guid + '&method=site&status=off'));
			elgg.system_message(preference_saved);
                };
});
</script>
