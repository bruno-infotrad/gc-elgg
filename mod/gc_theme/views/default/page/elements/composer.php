<?php 
$class = elgg_extract('class', $vars);
echo '<div class="elgg-composer '.$class.'">';
echo elgg_view_menu('composer', array(
	'entity' => elgg_get_page_owner_entity(),
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
));
echo '</div>';
?>
<script>
$('.elgg-composer').tabs({
	spinner: '',
	panelTemplate: '<div><div class="elgg-ajax-loader"></div></div>'
});
</script>
