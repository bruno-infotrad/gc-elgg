<?php
/**
 * Body of river item
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 * @uses $vars['responses']   Alternate respones (comments, replies, etc.)
 */

$item = $vars['item'];

$menu = elgg_view_menu('river', array(
	'item' => $item,
	'sort_by' => 'priority',
));

// river item header
$timestamp = elgg_get_friendly_time($item->getTimePosted());

$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary', array('item' => $vars['item'])));
if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
	));
}

$object = $item->getObjectEntity();
$object_guid = $object->guid;
$container = $object->getContainerEntity();
$container_guid = $container->guid;
$river_guid = $item->id;
$message = elgg_extract('message', $vars, false);
if ($message !== false) {
	if ($object->owner_guid == elgg_get_logged_in_user_guid()) {
		$edit_area = 'edit_area';
		$edit_icon = "<div id=\"wire-edit-$river_guid\"></div>";
		//$edit_icon = "<div class=\"wire-edit\" onclick=\"$('#$object_guid-$container_guid').trigger('click');\"></div>";
	} else {
		$edit_area = '';
		$edit_icon = '';
	}
        if (strlen($message) > 500) {
		$message = "$edit_icon<div class=\"text collapsed\"><div class=\"$edit_area elgg-river-message\">$message</div></div>";
	} else {
		$message = "$edit_icon<div class=\"$edit_area elgg-river-message\">$message</div>";
	}
}

$attachments = elgg_extract('attachments', $vars, false);
if ($attachments !== false) {
	$attachments = "<div class=\"elgg-river-attachments\">$attachments</div>";
}

$responses = elgg_view('river/elements/responses', $vars);
if ($responses) {
	$responses = "<div class=\"elgg-river-responses-$object_guid\">$responses</div>";
}

$group_string = '';
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}

echo <<<RIVER
<div class="elgg-river-summary">$summary $group_string</div>
$message
$attachments
<span class="elgg-river-timestamp">$timestamp</span>
$menu
$responses
RIVER;
?>
