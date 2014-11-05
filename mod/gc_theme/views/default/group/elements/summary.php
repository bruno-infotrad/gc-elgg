<?php
/**
 * Object summary
 *
 * Sample output
 * <ul class="elgg-menu elgg-menu-entity"><li>Public</li><li>Like this</li></ul>
 * <h3><a href="">Title</a></h3>
 * <p class="elgg-subtext">Posted 3 hours ago by George</p>
 * <p class="elgg-tags"><a href="">one</a>, <a href="">two</a></p>
 * <div class="elgg-content">Excerpt text</div>
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity menu and metadata (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['tags']      HTML for the tags (optional)
 * @uses $vars['content']   HTML for the entity content (optional)
 */

$entity = $vars['entity'];

if (elgg_instanceof($entity, 'group') && ((strpos(full_url(), 'newest') != false)||(strpos(full_url(), 'filter') === false))) {
	$last_update=$entity->time_created;
	$group_activity=elgg_get_entities(array( 'wheres' => array("e.type != 'user' AND e.subtype != '9' AND e.container_guid = $entity->guid"),'order_by' => ('time_created'),'reverse_order_by' => true));
	elgg_log("BRUNO GROUP_ACTIVITY=".var_export($group_activity,true),'NOTICE');
	elgg_log("BRUNO FILTER =".full_url(),'NOTICE');
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

$metadata = elgg_extract('metadata', $vars, '');
$subtitle = elgg_extract('subtitle', $vars, '');
$content = elgg_extract('content', $vars, '');

$tags = elgg_extract('tags', $vars, '');
if ($tags !== false) {
	$tags = elgg_view('output/tags', array('tags' => $entity->tags));
}

if ($metadata) {
	echo $metadata;
}
echo "<h3>$title_link</h3>";
echo "<div class=\"elgg-subtext\">$subtitle";
echo elgg_view('object/summary/extend', $vars);
if (elgg_instanceof($entity, 'group') && ((strpos(full_url(), 'newest') != false)||(strpos(full_url(), 'filter') === false))) {
	echo "<div style=\"float:right;\">$last_updated</div>";
}
echo "</div>";
echo $tags;

if ($content) {
	echo "<div class=\"elgg-content\">$content</div>";
}
