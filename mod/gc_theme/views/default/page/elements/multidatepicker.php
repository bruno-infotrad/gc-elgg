<?php 
elgg_load_js('jquery.multidatespicker');
$setdates=$setattdates=$setmydates=0;
$user_event_preferred_tab = elgg_get_logged_in_user_entity()->event_preferred_tab;
if (!$user_event_preferred_tab) {
	$user_event_preferred_tab = 'all';
}
if ($user_event_preferred_tab == 'all') {
	$list=1;
	$meattending = 0;
	$owning = 0;
} else if ($user_event_preferred_tab == 'attending') {
	$list=0;
	$meattending = 1;
	$owning = 0;
} elseif ($user_event_preferred_tab == 'mine') {
	$list=0;
	$meattending = 0;
	$owning = 1;
}
foreach ($vars["entities"] as $entity) {
	if ($setdates) {
		$setdates .= ',"'.date('y-n-d',$entity->start_day).'"';
		if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
			$numeric_sd = date('U',$entity->start_day);
			$numeric_ed = date('U',$entity->end_ts);
			$ed = $numeric_sd + 24*3600;
			$setdates .= ',"'.date('y-n-d',$ed).'"';
			while ($ed < $numeric_ed) {
				$setdates .= ',"'.date('y-n-d',$ed).'"';
				$ed += 24*3600;
			}
		}
	} else {
		$setdates = '"'.date('y-n-d',$entity->start_day).'"';
		$startdate = $setdates;
		if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
			$numeric_sd = date('U',$entity->start_day);
			$numeric_ed = date('U',$entity->end_ts);
			$ed = $numeric_sd + 24*3600;
			$setdates .= ',"'.date('y-n-d',$ed).'"';
			while ($ed < $numeric_ed) {
				$setdates .= ',"'.date('y-n-d',$ed).'"';
				$ed += 24*3600;
			}
		}
	}
	if (check_entity_relationship($entity->guid, EVENT_MANAGER_RELATION_ATTENDING, elgg_get_logged_in_user_guid())) {
		if ($setattdates) {
			$setattdates .= ',"'.date('y-n-d',$entity->start_day).'"';
			if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
				$numeric_sd = date('U',$entity->start_day);
				$numeric_ed = date('U',$entity->end_ts);
				$ed = $numeric_sd + 24*3600;
				$setattdates .= ',"'.date('y-n-d',$ed).'"';
				while ($ed < $numeric_ed) {
					$setattdates .= ',"'.date('y-n-d',$ed).'"';
					$ed += 24*3600;
				}
			}
		} else {
			$setattdates = '"'.date('y-n-d',$entity->start_day).'"';
			$attstartdate = $setattdates;
			if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
				$numeric_sd = date('U',$entity->start_day);
				$numeric_ed = date('U',$entity->end_ts);
				$ed = $numeric_sd + 24*3600;
				$setattdates .= ',"'.date('y-n-d',$ed).'"';
				while ($ed < $numeric_ed) {
					$setattdates .= ',"'.date('y-n-d',$ed).'"';
					$ed += 24*3600;
				}
			}
		}
	}
	if ($entity->owner_guid == elgg_get_logged_in_user_guid()) {
		if ($setmydates) {
			$setmydates .= ',"'.date('y-n-d',$entity->start_day).'"';
			if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
				$numeric_sd = date('U',$entity->start_day);
				$numeric_ed = date('U',$entity->end_ts);
				$ed = $numeric_sd + 24*3600;
				$setmydates .= ',"'.date('y-n-d',$ed).'"';
				while ($ed < $numeric_ed) {
					$setmydates .= ',"'.date('y-n-d',$ed).'"';
					$ed += 24*3600;
				}
			}
		} else {
			$setmydates = '"'.date('y-n-d',$entity->start_day).'"';
			$mystartdate = $setmydates;
			if (($entity->end_ts - $entity->start_day)/(24*3600) >= 1) {
				$numeric_sd = date('U',$entity->start_day);
				$numeric_ed = date('U',$entity->end_ts);
				$ed = $numeric_sd + 24*3600;
				$setmydates .= ',"'.date('y-n-d',$ed).'"';
				while ($ed < $numeric_ed) {
					$setmydates .= ',"'.date('y-n-d',$ed).'"';
					$ed += 24*3600;
				}
			}
		}
	}
}
if (! $startdate) {$startdate = date('y-n-d');}
if (! $attstartdate) {$attstartdate = $startdate;}
if (! $mystartdate) {$mystartdate = $startdate;}
$of ='<div class="gc-datepicker"><div id="all-events"><h4>'.elgg_echo('event_manager:list:navigation:list').'</h4></div><div id="att-events"><h4>'.elgg_echo('event_manager:event:relationship:event_attending').'</h4></div><div id="my-events"><h4>'.elgg_echo('event_manager:event:relationship:my_events').'</h4></div></div>';
$of .=<<<__HTML
<script>
var setdates='$setdates', setattdates='$setattdates', setmydates='$setmydates';
if ($list) {
 $('#att-events').hide();
 $('#my-events').hide();
} else if  ($meattending) {
 $('#all-events').hide();
 $('#my-events').hide();
} else if  ($owning) {
 $('#all-events').hide();
 $('#att-events').hide();
}
setTimeout(function(){
	if (setdates) {
		$('#all-events').multiDatesPicker({ dateFormat: "y-m-d", addDates: [$setdates]}).datepicker("setDate", $startdate );
	} else {
		$('#all-events').multiDatesPicker({ dateFormat: "y-m-d"}).datepicker("setDate", $startdate );
	}
	if (setattdates) {
		$('#att-events').multiDatesPicker({ dateFormat: "y-m-d", addDates: [$setattdates]}).datepicker("setDate", $attstartdate );
	} else {
		$('#att-events').multiDatesPicker({ dateFormat: "y-m-d"}).datepicker("setDate", $attstartdate );
	}
	if (setmydates) {
		$('#my-events').multiDatesPicker({ dateFormat: "y-m-d", addDates: [$setmydates] }).datepicker("setDate", $mystartdate );
	} else {
		$('#my-events').multiDatesPicker({ dateFormat: "y-m-d"}).datepicker("setDate", $mystartdate );
	}
},3500);
</script>
__HTML;
echo $of;
