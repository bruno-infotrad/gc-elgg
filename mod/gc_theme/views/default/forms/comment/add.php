<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses ElggEntity $vars['entity'] The entity to comment on
 * @uses bool       $vars['inline'] Show a single line version of the form?
 */


if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	$id = elgg_extract('id', $vars, false);
	$canwrite = elgg_extract('canwrite', $vars, false);
	
	if ($inline) {
		if ($canwrite) {
			echo elgg_view('input/plaintext', array(
				'name' => 'generic_comment', 
				'id' => $id.'-textarea',
				'style' => 'height:22px;',
				//'onblur' => "changeHeight(\"$id\",'22px',\"$canwrite\")",
				'onfocus' => "elgg.user_handle(\"$id-textarea\");changeHeight(\"$id\",'60px',\"$canwrite\")",
				'placeholder' => elgg_echo('annotation:generic_comment:value:placeholder'),
			));
			echo elgg_view('input/submit', array(
				'value' => elgg_echo('post'), 
				//'style' => 'opacity:0;filter:alpha(opacity=0);width:100%;height:100%;',
				'style' => 'visibility: hidden;',
				'id' => $id.'-submit',
			));
		} else {
			echo elgg_view('input/plaintext', array(
				'name' => 'generic_comment', 
				'id' => $id.'-textarea',
				'style' => 'height:22px;',
				'disabled' => 'disabled',
				'value' => elgg_echo('groups:register_to_comment'),
			));
		}
	} else {
?>
		<div>
			<label><?php echo elgg_echo("generic_comments:add"); ?></label>
			<?php echo elgg_view('input/longtext', array('name' => 'generic_comment')); ?>
		</div>
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
}
