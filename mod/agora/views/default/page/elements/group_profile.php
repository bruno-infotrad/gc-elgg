<?php
$group = elgg_get_page_owner_entity();
if ($group instanceof ElggGroup) {
	$dbprefix = elgg_get_config("dbprefix");
	$context = elgg_get_context();
	$owner = $group->getOwnerEntity();
	$members = gc_get_group_members($group->guid,0, 0, 0, TRUE);
	if ($members == 1) {
		$members_label = elgg_echo('groups:onemember');
	} else {
		$members_label = elgg_echo('groups:member');
	}
	if (group_gatekeeper(false)) {
		$members_url = elgg_view('output/url', array( 'text' => '<strong>'.$members.'</strong> '.$members_label, 'value' => '/groups/members/'.$group->guid));
	} else {
		$members_url = '<strong>'.$members.'</strong> '.$members_label;
	}
	if (isset($vars['class'])) {
		$class = "$class {$vars['class']}";
	}
	if ($context == 'profile') {
	?>
	<div id="group-info-cont" class="detail-info-cont">
			<?php echo elgg_view_entity_icon($group, 'medium'); ?>
			<div class="data-cont">
				<?php echo elgg_view('page/elements/group_title', $vars); ?>
				<div class="group-meta">
	        			<p><?php echo elgg_echo("agora:started_by");?>: <?php echo elgg_view('output/url', array( 'text' => $owner->name, 'value' => $owner->getURL(),));?></p>
	        			<p><?php echo elgg_echo("agora:active_since");?>: <?php echo strftime("%B %Y",$group->getTimeCreated());?></p>
				</div>
				<div class="counter-cont">
					<p><?php echo $members_url ;?></p>
				</div>
				<div class="briefdescription"><?php echo $group->briefdescription; ?></div>
				<div class="description"><?php echo $group->description; ?></div>
			</div>
	</div>
	<div id="group-controls-cont" class="section-controls-cont">
	<?php echo elgg_view_menu('title', array('sort_by' => 'priority'));?>
	</div>
	<?php } else {?>
	<div class="detail-info-cont">
			<?php echo elgg_view_entity_icon($group, 'small'); ?>
			<div class="data-cont">
				<?php $vars['title'] = $group->name.'-'.elgg_echo("agora:tabnav:$context");
				echo elgg_view('page/elements/group_title', $vars); ?>
			</div>
	</div>
	<?php echo elgg_view("agora/group_tabs", array("type" => $context)); ?>
	<?php } ?>

<?php } ?>
