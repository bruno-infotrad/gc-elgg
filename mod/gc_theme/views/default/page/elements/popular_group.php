<?php 
$group=$vars['group'];
$count=$vars['count'];
$owner = get_entity($group->owner_guid);
$text = $group->name;
$text = (strlen($text) > 203) ? substr($text,0,200).'...' : $text;
$icon = elgg_view_entity_icon($group, 'small');
$summary = elgg_view('output/url', array(
		'href' => $group->getURL(),
		//'text' => $count.' '.$text,
		'text' => $text,
	));
$summary .= '<br>'.$group->getMembers(0,0,true).' '.elgg_echo('groups:member');
echo '<div class="elgg-image-block clearfix"><div class="elgg-image">'.$icon.'</div><div class="elgg-body sidebar-discussion-item">'.$summary.'</div></div>';
