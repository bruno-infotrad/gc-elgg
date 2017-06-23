<?php

	function event_manager_user_hover_menu($hook, $entity_type, $returnvalue, $params){
		$result = $returnvalue;
		$event = false;

		$guid = get_input("guid");

		if(!empty($guid) && ($entity = get_entity($guid))){
			if($entity->getSubtype() == Event::SUBTYPE) {
				$event = $entity;
			}
		}
		if($event){
			if (!$event->canEdit()) {
				return;
			}
			$user = elgg_extract("entity", $params);

			if($user){
				// kick from event
				$href = elgg_get_site_url() . 'action/event_manager/event/rsvp?guid=' . $event->getGUID() . '&user=' . $user->getGUID() . '&type=' . EVENT_MANAGER_RELATION_REVOKE;
				$href = elgg_add_action_tokens_to_url($href);

				$item = new ElggMenuItem("event_manager_kick", elgg_echo("event_manager:event:relationship:kick"), $href);
				$item->setSection("action");

				$result[] = $item;

				$user_relationship = $event->getRelationshipByUser($user->getGUID());
				
				if ($user_relationship == EVENT_MANAGER_RELATION_ATTENDING_PENDING) {
					// resend confirmation
					$href = elgg_get_site_url() . 'action/event_manager/event/resend_confirmation?guid=' . $event->getGUID() . '&user=' . $user->getGUID();
					$href = elgg_add_action_tokens_to_url($href);
	
					$item = new ElggMenuItem("event_manager_resend_confirmation", elgg_echo("event_manager:event:menu:user_hover:resend_confirmation"), $href);
					$item->setSection("action");
	
					$result[] = $item;
				}

				if (in_array($user_relationship, array(EVENT_MANAGER_RELATION_ATTENDING_PENDING, EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST))) {
					// move to attendees
					$href = elgg_get_site_url() . 'action/event_manager/attendees/move_to_attendees?guid=' . $event->getGUID() . '&user=' . $user->getGUID();
					$href = elgg_add_action_tokens_to_url($href);
	
					$item = new ElggMenuItem("event_manager_move_to_attendees", elgg_echo("event_manager:event:menu:user_hover:move_to_attendees"), $href);
					$item->setSection("action");
	
					$result[] = $item;
				}
			}
		}

		return $result;
	}

	function event_manager_entity_menu($hook, $entity_type, $returnvalue, $params){
		$result = $returnvalue;

		if (elgg_in_context("widgets")) {
			return $result;
		}

		if (($entity = elgg_extract("entity", $params)) && elgg_instanceof($entity, "object", Event::SUBTYPE)) {
			$attendee_menu_options = array(
				"name" => "attendee_count",
				"priority" => 50,
				"text" => elgg_echo("event_manager:event:relationship:event_attending:entity_menu", array($entity->countAttendees())),
				"href" => false
			);

			$result[] = ElggMenuItem::factory($attendee_menu_options);

			// change some of the basic menus
			if (!empty($result) && is_array($result)) {
				foreach ($result as &$item) {
					switch ($item->getName()) {
						case "edit":
							$item->setHref("events/event/edit/" . $entity->getGUID());
							break;
						case "delete":
							$href = elgg_get_site_url() . "action/event_manager/event/delete?guid=" . $entity->getGUID();
							$href = elgg_add_action_tokens_to_url($href);

							$item->setHref($href);
							$item->setConfirmText(elgg_echo("deleteconfirm"));
							break;
					}
				}
			}

			// show an unregister link for non logged in users
			if (!elgg_is_logged_in() && $entity->register_nologin) {
				$result[] = ElggMenuItem::factory(array(
					"name" => "unsubscribe",
					"text" => elgg_echo("event_manager:menu:unsubscribe"),
					"href" => "events/unsubscribe/" . $entity->getGUID() . "/" . elgg_get_friendly_title($entity->title),
					"priority" => 300
				));
			}
		}

		return $result;
	}

	/**
	 * add menu item to owner block
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function event_manager_owner_block_menu($hook, $entity_type, $returnvalue, $params){
		$group = elgg_extract("entity", $params);
		if (elgg_instanceof($group, 'group') && $group->event_manager_enable != "no") {
			$url = '/events/event/list/' . $group->getGUID();
			$item = new ElggMenuItem('events', elgg_echo('event_manager:menu:group_events'), $url);
			$returnvalue[] = $item;
		}

		return $returnvalue;
	}

	/**
	 * Generates correct title link for widgets depending on the context
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return optional new link
	 */
	function event_manager_widget_events_url($hook, $entity_type, $returnvalue, $params){
		$result = $returnvalue;
		$widget = $params["entity"];

		if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "events"){
			switch($widget->context){
				case "index":
					$result = "/events";
					break;
				case "groups":
					$result = "/events/event/list/" . $widget->getOwnerGUID();
					break;
				case "profile":
				case "dashboard":
					break;
			}
		}
		return $result;
	}

	/**
	 * Allow non user to remove their registration correctly
	 *
	 * @param string $hook
	 * @param string $entity_type
	 * @param bool $returnvalue
	 * @param array $params
	 * @return bool
	 */
	function event_manager_permissions_check_handler($hook, $entity_type, $returnvalue, $params) {
		global $EVENT_MANAGER_UNDO_REGISTRATION;
		$result = $returnvalue;

		// only override the hook if not already allowed
		if (!$result && !empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);

			if (elgg_instanceof($entity, "object", EventRegistration::SUBTYPE)) {
				if (!empty($EVENT_MANAGER_UNDO_REGISTRATION)) {
					$result = true;
				}
			}
		}

		return $result;
	}

	/**
	 * Prepare a notification message about a created event
	 *
	 * @param string                          $hook         Hook name
	 * @param string                          $type         Hook type
	 * @param Elgg_Notifications_Notification $notification The notification to prepare
	 * @param array                           $params       Hook parameters
	 * 
	 * @return Elgg_Notifications_Notification
	 */
	function event_manager_prepare_notification($hook, $type, $notification, $params) {
		$entity = $params['event']->getObject();
		$owner = $params['event']->getActor();
		$language = $params['language'];
		$method = $params['method'];
		$start_day = date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $entity->start_day);
		$end_day = date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $entity->end_ts);
		$start_time_hours = date('H', $entity->start_time);
		$start_time_minutes = date('i', $entity->start_time);
		$start_time = $start_time_hours.'h'.$start_time_minutes;
		$end_time_hours = date('H', $entity->end_ts);
		$end_time_minutes = date('i', $entity->end_ts);
		$end_time = $end_time_hours.'h'.$end_time_minutes;
		
		$subject = elgg_echo('event_manager:notification:subject', array(), $language);
		$summary = elgg_echo('event_manager:notification:summary', array(), $language);
		$body = elgg_echo('event_manager:notification:body', array($owner->name, $entity->title, $start_day, $end_day, $start_time, $end_time), $language);
		if ($description = $entity->shortdescription) {
			$body .= PHP_EOL . PHP_EOL . $description;
		} elseif ($description = $entity->description) {
			$body .= PHP_EOL . PHP_EOL . elgg_get_excerpt($description);
		}
		$body .= PHP_EOL . PHP_EOL . $entity->getURL();
		$notification->subject = $subject;
		$notification->body = $body;
		$notification->summary = $summary;
		return $notification;
	}
	
	/**
	 * Notification body for events
	 *
	 * @param string $hook
	 * @param string $entity_type
	 * @param string $returnvalue
	 * @param array $params
	 * @return string
	 */
	function event_manager_notify_message($hook, $entity_type, $returnvalue, $params) {
		$result = $returnvalue;

		if (!empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);

			if (!empty($entity) && elgg_instanceof($entity, "object", Event::SUBTYPE)) {
				$user = $entity->getOwnerEntity();
				if (!$user) {
					$user = elgg_get_logged_in_user_entity();
				}

				$result = elgg_echo("event_manager:notification:body", array($user->name, $entity->title));

				if ($description = $entity->description) {
					$result .= PHP_EOL . PHP_EOL . elgg_get_excerpt($description);
				}

				$result .= PHP_EOL . PHP_EOL . $entity->getURL();
			}
		}

		return $result;
	}
