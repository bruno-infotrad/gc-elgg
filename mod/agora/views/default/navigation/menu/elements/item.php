<?php
/**
 * A single element of a menu.
 *
 * @package Elgg.Core
 * @subpackage Navigation
 *
 * @uses $vars['item']       ElggMenuItem
 * @uses $vars['item_class'] Additional CSS class for the menu item
 */

$item = $vars['item'];

$link_class = 'elgg-menu-closed';
if ($item->getSelected()) {
	// @todo switch to addItemClass when that is implemented
	//$item->setItemClass('elgg-state-selected');
	if (elgg_get_context() == 'admin') {
		$link_class = 'elgg-menu-opened';
	}
}

$children = $item->getChildren();

if ($children) {
	$child_selected = false;
	$init_class = '';
	if (elgg_get_context() == 'admin') {
		$item->addLinkClass($link_class);
		$item->addLinkClass('elgg-menu-parent');
	} else {
		foreach ($children as $child) {
			$GLOBALS['GC_THEME']->debug('BRUNO menuitem:children '. $child->getName());
			$GLOBALS['GC_THEME']->debug('BRUNO menuitem:children '. $child->getSelected());
			if ($child->getSelected()) {
				$child_selected = true;
				$link_class = 'elgg-menu-opened';
				$init_class = 'elgg-child-menu-opened';
				break;
			}
		}
		$item->addLinkClass($link_class);
	}
}

$item_class = $item->getItemClass();
if ($item->getSelected()) {
	if (elgg_get_context() == 'admin'|| !$children || ! $child_selected) {
		$item_class = "$item_class elgg-state-selected";
	} else {
		$item_class = "$item_class elgg-child-selected";
	}
}
if (isset($vars['item_class']) && $vars['item_class']) {
	$item_class .= ' ' . $vars['item_class'];
}

echo "<li class=\"$item_class\">";
echo elgg_view_menu_item($item);
//echo $item->getContent();
if ($children && (elgg_get_context() != 'admin')) {
	if (preg_match('/elgg-menu-item-message/',$item_class)||
	   (preg_match('/elgg-menu-item-news/',$item_class))||
	   (preg_match('/elgg-menu-item-group/',$item_class))){
		//echo "<div><i style='right:5px;top:0;display:block;position:absolute;overflow:hidden;z-index:10;cursor:pointer;' class='elgg-menu-parent $link_class'>&#x25bc;</i>";
		echo "<div><i id='elgg-expandable' class='elgg-menu-parent $link_class'></i>";
	}
}
if ($children) {
	echo elgg_view('navigation/menu/elements/section', array(
		'items' => $children,
		'class' => "elgg-menu elgg-child-menu $init_class",
	));
}
if ($children && (elgg_get_context() != 'admin')) {
	if (preg_match('/elgg-menu-item-message/',$item_class)||
	   (preg_match('/elgg-menu-item-news/',$item_class))||
	   (preg_match('/elgg-menu-item-group/',$item_class))){
		echo "</div>";
	}
}
echo '</li>';
