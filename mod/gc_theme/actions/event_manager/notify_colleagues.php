<?php 
$guid = (int) get_input("guid");

if(!empty($guid) && $entity = get_entity($guid)){
	if($entity->getSubtype() == Event::SUBTYPE)	{
		$db_prefix = elgg_get_config('dbprefix');
		$event = $entity;
		$user = elgg_get_logged_in_user_entity();
		$options = array(
			'relationship' => 'friend',
			'relationship_guid' => $user->getGUID(),
			'inverse_relationship' => FALSE,
			'type' => 'user',
			'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
			'wheres' => array("(u.banned = 'no')"),
			'limit' => '0',
		);

		$colleagues = elgg_get_entities_from_relationship($options);
		if ($colleagues) {
			$from = "$user->email";
			foreach ($colleagues as $colleague) {
				$subject = elgg_echo("event_manager:notify_colleagues:subject",array($event->title),"$colleague->language");
				$to = $colleague->email;
				$body = elgg_echo("event_manager:notify_colleagues:body",array($colleague->name,$user->name,$event->title,date('y-n-d',$event->start_day),$event->venue,elgg_get_site_url()."events/event/view/$guid"),$colleague->language);
				elgg_send_email($from,$to,$subject,$body);
			}
			system_message(elgg_echo("event_manager:action:notify_colleagues:ok"));
		} 
		forward("/events/event/view/$guid");
	}
}

system_message(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
forward(REFERER);
