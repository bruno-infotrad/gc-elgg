<?php
/**
 * Discussion topic reply form bofy
 *
 * @uses $vars['entity'] A discussion topic object
 * @uses $vars['inline'] Display a shortened form?
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {
	$inline = elgg_extract('inline', $vars, false);
	$id = elgg_extract('id', $vars, false);
	$canwrite = elgg_extract('canwrite', $vars, true);

	if ($inline) {
		if ($canwrite) {
			echo elgg_view('input/plaintext', array(
                	        'name' => 'group_topic_post',
                	        'id' => $id.'-textarea',
                	        'style' => 'height:22px;',
                	        'onblur' => "changeHeight(\"$id\",'22px',\"$canwrite\")",
                	        'onfocus' => "changeHeight(\"$id\",'60px',\"$canwrite\")",
				'placeholder' => elgg_echo('annotation:group_topic_post:value:placeholder'),
                	));
                	echo elgg_view('input/submit', array(
                	        'value' => elgg_echo('reply'),
                	        'style' => 'visibility: hidden;',
                	        'id' => $id.'-submit',
                	));
		} else {
			echo elgg_view('input/plaintext', array(
                	        'name' => 'group_topic_post',
                	        'id' => $id.'-textarea',
                	        'style' => 'height:22px;',
                	        'disabled' => 'disabled',
				'value' => elgg_echo('groups:register_to_reply'),
                	));
		}
	} else {
?>
	<div>
		<label><?php echo elgg_echo("reply"); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'group_topic_post')); ?>
	</div>
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	}
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID(),
	));
}
