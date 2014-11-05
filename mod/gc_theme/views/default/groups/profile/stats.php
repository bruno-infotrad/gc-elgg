<?php 

$group = $vars['entity'];
$group_members = $vars['group_members'];
$owner = $group->getOwnerEntity();

?>
<div class="elgg-group-stats">
<dl class="elgg-profile">
	<dt><?php echo elgg_echo("groups:owner"); ?></dt>
	<dd>
		<?php
			echo elgg_view('output/url', array(
				'text' => $owner->name,
				'value' => $owner->getURL(),
			));
		?>
	</dd>
	<dt><?php echo elgg_echo('groups:members'); ?></dt>
	<dd><?php echo $group->getMembers(0, 0, TRUE); ?></dd>
</dl>
</div>
<div class="elgg-group-members">
	<?php echo $group_members; ?>
</div>
