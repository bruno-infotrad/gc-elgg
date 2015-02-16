<?php

	$entity = elgg_extract("entity", $vars);
	$size = elgg_extract("size", $vars, "medium");
	
	if($size == "date") {
		$unix_start_day = $entity->start_day;
		$start_day = date('y-n-d',$unix_start_day);
		$unix_end_day = $entity->end_ts;
		$end_day = date('y-n-d',$unix_end_day);
		//echo "start_day=$start_day end_day=$end_day";
		if ($start_day == $end_day) {
			$icon = "<div class='event_manager_event_list_icon' title='" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $unix_start_day) . "'>";
			$icon .= "<div class='event_manager_event_list_icon_month'>" . strtoupper(date("M", $unix_start_day)) . "</div>";
			$icon .= "<div class='event_manager_event_list_icon_day'>" . date("d", $unix_start_day) . "</div>";
			$icon .= "</div>";
			$icon .= '<div class="gc-event-time">'.date('H:i',$entity->start_time).'<br>'.date('H:i',$entity->end_ts).'</div>';
		} else {
			$icon = "<div class='event_manager_event_list_icon' title='" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $unix_start_day) . "'>";
			$icon .= "<div class='event_manager_event_list_icon_month'>" . strtoupper(date("M", $unix_start_day)) . "</div>";
			$icon .= "<div class='event_manager_event_list_icon_day'>" . date("d", $unix_start_day) . "</div>";
			$icon .= "</div>";
			$icon .= '<div class="gc-event-time">'.date('H:i',$entity->start_time).'</div>';
			$icon .= "<div class='event_manager_event_list_icon' title='" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $unix_end_day) . "'>";
			$icon .= "<div class='event_manager_event_list_icon_month'>" . strtoupper(date("M", $unix_end_day)) . "</div>";
			$icon .= "<div class='event_manager_event_list_icon_day'>" . date("d", $unix_end_day) . "</div>";
			$icon .= "</div>";
			$icon .= '<div class="gc-event-time">'.date('H:i',$entity->end_ts).'</div>';
		}
		echo $icon;
	}
