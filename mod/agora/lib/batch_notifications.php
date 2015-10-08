<?php
function gc_object_notifications($event, $object_type, $object) {
	// We only want to trigger notification events for ElggEntities
	$queued_notifications = elgg_get_entities(array(
		"type" => "object",
		"subtype" => 'gc_notification',
		"order_by" => "time_created asc",
		'limit' => false,
	));
	elgg_log('BRUNO batch gc_object_notification queued_notifications='.var_export($queued_notifications,true),'NOTICE');
	foreach ($queued_notifications as $queued_notification) {
		$object = get_entity($queued_notification->objet);
		if (! $object) {
		//No idea where the object is
			$queued_notification->delete();
			continue;
		}
		$object_type = $queued_notification->type_objet;
		$event = $queued_notification->event;
		$processed = $queued_notification->processed;
		if ($object->subtype ==  get_subtype_id('object', 'groupforumtopic')) {
			$object->reply_owner = $queued_notification->getOwnerGUID();
		}
		elgg_log('BRUNO batch gc_object_notification queued_notification->ownerGUID='.$queued_notification->getOwnerGUID(),'NOTICE');
		elgg_log('BRUNO batch gc_object_notification object->objectGUID='.$object->getGUID(),'NOTICE');
		elgg_log('BRUNO batch gc_object_notification object->ownerGUID='.$object->getOwnerGUID(),'NOTICE');
		elgg_log('BRUNO batch gc_object_notification object->subtype='.$object->subtype,'NOTICE');
		elgg_log('BRUNO batch gc_object_notification object_type='.$object_type,'NOTICE');
		elgg_log('BRUNO batch gc_object_notification event='.$event,'NOTICE');
		elgg_log('BRUNO batch gc_object_notification processed='.$processed,'NOTICE');
		//$queued_notification->delete();
		if ($object instanceof ElggEntity && $processed == false) {
			$queued_notification->processed = true;
			$processed = $queued_notification->processed;
			elgg_log('BRUNO batch gc_object_notification inside processing loop','NOTICE');
			elgg_log('BRUNO batch gc_object_notification gc_notification->processed='.$processed,'NOTICE');

			// Get config data
			global $CONFIG, $SESSION, $NOTIFICATION_HANDLERS;

			$hookresult = elgg_trigger_plugin_hook('object:notifications', $object_type, array(
				'event' => $event,
				'object_type' => $object_type,
				'object' => $object,
			), false);
			if ($hookresult === true) {
				$queued_notification->delete();
				return true;
			}
			elgg_log('BRUNO batch gc_object_notification after object:notifications hook','NOTICE');

			// Have we registered notifications for this type of entity?
			$object_type = $object->getType();
			if (empty($object_type)) {
				$object_type = '__BLANK__';
			}

			$object_subtype = $object->getSubtype();
			if (empty($object_subtype)) {
				$object_subtype = '__BLANK__';
			}

			if (isset($CONFIG->register_objects[$object_type][$object_subtype])) {
				$descr = $CONFIG->register_objects[$object_type][$object_subtype];
				$string = $descr . ": " . $object->getURL();

				// Get users interested in content from this person and notify them
				// (Person defined by container_guid so we can also subscribe to groups if we want)
				foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
					$interested_users = elgg_get_entities_from_relationship(array(
						'site_guids' => ELGG_ENTITIES_ANY_VALUE,
						'relationship' => 'notify' . $method,
						'relationship_guid' => $object->container_guid,
						'inverse_relationship' => TRUE,
						'type' => 'user',
						'limit' => false,
					));

					if ($interested_users && is_array($interested_users)) {
						foreach ($interested_users as $user) {
							if ($user instanceof ElggUser && !$user->isBanned()) {
								if (($user->guid != $queued_notification->getOwnerGUID()) && has_access_to_entity($object, $user)
								&& $object->access_id != ACCESS_PRIVATE) {
									$methodstring = elgg_trigger_plugin_hook('notify:entity:message', $object->getType(), array(
										'entity' => $object,
										'to_entity' => $user,
										'method' => $method), $string);
									if (empty($methodstring) && $methodstring !== false) {
										$methodstring = $string;
									}
									if ($methodstring !== false) {
										notify_user($user->guid, $object->container_guid, $descr, $methodstring,
											NULL, array($method));
									}
								}
							}
						}
					}
				}
			}
		$queued_notification->delete();
		}
	}
}


/**
 * Returns a more meaningful message
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function gc_groupforumtopic_notify_message($hook, $entity_type, $returnvalue, $params) {

	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	elgg_log("BRUNO batch function gc_groupforumtopic_notify_message entity=".var_export($entity,true),'NOTICE');
	elgg_log("BRUNO batch function gc_groupforumtopic_notify_message to_entity=".var_export($to_entity,true),'NOTICE');
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'groupforumtopic')) {

		$descr = $entity->description;
		$title = $entity->title;
		$url = $entity->getURL();

		$msg = get_input('topicmessage');
		if (empty($msg))
			$msg = get_input('topic_post');
		if (!empty($msg))
			$msg = $msg . "\n\n"; else
			$msg = '';

		$owner = get_entity($entity->container_guid);
		if ($method == 'sms') {
			return elgg_echo("groupforumtopic:new") . ': ' . $url . " ({$owner->name}: {$title})";
		} else {
			return get_entity($entity->reply_owner)->name . ' ' . elgg_echo("groups:viagroups") . ': ' . $title . "\n\n" . $msg . "\n\n" . $entity->getURL();
		}
	}
	return null;
}
