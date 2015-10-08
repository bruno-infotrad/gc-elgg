<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 * 
 * @todo look into restructuring this so we are not calling elgg_list_entities()
 * in this view
 */

$owner_guid = get_input('owner');
$base_url = get_input('base_url');

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

$class = 'elgg-comments';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// work around for deprecation code in elgg_view()
unset($vars['internalid']);

echo "<div $id class=\"$class\">";

$html = elgg_list_entities(array(
	'base_url' => $base_url,
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $owner_guid,
	'reverse_order_by' => true,
	'full_view' => true,
	'limit' => 5,
	'preload_owners' => true,
	'distinct' => false,
));

echo $html;
echo '</div>';
