<?php
$entity = $vars['entity'];
$user = elgg_get_logged_in_user_entity();
$site_url = elgg_get_site_url();

if (elgg_instanceof($entity, 'group') && (strpos(full_url(), 'list') != false)) {
	$last_update=$entity->time_created;
	$last_group_activity=gc_get_last_group_activity($entity->guid);
	if ($last_group_activity && $last_group_activity > $last_update) {
		$last_update = $last_group_activity;
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

echo "<h4>$title_link</h4>";
echo elgg_view('object/summary/extend', $vars);
if (elgg_instanceof($entity, 'group') && ((strpos(full_url(), 'list') != false)||(strpos(full_url(), 'filter') === false))) {
	echo "<div class=\"last-updated\">$last_updated</div>";
}

