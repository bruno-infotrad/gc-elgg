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
 * @uses $vars['buttons']        Content header buttons (override)
 * @uses $vars['filter_context'] Filter context: everyone, friends, mine
 * @uses $vars['class']          Additional class to apply to layout
 */

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));
$full_url = current_page_url();

// allow page handlers to override the default filter
if (isset($vars['filter'])) {
	$vars['filter_override'] = $vars['filter'];
}
$context = elgg_get_context();
if ($context == 'groups') {
	if ((strpos($full_url, 'all') !== false)||(strpos($full_url, 'invitations') !== false)){
		if (strpos($full_url, 'polls') !== false){
			$header= elgg_view_menu('title', array('sort_by' => 'priority'));
			$header .= '<h1>'.elgg_echo("gc_theme:tabnav:polls").'</h1>';
		} else {
			$header = '<h1>'.elgg_echo("gc_theme:tabnav:$context").'</h1>';
		}
	} elseif (strpos($full_url, 'add') !== false) {
		$header = '<h1>'.elgg_echo("groups:add").'</h1>';
	}
} elseif ($context == 'bookmarks') {
	$url = elgg_get_site_url();
	$img = elgg_view('output/img', array( 'src' => 'mod/bookmarks/graphics/bookmarklet.gif', 'alt' => $title,));
	$bookmarklet = "<a href=\"javascript:location.href='{$url}bookmarks/add/$guid?address='" . "+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">" . $img . "</a>";
	$header.= elgg_view_menu('title', array('sort_by' => 'priority'));
	$header .= '<h1>'.elgg_echo("gc_theme:tabnav:$context").'</h1>';
	$header.= "<div id='gc_theme-bookmarklet'>".$bookmarklet."</div>";
} else {
	//$header= elgg_view('page/elements/title', $vars);
	$header= elgg_view_menu('title', array('sort_by' => 'priority'));
	if ((strpos($full_url, 'tag') !== false) && $context == 'thewire') {
		$header .= '<h1>'.elgg_echo("gc_theme:thewire:tags").'</h1>';
	} else {
		$header .= '<h1>'.elgg_echo("gc_theme:tabnav:$context").'</h1>';
	}
}
$header = "<div class='title-box'>".$header."</div>";
$filter = $header.elgg_view('page/layouts/content/filter', $vars);

// the all important content
$content = elgg_extract('content', $vars, '');

// optional footer for main content area
$footer_content = elgg_extract('footer', $vars, '');
$params = $vars;
$params['content'] = $footer_content;
$footer = elgg_view('page/layouts/content/footer', $params);

$params = array(
	'title' => $vars['title'],
	'content' => $filter . $content . $footer,
	//'sidebar' => elgg_extract('sidebar', $vars, ''),
	'sidebar_alt' => elgg_extract('sidebar', $vars, '').elgg_extract('sidebar_alt', $vars, ''),
);
if (isset($vars['class'])) {
	$params['class'] = $vars['class'];
}
echo elgg_view_layout('two_sidebar_gc', $params);
