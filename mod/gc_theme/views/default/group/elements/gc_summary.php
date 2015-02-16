<?php
$entity = $vars['entity'];
$user = elgg_get_logged_in_user_entity();
$site_url = elgg_get_site_url();

//if (elgg_instanceof($entity, 'group') && ((strpos(full_url(), 'list') != false)||(strpos(full_url(), 'filter') === false))) {
if (elgg_instanceof($entity, 'group')) {
	$last_update=$entity->time_created;
	$group_activity=elgg_get_entities(array( 'wheres' => array("e.type != 'user' AND e.subtype != '9' AND e.container_guid = $entity->guid"),'order_by' => ('time_created'),'reverse_order_by' => true));
	if ($group_activity && $group_activity[0]->time_updated > $last_update) {
		$last_update = $group_activity[0]->time_updated;
	}
	$last_updated = elgg_echo('groups:last_updated').' '.elgg_view_friendly_time($last_update);
}

$title_link = elgg_extract('title', $vars, '');
if ($title_link === '') {
	if (isset($entity->title)) {
		$text = $entity->title;
	} else {
		$text = $entity->name;
	}
	$params = array(
		'text' => $text,
		'href' => $entity->getURL(),
		'is_trusted' => true,
	);
	$title_link = elgg_view('output/url', $params);
}
if ($entity->owner_guid == $user->getGUID()) {
	$join_requests = elgg_get_entities_from_relationship(array(
	                'type' => 'user',
	                'relationship' => 'membership_request',
	                'relationship_guid' => $entity->getGUID(),
	                'inverse_relationship' => true,
	                'limit' => 0,
	                'count' => true,
	        ));
	if ($join_requests > 0) {
		$join_url =elgg_view('output/url', array(
			'text' => $join_requests.' '.elgg_echo('groups:membershiprequests:short'),
			'href' => 'groups/requests/'.$entity->guid,
		));

	        $join_text = "<span id=\"gc-group-invitations-list\"> ".$join_url."</span>";
	}
}

$metadata = elgg_extract('metadata', $vars, '');
$members = gc_get_group_members($entity->guid, 0, 0, 0, TRUE);
if ($members == 1) {
	$members = $members.' '.elgg_echo('groups:onemember');
} else {
	$members = $members.' '.elgg_echo('groups:member');
}
if ($entity->isMember($user)) {
	$url=$site_url.'/action/groups/leave?group_guid='.$entity->getGUID();
	$url = elgg_add_action_tokens_to_url($url);
	$actions_url = elgg_echo('groups:leave');
	$join_leave_button = "<a href=\"$url\" class='elgg-button elgg-button-action elgg-button-leave'>$actions_url</a>";
} else {
	$url=$site_url.'/action/groups/join?group_guid='.$entity->getGUID();
	$url = elgg_add_action_tokens_to_url($url);
	if ($entity->isPublicMembership() || $entity->canEdit()) {
		$actions_url = elgg_echo('groups:join');
	} else {
		$actions_url = elgg_echo('groups:joinrequest');
	}
	$join_leave_button = "<a href=\"$url\" class='elgg-button elgg-button-action elgg-button-join'>$actions_url</a>";
}
$content = elgg_extract('content', $vars, '');

$tags = elgg_extract('tags', $vars, '');
if ($tags !== false) {
	$tags = elgg_view('output/tags', array('tags' => $entity->tags));
}

if ($metadata) {
	//echo $metadata;
}
echo "$join_text";
echo "<h4>$title_link</h4>";
echo "<div class=\"elgg-subtext\"><p class='group-size'>$members</p>";
echo $join_leave_button;
echo elgg_view('object/summary/extend', $vars);
//if (elgg_instanceof($entity, 'group') && ((strpos(full_url(), 'list') != false)||(strpos(full_url(), 'filter') === false))) {
if (elgg_instanceof($entity, 'group')) {
	echo "<div style=\"float:right;\">$last_updated</div>";
}
echo "</div>";
echo $tags;

if ($content) {
	echo "<div class=\"elgg-content\">$content</div>";
}
