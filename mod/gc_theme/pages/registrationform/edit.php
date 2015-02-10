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
			
			$output  ='<div class="elgg-menu gc-event_manager_registrationform_fields">';
			$output  .='<ul id="event_manager_registrationform_fields">';
			
			if($registration_form = $event->getRegistrationFormQuestions()) {
				foreach($registration_form as $question) {
					$output .= elgg_view('event_manager/registration/question', array('entity' => $question));
				}
			}
			
			$output .= '</ul>';	
			$output .= '<br /><a rel="'.$guid.'" id="event_manager_questions_add" href="javascript:void(0);" class="elgg-button elgg-button-action">' . elgg_echo('event_manager:editregistration:addfield') . '</a>';
			//$output .= '<a href="'.elgg_get_site_url().'events/event/view/'.$guid.'" class="elgg-menu-item-groups-invite elgg-button">' . elgg_echo('Done') . '</a>';
			$output .= '<div class="elgg-menu-item-groups-invite">';
			$output .= '<a href="'.elgg_get_site_url().'events/event/view/'.$guid.'" class="elgg-button">' . elgg_echo('gc_theme:done') . '</a>';
			$output .= '</div>';
			$output .= '</div>';
			
			$body = elgg_view_layout('content', array(
				'filter' => '',
				'content' => $output,
				'title' => $title_text,
			));
			
			echo elgg_view_page($title_text, $body);			
		} else {
			forward($event->getURL());
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}
