<div id="event_manager_result_navigation">
	<div id="event_manager_result_refreshing"><?php echo elgg_echo("event_manager:list:navigation:refreshing"); ?></div>	
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected" id="list">	
			<a href="javascript:void(0);" rel="list"><?php echo elgg_echo('event_manager:list:navigation:list'); ?></a>
		</li>
		<li id="attending">
			<a href="javascript:void(0);" rel="attending"><?php echo elgg_echo('event_manager:event:relationship:event_attending'); ?></a>
		</li>
		<li id="mine">
			<a href="javascript:void(0);" rel="mine"><?php echo elgg_echo('event_manager:event:relationship:my_events'); ?></a>
		</li>
	</ul>
</div>
