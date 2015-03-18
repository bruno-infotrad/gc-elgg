<?php
$event = $vars['item']->getObjectEntity();
$unix_start_day = $event->start_day;
$start_day = date('y-n-d',$unix_start_day);
$icon .= "<div class='event_manager_event_list_icon gc-river-event' title='" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $unix_start_day) . "'>";
$icon .= "<div class='event_manager_event_list_icon_month'>" . strtoupper(date("M", $unix_start_day)) . "</div>";
$icon .= "<div class='event_manager_event_list_icon_day'>" . date("d", $unix_start_day) . "</div>";
$icon .= "</div>";
if($event->icontime){
            $icon .= '<div class="gc-river-event gc-river-event-icon"><img src="' . $event->getIcon('small') . '" /></div>';
}
$vars['attachments'] = $icon;
echo elgg_view('river/item', $vars);
