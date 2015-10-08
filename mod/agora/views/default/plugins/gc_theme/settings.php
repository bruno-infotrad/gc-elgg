<p>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('reportedcontent');?></legend>
        
        <label for="params[rcemail]"><?php echo elgg_echo('agora:settings:label:rcemail');?></label><br/>
	<?php echo elgg_view("input/text", array("name" => "params[rcemail]", "value" => $vars['entity']->rcemail)); ?>
    </fieldset>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('agora:settings:motd');?></legend>
        
        <label for="params[motd]"><?php echo elgg_echo('agora:settings:label:motd');?></label><br/>
	<?php echo elgg_view("input/longtext", array("name" => "params[motd]", "value" => $vars['entity']->motd)); ?>
        <label for="params[motd_url]"><?php echo elgg_echo('agora:settings:label:motd_url');?></label><br/>
	<?php echo elgg_view("input/text", array("name" => "params[motd_url]", "value" => $vars['entity']->motd_url)); ?>
        <label for="params[motd_display_expiry]"><?php echo elgg_echo('agora:settings:label:motd_display_expiry');?></label><br/>
	<?php echo elgg_view("input/text", array("name" => "params[motd_display_expiry]", "value" => $vars['entity']->motd_display_expiry)); ?>
        <label for="params[motd_display_duration]"><?php echo elgg_echo('agora:settings:label:motd_display_duration');?></label><br/>
	<?php echo elgg_view("input/text", array("name" => "params[motd_display_duration]", "value" => $vars['entity']->motd_display_duration)); ?>
	<label for="params[js_polling_control]"><?php echo elgg_echo('agora:settings:label:js_polling_control');?></label><br/>
	<?php echo elgg_view("input/radio", array( "name" => "params[js_polling_control]", "value" => $vars['entity']->js_polling_control, 'options' => array( elgg_echo('on') => 'on', elgg_echo('off') => 'off',),)); ?>
    </fieldset>
</p>
