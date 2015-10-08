<?php
/**
 * Discussion topic add/edit form body
 *
 */

$guid = elgg_extract('guid', $vars, null);
$gft = elgg_extract('gft', $vars, null);
if ($gft) {
	//Ajax inline edit, backfill whatever
	$title = $gft->title;
	$desc = $gft->description;
	$status = $gft->status;
	$tags = $gft->tags;
	$access_id = $gft->access_id;
	$container_guid = $gft->container_guid;
	?>
	<div>
		<?php echo elgg_view('input/hidden', array('name' => 'title', 'value' => $title)); ?>
	</div>
	<div>
		<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
	</div>
	<div>
		<?php echo elgg_view('input/hidden', array('name' => 'tags', 'value' => $tags)); ?>
	</div>
	<div>
		<?php echo elgg_view('input/hidden', array('name' => 'status', 'value' => $status)); ?>
	</div>
	<div>
		<?php echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id)); ?>
	</div>
<?php
} else {
	$title = elgg_extract('title', $vars, '');
	$desc = elgg_extract('description', $vars, '');
	$status = elgg_extract('status', $vars, '');
	$tags = elgg_extract('tags', $vars, '');
	$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
	$container_guid = elgg_extract('container_guid', $vars);
	?>
	<div>
		<label><?php echo elgg_echo('title'); ?></label><br />
		<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
	</div>
	<div>
		<label><?php echo elgg_echo('groups:topicmessage'); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
	</div>
	<div>
		<label><?php echo elgg_echo('tags'); ?></label>
		<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
	</div>
	<div>
	    <label><?php echo elgg_echo("groups:topicstatus"); ?></label><br />
		<?php
			echo elgg_view('input/select', array(
				'name' => 'status',
				'value' => $status,
				'options_values' => array(
					'open' => elgg_echo('status:open'),
					'closed' => elgg_echo('status:closed'),
				),
			));
		?>
	</div>
	<div>
		<label><?php echo elgg_echo('access'); ?></label><br />
		<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
	</div>
<?php
}
?>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'topic_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save"),'id' => 'gft-submit-button'));
echo elgg_view('input/button', array( 'value' => elgg_echo('cancel'), 'class' => 'elgg-button-cancel mlm', 'id' => 'gft-cancel-button', 'href' => $entity ? $entity->getURL() : '#',));

?>
</div>
