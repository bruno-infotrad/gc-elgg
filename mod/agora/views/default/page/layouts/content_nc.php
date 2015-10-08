<?php
/**
 * Main content area layout
 *
 * @uses $vars['content']        HTML of main content area
 * @uses $vars['sidebar']        HTML of the sidebar
 * @uses $vars['header']         HTML of the content area header (override)
 * @uses $vars['nav']            HTML of the content area nav (override)
 * @uses $vars['footer']         HTML of the content area footer
 * @uses $vars['filter']         HTML of the content area filter (override)
 * @uses $vars['title']          Title text (override)
 * @uses $vars['context']        Page context (override)
 * @uses $vars['filter_context'] Filter context: everyone, friends, mine
 * @uses $vars['class']          Additional class to apply to layout
 */

// give plugins an opportunity to add to content sidebars
$params = $vars;
$params['content'] = $sidebar_content;

// allow page handlers to override the default header
if (isset($vars['header'])) {
	$vars['header_override'] = $vars['header'];
}
$header = elgg_view('page/layouts/content/header', $vars);

// allow page handlers to override the default filter
if (isset($vars['filter'])) {
	$vars['filter_override'] = $vars['filter'];
}
$filter = elgg_view('page/layouts/content/filter', $vars);
//Uload button for groups
$header = elgg_view_menu('title', array(
                'sort_by' => 'priority',
                'class' => 'elgg-menu-hz',
        ));
$header .= '<h1>'.elgg_echo("file:type:").'</h1>';
$header = "<div class='title-box'>".$header."</div>";
// the all important content
$content = elgg_extract('content', $vars, '');

//$body = $header . $filter . $content . $footer;
$body = $filter.$header.$content;

$params = array(
	'content' => $body,
	'sidebar' => $sidebar,
);
if (isset($vars['class'])) {
	$params['class'] = $vars['class'];
}
//echo elgg_view_layout('one_sidebar', $params);
echo $body;
