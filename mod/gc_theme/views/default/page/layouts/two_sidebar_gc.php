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
				//echo elgg_view_layout('content_nc', array( "title" => '', "content" => $vars['content'], "filter" => $vars['filter']));
			}
		?>
	</div>
	<div class="elgg-sidebar-alt-river">
		<?php if (elgg_is_logged_in()) {
			echo elgg_view('page/elements/sidebar_alt', $vars);
		}?>
	</div>
</div>

