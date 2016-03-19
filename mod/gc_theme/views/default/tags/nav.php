<?php
/**
 * Members navigation
 */

$tabs = array(
	'all' => array(
		'title' => elgg_echo('tags:since_beginning'),
		'url' => "tags/all",
		'selected' => $vars['selected'] == 'all',
	),
	'one_year' => array(
		'title' => elgg_echo('tags:one_year'),
		'url' => "tags/one_year",
		'selected' => $vars['selected'] == 'one_year',
	),
	'six_months' => array(
		'title' => elgg_echo('tags:six_months'),
		'url' => "tags/six_months",
		'selected' => $vars['selected'] == 'six_months',
	),
	'one_month' => array(
		'title' => elgg_echo('tags:one_month'),
		'url' => "tags/one_month",
		'selected' => $vars['selected'] == 'one_month',
	),
);
//echo '<div class="elgg-head">'.$add_button.'<h1>'.elgg_echo('gc_theme:people').'</h1></div>';
echo elgg_view('members/navtabs', array('tabs' => $tabs));
