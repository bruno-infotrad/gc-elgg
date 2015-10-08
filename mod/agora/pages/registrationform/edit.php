<?php 
	gatekeeper();

	$title_text = elgg_echo("event_manager:editregistration:title");
	
	$guid = get_input("guid");
	
	if($entity = get_entity($guid))	{	
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	if(!empty($event)) {
		if($event->canEdit()) {
			elgg_push_breadcrumb($entity->title, $event->getURL());
			elgg_push_breadcrumb($title_text);
			
			$output  ='<div id="registrationform-preamble">'.elgg_echo('agora:registrationform_preamble').'</div>';
			$output  .='<div class="elgg-menu gc-event_manager_registrationform_fields">';
			$output  .='<ul id="event_manager_registrationform_fields">';
			if ($event->access_id == ACCESS_PRIVATE) {
				$ia = elgg_set_ignore_access(true);
			}
			if($registration_form = $event->getRegistrationFormQuestions()) {
				foreach($registration_form as $question) {
					$output .= elgg_view('event_manager/registration/question', array('entity' => $question));
				}
			}
			
			$output .= '</ul>';	
			$output .= '<br /><a rel="'.$guid.'" id="event_manager_questions_add" href="javascript:void(0);" class="elgg-button elgg-button-action">' . elgg_echo('event_manager:editregistration:addfield') . '</a>';
			$output .= '<div class="elgg-menu-item-groups-invite">';
			$output .= '<a href="'.elgg_get_site_url().'events/event/view/'.$guid.'" class="elgg-button" id="registration-form-button">' . elgg_echo('agora:done') . '</a>';
			$output .= '</div>';
			$output .= '</div>';
			
			$body = elgg_view_layout('content', array(
				'filter' => '',
				'content' => $output,
				'title' => $title_text,
			));
			if ($ia) {
				elgg_set_ignore_access($ia);
			}
			echo elgg_view_page($title_text, $body);			
		} else {
			forward($event->getURL());
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}
?>
<script>
$(document).ready(function() {
        $('#registration-form-button').css('pointer-events','none');
        $('#registration-form-button').attr('disabled','disabled');
        $('#registration-form-button').css('opacity',0.5);
});
$('#event_manager_registrationform_fields').bind("DOMSubtreeModified", function() {
	var count= $('li.elgg-module-popup').length;
	if (count) {
        	$('#registration-form-button').css('pointer-events','all');
        	$('#registration-form-button').removeAttr('disabled','disabled');
        	$('#registration-form-button').css('opacity',1);
	} else {
        	$('#registration-form-button').css('pointer-events','none');
        	$('#registration-form-button').attr('disabled','disabled');
        	$('#registration-form-button').css('opacity',0.5);
	}
});
</script>
