<?php
/**
 * Elgg 2 sidebar layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] The content string for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 * @uses $vars['sidebar_alt'] Optional content that is displayed in the alternate sidebar
 * @uses $vars['class']   Additional class to apply to layout
 */

$class = 'elgg-layout elgg-layout-two-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
elgg_load_js('elgg.activity_stream');
?>

<div class="<?php echo $class; ?>">
	<div class="elgg-sidebar">
		<?php echo elgg_view('page/elements/sidebar', $vars); ?>
	</div>
	<div class="elgg-body">
		<?php echo elgg_view('page/elements/group_profile', $vars); ?>
		<?php
			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
	<div class="elgg-sidebar-alt-river">
		<?php if (elgg_is_logged_in()) {
			$page_owner = $vars['entity'];
			$user = elgg_get_logged_in_user_entity();
			if ($page_owner->isMember($user) || $page_owner->isPublicMembership()) {
                		echo elgg_view('groups/sidebar/search', array('entity' => $vars['entity']));
                		echo elgg_view_form('notificationsettings/single_groupsave', array(),array('group' => $vars['entity']));
				echo elgg_view('page/elements/sidebar_alt_river_activity', $vars);
			}
			echo elgg_view('page/elements/popular_discussions', $vars);
			echo elgg_view('page/elements/popular_groups', $vars);
		}?>
	</div>
</div>

