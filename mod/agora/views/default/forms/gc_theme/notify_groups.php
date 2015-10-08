<?php
$content = '';
$group_list = elgg_extract('group_list', $vars, '');
$event_guid = elgg_extract('event_guid', $vars, 0);
$container_guid = get_entity($event_guid)->container_guid;
foreach($group_list as $group){
	$group_guid = $group->guid;
	if ($group_guid != $container_guid) {
		$content .= elgg_view('input/checkbox',array('name'=>$group->name,'value'=>$group_guid,'class'=>'contribute-to')).$group->name.'<br>';
	}
}
if ($event_guid) {
	$label = elgg_echo('event_manager:notify:groups:button');
        $id = 'select-notify-groups';
	$content .= elgg_view('input/hidden', array('name' => 'event_guid', 'value' => $event_guid,'id' => 'event-guid'));
	$content .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => '','id' => 'container-guid'));
	$content .= elgg_view('input/submit', array(
        	'value' => $label,
		'rel' => $event_guid,
	        'class' => "elgg-button elgg-button-submit",
	        'id' => $id,
		'disabled' => 'disabled'
	));
}
echo $content;
?>
