<?php
$group=$vars['entity'];
if (! $group->isPublicMembership()) {
	echo "<div class='is-locked'>".elgg_view_entity($group,array('full_view' => 'gc_summary'))."</div>";
} else {
	echo elgg_view_entity($group,array('full_view' => 'gc_summary'));
}
