<p>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('reportedcontent');?></legend>
        
        <label for="params[rcemail]"><?php echo elgg_echo('gc_theme:settings:label:rcemail');?></label><br/>
	<?php echo elgg_view("input/text", array("name" => "params[rcemail]", "value" => $vars['entity']->rcemail)); ?>
    </fieldset>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
	<label for="params[classified_expiry]"><?php echo elgg_echo('gc_theme:settings:label:classified_expiry');?></label><br/>
	<?php echo elgg_view("input/text", array( "name" => "params[classified_expiry]", "value" => $vars['entity']->classified_expiry,)); ?>
    </fieldset>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
	<label for="params[js_polling_control]"><?php echo elgg_echo('gc_theme:settings:label:js_polling_control');?></label><br/>
	<?php echo elgg_view("input/radio", array( "name" => "params[js_polling_control]", "value" => $vars['entity']->js_polling_control, 'options' => array( elgg_echo('on') => 'on', elgg_echo('off') => 'off',),)); ?>
    </fieldset>
</p>
