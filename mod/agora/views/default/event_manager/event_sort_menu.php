<?php
$all_class_selected = $att_class_selected = $mine_class_selected ='';
$user_event_preferred_tab = elgg_get_logged_in_user_entity()->event_preferred_tab;
if (!$user_event_preferred_tab || $user_event_preferred_tab == 'all') {
	$all_class_selected ='class="elgg-state-selected"';
} elseif ($user_event_preferred_tab == 'attending') {
	$att_class_selected ='class="elgg-state-selected"';
} elseif ($user_event_preferred_tab == 'mine') {
	$mine_class_selected ='class="elgg-state-selected"';
}
?>
<div id="event_manager_result_navigation">
	<div id="event_manager_result_refreshing"><?php echo elgg_echo("event_manager:list:navigation:refreshing"); ?></div>	
	<ul class="elgg-tabs elgg-htabs">
		<li <?php echo $all_class_selected;?> id="list">	
			<a href="javascript:void(0);" rel="list"><?php echo elgg_echo('event_manager:list:navigation:list'); ?></a>
		</li>
		<li <?php echo $att_class_selected;?> id="attending">
			<a href="javascript:void(0);" rel="attending"><?php echo elgg_echo('event_manager:event:relationship:event_attending'); ?></a>
		</li>
		<li <?php echo $mine_class_selected;?> id="mine">
			<a href="javascript:void(0);" rel="mine"><?php echo elgg_echo('event_manager:event:relationship:my_events'); ?></a>
		</li>
	</ul>
</div>
