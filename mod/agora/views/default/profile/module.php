<?php
/**
 * Group module (also called a group widget)
 *
 * @uses $vars['title']    The title of the module
 * @uses $vars['content']  The module content
 * @uses $vars['add_link'] A link to create content
 */

$group = elgg_get_page_owner_entity();

$header = "<span class=\"groups-widget-viewall\">{$vars['all_link']}</span>";
$header .= '<h3>' . $vars['title'] . '</h3>';

if ($group->canWriteToContainer() && isset($vars['add_link'])) {
	$vars['content'] .= "<span class='elgg-widget-more'>{$vars['add_link']}</span>";
}

echo '<li>';
//error_log($vars['content'],3,'/tmp/bla');
echo elgg_view_module('info', '', $vars['content'], array(
	'header' => $header,
	'class' => 'elgg-module-group',
));
echo '</li>';
