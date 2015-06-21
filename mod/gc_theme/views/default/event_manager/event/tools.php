<?php 
$event = $vars["entity"];
$event_guid = $event->getGUID();
$options = array(
	'relationship' => 'friend',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => FALSE,
	'type' => 'user',
	'limit' => '0',
	'count' => true,
);
$num_of_colleagues = elgg_get_entities_from_relationship($options);


$toolLinks = "<span class='event_manager_event_actions'>" . elgg_echo('tools') . "</span>";
$toolLinks .= "<ul class='event_manager_event_actions_drop_down'>";
$toolLinks .= "<li>" . elgg_view("output/url", array("href" => "events/event/edit/" . $event->getGUID(), "text" => elgg_echo("event_manager:event:editevent"))) . "</li>";
$toolLinks .= "<li>" . elgg_view("output/url", array("href" => "action/event_manager/event/delete?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:deleteevent"), 'confirm' => true)) . "</li>";
$toolLinks .= "<li>" . elgg_view("output/url", array("href" => "events/event/upload/" . $event->getGUID(), "text" => elgg_echo("event_manager:event:uploadfiles"))) . "</li>";
if($event->registration_needed)	{
	$toolLinks .= "<li>" . elgg_view("output/url", array("href" => "events/registrationform/edit/" . $event->getGUID(), "text" => elgg_echo("event_manager:event:editquestions"))) . "</li>";
}

$toolLinks .= "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/attendees/export?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:exportattendees"))) . "</li>";
if($event->waiting_list_enabled && $event->countWaiters()) {
	$toolLinks .= "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/attendees/export_waitinglist?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:exportwaitinglist"))) . "</li>";
}
if ($num_of_colleagues) {
	$confirmText = elgg_echo('event_manager:event:notify_colleagues:confirm',array($num_of_colleagues));
	$toolLinks .= "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/notify_colleagues?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:notify_colleagues"), 'class'=>'gc-warn-user-'.$event_guid)) . "</li>";
}
$toolLinks .= "<li>" . elgg_view('output/url', array( 'rel' => $event->getGUID(),'text' => elgg_echo('event_manager:event:notify_groups'), 'href' => '#', 'class' => 'event-manager-notify-groups')). "</li>";
$toolLinks .= "</ul>";
echo $toolLinks;
$of .=<<<__HTML
<script>
$(".gc-warn-user-$event_guid").live('click',function (e){
	var confirmText = '$confirmText';
        if (!confirm(confirmText)) {
                e.preventDefault();
        }
});
</script>
__HTML;
echo $of;
?>
