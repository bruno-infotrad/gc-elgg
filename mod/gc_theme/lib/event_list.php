<?php
if (elgg_is_logged_in()) {
	 if (!$start_day = get_input('start_day')) {
                exit;
        }
	$start_day=intval($start_day/1000);
	$end_day = $start_day+24*3600;
	$event_options = array('start_day'=>$start_day,'end_day'=>$end_day);
	$events = event_manager_search_events($event_options);
	$entities = $events["entities"];
	$count = $events["count"];
	$result = elgg_view("event_manager/search_result", array("entities" => $entities, "count" => $count));
	echo $result;
}
