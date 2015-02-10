<?php 
	
	if($vars['entity'])	{		
		$guid = $vars['entity']->getGUID();
		$form_body = elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
		$form_body .= '<label>' . elgg_echo('title') . ' *</label><br />' . elgg_view('input/text', array('name' => 'title', 'value' => '')) . '<br />';
		$form_body .= '<label>' . elgg_echo('event_manager:edit:form:file') . ' *</label><br />' . elgg_view('input/file', array('name' => 'file')) . '<br />';
		$form_body .= elgg_view('input/submit', array('value' => elgg_echo('upload')));
		$form_body .= '<a href="'.elgg_get_site_url().'events/event/view/'.$guid.'" id="event-manager-file-upload" class="elgg-button elgg-button-submit">' . elgg_echo('gc_theme:done') . '</a>';
		$form_body .= '<br />(* = ' . elgg_echo('requiredfields') . ')';
		
		$form = elgg_view('input/form', array('id' => 'event_manager_event_upload', 
											  'name' => 'event_manager_event_upload', 
											  'action' => 'action/event_manager/event/upload', 
											  'enctype' => 'multipart/form-data', 
											  'body' => $form_body));
		
		echo elgg_view_module("main", "", $form);
	}
