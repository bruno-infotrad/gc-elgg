<?php

	$entity = elgg_extract("entity", $vars);
	$size = elgg_extract("size", $vars, "medium");
	
	if($size == "date") {
		$start_day = $entity->start_day;
		
		$icon = "<div class='event_manager_event_list_icon' title='" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $start_day) . "'>";
		$icon .= "<div class='event_manager_event_list_icon_month'>" . strtoupper(date("M", $start_day)) . "</div>";
		$icon .= "<div class='event_manager_event_list_icon_day'>" . date("d", $start_day) . "</div>";
		$icon .= "</div>";
		
		echo $icon;
	} elseif($size == "tiny") {
		//echo '<div class="elgg-agora-icon elgg-agora-icon-eventregistration"></div>';
		echo '<div></div>';
	}
